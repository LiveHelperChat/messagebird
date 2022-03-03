<?php

/**
 * Direct integration with Mattermost
 * */
class erLhcoreClassExtensionMessagebird
{
    private static $persistentSession;

    public function __construct()
    {
        $dispatcher = erLhcoreClassChatEventDispatcher::getInstance();
        $dispatcher->listen('chat.webhook_incoming', 'erLhcoreClassExtensionMessagebird::incommingWebhook');
        $dispatcher->listen('chat.webhook_incoming_chat_started', 'erLhcoreClassExtensionMessagebird::incommingChatStarted');
    }

    /*
     * erLhcoreClassChatEventDispatcher::getInstance()->dispatch('chat.webhook_incoming_chat_started', array(
            'webhook' => & $incomingWebhook,
            'data' => & $payloadAll,
            'chat' => & $chat
        ));*/
    public static function incommingChatStarted($params)
    {
        if (
            isset($params['data']['message']['id']) &&
            isset($params['data']['message']['platform']) &&
            $params['data']['message']['platform'] == 'whatsapp'
        ) {
            $messageBird = LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdMessage::findOne(['sort' => 'id DESC', 'filter' => ['conversation_id' => $params['data']['message']['conversationId']]]);

            if (is_object($messageBird) && $messageBird->dep_id > 0) {
                // Chat
                $params['chat']->dep_id = $messageBird->dep_id;
                $params['chat']->updateThis(['update' => ['dep_id']]);

                // Save template message first before saving initial response in the lhc core
                $msg = new erLhcoreClassModelmsg();
                $msg->msg = $messageBird->message;
                $msg->chat_id = $params['chat']->id;
                $msg->user_id = $messageBird->user_id;
                $msg->time = $messageBird->created_at;
                erLhcoreClassChat::getSession()->save($msg);

                // Update message bird
                $messageBird->chat_id = $params['chat']->id;
                $messageBird->updateThis(['update' => ['chat_id']]);
            }
        }
    }

    /*
     * erLhcoreClassChatEventDispatcher::getInstance()->dispatch('chat.webhook_incoming', array(
        'webhook' => & $incomingWebhook,
        'data' => & $data
    ));*/
    public static function incommingWebhook($params)
    {
        if (
            isset($params['data']['message']['id']) &&
            isset($params['data']['message']['platform']) &&
            isset($params['data']['message']['type']) &&
            $params['data']['message']['platform'] == 'whatsapp' &&
            $params['data']['message']['type'] == 'hsm'
        ) {
            // Update message status
            $messageBird = LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdMessage::findOne(['filter' => ['mb_id_message' => $params['data']['message']['id']]]);

            if (!is_object($messageBird)) {
                $messageBird = new LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdMessage();
                $messageBird->template = $params['data']['message']['content']['hsm']['templateName'];
                $messageBird->language = $params['data']['message']['content']['hsm']['language']['code'];
                $messageBird->phone = str_replace('+','',$params['data']['message']['to']);
                $messageBird->initiation = LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdMessage::INIT_THIRD_PARTY;
                $messageBird->dep_id = $params['webhook']->dep_id;
                $messageBird->message = $params['data']['message']['content']['hsm']['templateName'];
                $messageBird->user_id = -1;
            }

            $statusMap = [
                'pending' => LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdMessage::STATUS_PENDING,
                'sent' => LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdMessage::STATUS_SENT,
                'delivered' => LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdMessage::STATUS_DELIVERED,
                'read' => LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdMessage::STATUS_READ,
            ];

            $messageBird->conversation_id = $params['data']['message']['conversationId'];
            $messageBird->status = $statusMap[$params['data']['message']['status']];
            $messageBird->saveThis();
            
            // We do not need to do anything else with these type of messages
            exit;
        }
    }

    public function run()
    {

    }

    public static function getSession() {
        if (! isset ( self::$persistentSession )) {
            self::$persistentSession = new ezcPersistentSession ( ezcDbInstance::get (), new ezcPersistentCodeManager ( './extension/messagebird/pos' ) );
        }
        return self::$persistentSession;
    }
}