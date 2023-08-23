<?php
/**
 * php cron.php -s site_admin -e messagebird -c cron/masssending
 * */

$db = ezcDbInstance::get();

$db->beginTransaction();

try {

    $stmt = $db->prepare('SELECT id FROM lhc_messagebird_message WHERE status = :status LIMIT :limit FOR UPDATE ');
    $stmt->bindValue(':limit',60,PDO::PARAM_INT);
    $stmt->bindValue(':status',\LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdMessage::STATUS_PENDING_PROCESS, PDO::PARAM_INT);
    $stmt->execute();
    $chatsId = $stmt->fetchAll(PDO::FETCH_COLUMN);

} catch (Exception $e) {
    // Someone is already processing. So we just ignore and retry later
    return;
}

if (!empty($chatsId)) {
    
    $instance = LiveHelperChatExtension\messagebird\providers\MessageBirdLiveHelperChat::getInstance();

    $templatesCache = [];

    // Delete indexed chat's records
    $stmt = $db->prepare('UPDATE lhc_messagebird_message SET status = :status WHERE id IN (' . implode(',', $chatsId) . ')');
    $stmt->bindValue(':status',\LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdMessage::STATUS_IN_PROCESS, PDO::PARAM_INT);
    $stmt->execute();
    $db->commit();

    $messages = \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdMessage::getList(['filterin' => ['id' => $chatsId]]);

    if (!empty($messages)) {
        foreach ($messages as $message) {
            
            if ($message->business_account_id > 0 && is_object($message->business_account)) {

                // Override variables by account
                $instance->setAccessKey($message->business_account->access_key);
                $instance->setChannelId($message->business_account->channel_id);
                $instance->setNamespace($message->business_account->template_id_namespace);

                $templates = isset($templatesCache[$message->business_account_id]) ? $templatesCache[$message->business_account_id] : $instance->getTemplates();
                $templatesCache[$message->business_account_id] = $templates;
            } else {
                $templates = isset($templatesCache[0]) ? $templatesCache[0] : $instance->getTemplates();
                $templatesCache[0] = $templates;
            }

            $instance->sendTemplate($message, $templates);
        }
    }

} else {
    $db->rollback();
}

?>