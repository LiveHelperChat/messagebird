<?php

$tpl = erLhcoreClassTemplate::getInstance('lhmessagebird/rendertemplates.tpl.php');

$instance = \LiveHelperChatExtension\messagebird\providers\MessageBirdLiveHelperChat::getInstance();

if (is_numeric($Params['user_parameters']['business_account_id']) && $Params['user_parameters']['business_account_id'] > 0) {
    $account = \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdAccount::fetch($Params['user_parameters']['business_account_id']);
    $instance->setAccessKey($account->access_key);
}

$templates = $instance->getTemplates()['items'];

$tpl->setArray([
    'templates' => $templates,
]);

echo json_encode([
    'templates' => $tpl->fetch()
]);

exit;

?>