<?php

if ($Params['user_parameters_unordered']['action'] == 'disable') {
    if (!isset($_SERVER['HTTP_X_CSRFTOKEN']) || !$currentUser->validateCSFRToken($_SERVER['HTTP_X_CSRFTOKEN'])) {
        exit;
    }

    $templateDisabled = \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdTemplateDisabled::findOne(['filter' => ['id' => $Params['user_parameters_unordered']['id']]]);

    if (!is_object($templateDisabled) && $Params['user_parameters_unordered']['checked'] === 'true') {
        $templateDisabled = new \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdTemplateDisabled();
        $templateDisabled->id = (int)$Params['user_parameters_unordered']['id'];
        $templateDisabled->saveThis();
    } elseif (is_object($templateDisabled) && $Params['user_parameters_unordered']['checked'] === 'false') {
        $templateDisabled->removeThis();
    }

    exit;
}



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