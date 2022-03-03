<?php

$tpl = erLhcoreClassTemplate::getInstance('lhmessagebird/templates.tpl.php');

try {
    $instance = LiveHelperChatExtension\messagebird\providers\MessageBirdLiveHelperChat::getInstance();
    $templates = $instance->getTemplates();
} catch (Exception $e) {
    $tpl->set('error', $e->getMessage());
    $templates = [];
}

$tpl->set('templates', $templates);

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
    array(
        'url' => erLhcoreClassDesign::baseurl('messagebird/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','MessageBird')
    ),
    array(
        'url' => erLhcoreClassDesign::baseurl('messagebird/settings'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Templates')
    )
);
?>