<?php

$currentUser = erLhcoreClassUser::instance();

if (!isset($_SERVER['HTTP_X_CSRFTOKEN']) || !$currentUser->validateCSFRToken($_SERVER['HTTP_X_CSRFTOKEN'])) {
    die('Invalid CSRF Token');
    exit;
}

$instance = LiveHelperChatExtension\messagebird\providers\MessageBirdLiveHelperChat::getInstance();

if (is_numeric($Params['user_parameters']['business_account_id']) && $Params['user_parameters']['business_account_id'] > 0) {
    $account = \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdAccount::fetch($Params['user_parameters']['business_account_id']);
    $instance->setAccessKey($account->access_key);
}

$instance->deleteWebhook($Params['user_parameters']['id']);

echo "ok";
exit;

?>