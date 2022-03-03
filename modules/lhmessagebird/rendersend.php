<?php

$tpl = erLhcoreClassTemplate::getInstance('lhmessagebird/rendersend.tpl.php');

$params = explode('||',$Params['user_parameters']['template']);

$tpl->setArray([
    'data' => (isset($_POST['data']) ? json_decode($_POST['data'],true) : []),
    'template' => LiveHelperChatExtension\messagebird\providers\MessageBirdLiveHelperChat::getInstance()->getTemplate($params[0], $params[1]),
]);

$response = explode('<!--=========||=========-->', $tpl->fetch());

echo json_encode([
    'preview' => $response[0],
    'form' => $response[1],
]);

exit;

?>