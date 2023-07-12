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
            $messageBird = LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdMessage::findOne(['sort' => 'id DESC', 'filtergt' => ['created_at' => (time() - 24 * 3600)], 'filter' => ['conversation_id' => $params['data']['message']['conversationId']]]);

            if (is_object($messageBird) && $messageBird->dep_id > 0) {

                // Save template only if it was not assigned to any chat yet
                if ($messageBird->chat_id == 0) {
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
                }

                // Update message bird
                $messageBird->chat_id = $params['chat']->id;
                $messageBird->updateThis(['update' => ['chat_id']]);
            }
        } else if ($params['webhook']->scope == 'messagebirdsms' && isset($params['data']['recipient'])) {

            $phone = \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdPhoneNumber::findOne(['filter' => ['phone' => $params['data']['recipient']]]);

            if (is_object($phone)) {
                $params['chat']->dep_id = $phone->dep_id;
                $params['chat']->updateThis(['update' => ['dep_id']]);

                // Perhaps it's a visitor reply to one of our messages
                $lastMessage = \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdSMSMessage::findOne(['filter' => ['phone' => $params['data']['sender'], 'sender_phone_id' => $phone->id]]);
                if (is_object($lastMessage) && $lastMessage->chat_id == 0) {
                    // Save template message first before saving initial response in the lhc core
                    $msg = new erLhcoreClassModelmsg();
                    $msg->msg = $lastMessage->message;
                    $msg->chat_id = $params['chat']->id;
                    $msg->user_id = $lastMessage->user_id;
                    $msg->time = $lastMessage->created_at;
                    erLhcoreClassChat::getSession()->save($msg);

                    // Update message bird
                    $lastMessage->chat_id = $params['chat']->id;
                    $lastMessage->updateThis(['update' => ['chat_id']]);
                }
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
                'rejected' => LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdMessage::STATUS_REJECTED,
            ];

            $messageBird->conversation_id = $params['data']['message']['conversationId'];

            $previousStatus = $messageBird->status;

            if ($messageBird->status != LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdMessage::STATUS_READ) {
                $messageBird->status = $statusMap[$params['data']['message']['status']];
            }

            if ($messageBird->status == LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdMessage::STATUS_REJECTED) {
                $messageBird->send_status_raw = $messageBird->send_status_raw . json_encode($params['data']);
                self::processRejected($params['data']);
            } elseif (
                $messageBird->status == LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdMessage::STATUS_SENT ||
                $messageBird->status == LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdMessage::STATUS_DELIVERED ||
                $messageBird->status == LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdMessage::STATUS_READ
            ) {
                self::processSent($params['data']);
            }

            $messageBird->saveThis();

            // Append as a message to active chat once message is delivered
            // Happens if an operator sends another message during active conversation
            if (
                $messageBird->status == \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdMessage::STATUS_DELIVERED ||
                (
                    $previousStatus != LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdMessage::STATUS_READ &&
                    $previousStatus != \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdMessage::STATUS_DELIVERED &&
                    $messageBird->status == \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdMessage::STATUS_READ
                )
            ) {

                $incomingWebhook = \erLhcoreClassModelChatIncomingWebhook::findOne(['filter' => ['name' => 'MessageBirdWhatsApp']]);

                if (is_object($incomingWebhook)) {

                    $presentChat = \erLhcoreClassModelChatIncoming::findOne(['sort' => '`id` DESC','filter' => [
                        'chat_external_id' => $messageBird->conversation_id,
                        'incoming_id' => $incomingWebhook->id]]);

                    if (is_object($presentChat)) {
                        $chat = $presentChat->chat;
                        if (is_object($chat) && ($chat->status == \erLhcoreClassModelChat::STATUS_ACTIVE_CHAT || $chat->status == \erLhcoreClassModelChat::STATUS_PENDING_CHAT)) {
                            $msg = new \erLhcoreClassModelmsg();
                            $msg->msg = $messageBird->message;
                            $msg->chat_id = $chat->id;
                            $msg->user_id = $messageBird->user_id;
                            $msg->time = $messageBird->created_at;

                            $supportUser = \erLhcoreClassModelUser::fetch($msg->user_id);

                            if (is_object($supportUser)) {
                                $msg->name_support = $supportUser->name_support;
                                \LiveHelperChat\Models\Departments\UserDepAlias::getAlias(array('scope' => 'msg', 'msg' => & $msg, 'chat' => $chat));
                            }

                            \erLhcoreClassChat::getSession()->save($msg);

                            $chat->last_msg_id = $msg->id;
                            $chat->updateThis(['update' => ['last_msg_id']]);

                            // For instant refresh back office operator
                            \erLhcoreClassChatEventDispatcher::getInstance()->dispatch('chat.messages_added_passive', array('msg' => & $msg, 'chat' => & $chat));
                        }
                    }
                }
            }

            // We do not need to do anything else with these type of messages
            exit;
            
        } else if (isset($params['data']['message']['id']) &&
            isset($params['data']['message']['platform']) &&
            isset($params['data']['message']['type']) &&
            $params['data']['message']['platform'] == 'whatsapp' &&
            $params['data']['type'] == 'message.updated') {

            $incomingChat = erLhcoreClassModelChatIncoming::findOne(array('filter' => array('chat_external_id' => $params['data']['message']['conversationId'])));

            // Chat was found, now we need to find exact message
            if ($incomingChat instanceof erLhcoreClassModelChatIncoming && is_object($incomingChat->chat)) {
                $statusMap = [
                    'pending' => erLhcoreClassModelmsg::STATUS_PENDING,
                    'sent' => erLhcoreClassModelmsg::STATUS_SENT,
                    'delivered' => erLhcoreClassModelmsg::STATUS_DELIVERED,
                    'read' =>  erLhcoreClassModelmsg::STATUS_READ,
                    'rejected' =>  erLhcoreClassModelmsg::STATUS_REJECTED
                ];
                $msg = erLhcoreClassModelmsg::findOne(['filter' => ['chat_id' => $incomingChat->chat->id], 'customfilter' => ['`meta_msg` != \'\' AND JSON_EXTRACT(meta_msg,\'$.iwh_msg_id\') = ' . ezcDbInstance::get()->quote($params['data']['message']['id'])]]);

                if (is_object($msg) && $msg->del_st != erLhcoreClassModelmsg::STATUS_READ) {

                    $msg->del_st = max($statusMap[$params['data']['message']['status']],$msg->del_st);
                    $msg->updateThis(['update' => ['del_st']]);

                    // Refresh message delivery status for op
                    $chat = $incomingChat->chat;
                    $chat->operation_admin .= "lhinst.updateMessageRowAdmin({$msg->chat_id},{$msg->id});";
                    if ($msg->del_st == erLhcoreClassModelmsg::STATUS_READ) {
                        $chat->has_unread_op_messages = 0;
                    }
                    $chat->updateThis(['update' => ['operation_admin','has_unread_op_messages']]);

                    // NodeJS to update message delivery status
                    erLhcoreClassChatEventDispatcher::getInstance()->dispatch('chat.message_updated', array('msg' => & $msg, 'chat' => & $chat));
                }
            }
            exit;

        } else if ($params['webhook']->scope == 'messagebirdsms' && isset($_GET['status']) && isset($_GET['id'])) {

            $lastMessage = \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdSMSMessage::findOne([
                'filter' => [
                    'mb_id_message' => (string)$_GET['id'],
                ]
            ]);

            if (is_object($lastMessage)) {
                $statusMap = [
                    'pending' => \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdSMSMessage::STATUS_PENDING,
                    'sent' => \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdSMSMessage::STATUS_SENT,
                    'delivered' => \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdSMSMessage::STATUS_DELIVERED,
                    'buffered' => \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdSMSMessage::STATUS_BUFFERED,
                    'expired' => \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdSMSMessage::STATUS_EXPIRED,
                    'delivery_failed' => \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdSMSMessage::STATUS_FAILED,
                    'scheduled' => \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdSMSMessage::STATUS_SCHEDULED,
                ];

                if (isset($statusMap[(string)$_GET['status']])) {
                    $lastMessage->status = $statusMap[(string)$_GET['status']];
                    $lastMessage->status_txt = mb_substr((string)$_GET['statusReason'], 0, 100);
                    $lastMessage->updateThis(['update' => ['status','status_txt']]);
                }

                // Insert message as a normal message to the last chat customer had
                // In case there is chosen reopen old chat
                // Which by the case is the default option of the extension
                if (in_array($lastMessage->status,[
                        \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdSMSMessage::STATUS_DELIVERED
                    ]) && $lastMessage->chat_id == 0) {

                    $presentConversation = \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdSMSMessage::findOne([
                        'filter' => [
                            'sender_phone_id' => $lastMessage->sender_phone_id,
                            'phone' => $lastMessage->phone
                        ],
                        'filternot' => [
                            'chat_id' => 0
                        ],
                        'sort' => '`id` DESC'
                    ]);

                    if (is_object($presentConversation)) {

                        $chat = erLhcoreClassModelChat::fetch($presentConversation->chat_id);

                        if (is_object($chat)) {
                            // Save template message first before saving initial response in the lhc core
                            $msg = new erLhcoreClassModelmsg();
                            $msg->msg = $lastMessage->message;
                            $msg->chat_id = $presentConversation->chat_id;
                            $msg->user_id = $lastMessage->user_id;
                            $msg->time = $lastMessage->created_at;
                            erLhcoreClassChat::getSession()->save($msg);

                            $chat->last_msg_id = $msg->id;
                            $chat->updateThis(['update' => ['last_msg_id']]);
                        }
                    }
                }
            }

            // Track message status on normal flow
            if (isset($_GET['reference']) && is_numeric($_GET['reference'])) {
                $statusMap = [
                    'pending' => \erLhcoreClassModelmsg::STATUS_PENDING,
                    'sent' => \erLhcoreClassModelmsg::STATUS_SENT,
                    'delivered' => \erLhcoreClassModelmsg::STATUS_READ,
                    'buffered' => \erLhcoreClassModelmsg::STATUS_PENDING,
                    'expired' => \erLhcoreClassModelmsg::STATUS_REJECTED,
                    'delivery_failed' => \erLhcoreClassModelmsg::STATUS_REJECTED,
                    'scheduled' => \erLhcoreClassModelmsg::STATUS_PENDING,
                ];

                $msg = erLhcoreClassModelmsg::findOne(['filter' => ['chat_id' => (int)$_GET['reference']], 'customfilter' => ['`meta_msg` != \'\' AND JSON_EXTRACT(meta_msg,\'$.iwh_msg_id\') = ' . ezcDbInstance::get()->quote((string)$_GET['id'])]]);

                if (is_object($msg) && $msg->del_st != erLhcoreClassModelmsg::STATUS_READ) {

                    $msg->del_st = max($statusMap[(string)$_GET['status']],$msg->del_st);
                    $msg->updateThis(['update' => ['del_st']]);

                    // Refresh message delivery status for op
                    $chat = erLhcoreClassModelChat::fetch($msg->chat_id);
                    $chat->operation_admin .= "lhinst.updateMessageRowAdmin({$msg->chat_id},{$msg->id});";
                    if ($msg->del_st == erLhcoreClassModelmsg::STATUS_READ) {
                        $chat->has_unread_op_messages = 0;
                    }
                    $chat->updateThis(['update' => ['operation_admin','has_unread_op_messages']]);

                    // NodeJS to update message delivery status
                    erLhcoreClassChatEventDispatcher::getInstance()->dispatch('chat.message_updated', array('msg' => & $msg, 'chat' => & $chat));
                }
            }

            // We do not need to do anything else with these type of messages
            exit;
        }
    }

    public static function processRejected($dataRejection) {
        $mbOptions = erLhcoreClassModelChatConfig::fetch('mb_options');
        $data = (array)$mbOptions->data;
        if (!isset($data['fail_counter'])) {
            $data['fail_counter'] = 0;
        }
        $data['fail_counter']++;

        if (isset($data['alert_email']) && $data['alert_email'] != '' && $data['fail_counter'] >= 5 && (!isset($data['fail_sent']) || $data['fail_sent'] == 0)) {
            $mail = new PHPMailer();
            $mail->CharSet = "UTF-8";
            $mail->FromName = 'Live Helper Chat MessageBird';
            $mail->Subject = 'Live Helper Chat MessageBird Sent Failure';
            $mail->Body = "Error message - \n" . json_encode($dataRejection, JSON_PRETTY_PRINT);

            $emailRecipient = explode(',',$data['alert_email']);

            foreach ($emailRecipient as $receiver) {
                $mail->AddAddress( trim($receiver) );
            }

            erLhcoreClassChatMail::setupSMTP($mail);
            $mail->Send();
            $data['fail_sent'] = 1;
        }

        $mbOptions->value = serialize($data);
        $mbOptions->saveThis();
    }

    public static function processSent($dataSent) {
        $mbOptions = erLhcoreClassModelChatConfig::fetch('mb_options');
        $data = (array)$mbOptions->data;
        if (isset($data['fail_counter']) && $data['fail_counter'] > 0) {
            $data['fail_counter'] = 0;
            $data['fail_sent'] = 0;
            $mbOptions->value = serialize($data);
            $mbOptions->saveThis();
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