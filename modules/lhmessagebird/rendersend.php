<?php

$tpl = erLhcoreClassTemplate::getInstance('lhmessagebird/rendersend.tpl.php');

$params = explode('||',$Params['user_parameters']['template']);

$instance = \LiveHelperChatExtension\messagebird\providers\MessageBirdLiveHelperChat::getInstance();

if (is_numeric($Params['user_parameters']['business_account_id']) && $Params['user_parameters']['business_account_id'] > 0) {
    $businessAccount = \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdAccount::fetch($Params['user_parameters']['business_account_id']);
    $instance->setAccessKey($businessAccount->access_key);
}

$tpl->setArray([
    'data' => (isset($_POST['data']) ? json_decode($_POST['data'],true) : []),
    'template' => $instance->getTemplate($params[0], $params[1]),
]);

$response = explode('<!--=========||=========-->', $tpl->fetch());

echo json_encode([
    'preview' => $response[0],
    'form' => $response[1],
]);

exit;

?>