<?php
$tpl = erLhcoreClassTemplate::getInstance('lhmessagebird/index.tpl.php');

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
    array(
        'url' => erLhcoreClassDesign::baseurl('messagebird/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','MessageBird')
    )
);

?>