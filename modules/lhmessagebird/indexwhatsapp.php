<?php
$tpl = erLhcoreClassTemplate::getInstance('lhmessagebird/indexwhatsapp.tpl.php');


$Result['content'] = $tpl->fetch();

$Result['path'] = array(
    array(
        'url' => erLhcoreClassDesign::baseurl('messagebird/indexwhatsapp'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','WhatsApp')
    )
);

?>