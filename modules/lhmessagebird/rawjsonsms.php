<?php

$tpl = erLhcoreClassTemplate::getInstance('lhmessagebird/rawjson.tpl.php');

$item = \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdSMSMessage::fetch($Params['user_parameters']['id']);
$tpl->set('item',$item);

echo $tpl->fetch();
exit;

?>