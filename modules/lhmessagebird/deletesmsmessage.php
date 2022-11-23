<?php

$currentUser = erLhcoreClassUser::instance();

if (!isset($_SERVER['HTTP_X_CSRFTOKEN']) || !$currentUser->validateCSFRToken($_SERVER['HTTP_X_CSRFTOKEN'])) {
    die('Invalid CSRF Token');
    exit;
}

$item = \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdSMSMessage::fetch($Params['user_parameters']['id']);
$item->removeThis();

echo "ok";
exit;

?>