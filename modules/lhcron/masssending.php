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
    $templates = LiveHelperChatExtension\messagebird\providers\MessageBirdLiveHelperChat::getInstance()->getTemplates();

    // Delete indexed chat's records
    $stmt = $db->prepare('UPDATE lhc_messagebird_message SET status = :status WHERE id IN (' . implode(',', $chatsId) . ')');
    $stmt->bindValue(':status',\LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdMessage::STATUS_IN_PROCESS, PDO::PARAM_INT);
    $stmt->execute();
    $db->commit();

    $messages = \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdMessage::getList(['filterin' => ['id' => $chatsId]]);

    if (!empty($messages)) {
        foreach ($messages as $message) {
            LiveHelperChatExtension\messagebird\providers\MessageBirdLiveHelperChat::getInstance()->sendTemplate($message, $templates);
        }
    }

} else {
    $db->rollback();
}

?>