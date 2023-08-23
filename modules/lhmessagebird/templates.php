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

if (isset($_GET['doSearch'])) {
    $filterParams = erLhcoreClassSearchHandler::getParams(array('customfilterfile' => 'extension/messagebird/classes/filter/templates.php', 'format_filter' => true, 'use_override' => true, 'uparams' => $Params['user_parameters_unordered']));
    $filterParams['is_search'] = true;
} else {
    $filterParams = erLhcoreClassSearchHandler::getParams(array('customfilterfile' => 'extension/messagebird/classes/filter/templates.php', 'format_filter' => true, 'uparams' => $Params['user_parameters_unordered']));
    $filterParams['is_search'] = false;
}

$tpl = erLhcoreClassTemplate::getInstance('lhmessagebird/templates.tpl.php');

try {
    $instance = LiveHelperChatExtension\messagebird\providers\MessageBirdLiveHelperChat::getInstance();

    if (is_numeric($filterParams['input_form']->business_account_id) && $filterParams['input_form']->business_account_id > 0) {
        $businessAccount = \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdAccount::fetch($filterParams['input_form']->business_account_id);
        $instance->setAccessKey($businessAccount->access_key);
    }

    $templates = $instance->getTemplates();
} catch (Exception $e) {
    $tpl->set('error', $e->getMessage());
    $templates = [];
}

$tpl->set('templates', $templates);
$tpl->set('input',$filterParams['input_form']);

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