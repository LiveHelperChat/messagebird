<?php

$tpl = erLhcoreClassTemplate::getInstance('lhmessagebird/webhooks.tpl.php');

if (ezcInputForm::hasPostData() && isset($_POST['setWebhook'])) {

    if (!isset($_POST['csfr_token']) || !$currentUser->validateCSFRToken($_POST['csfr_token'])) {
        //erLhcoreClassModule::redirect('messagebird/webhooks');
        exit;
    }

    $Errors = \LiveHelperChatExtension\messagebird\providers\MessageBirdLiveHelperChatValidator::setWebhook();

    if (count($Errors) == 0) {
        try {
            erLhcoreClassModule::redirect('messagebird/webhooks');
            exit ;
        } catch (Exception $e) {
            $tpl->set('errors',array($e->getMessage()));
        }
    } else {
        $tpl->set('errors',$Errors);
    }
}



if (isset($_GET['doSearch'])) {
    $filterParams = erLhcoreClassSearchHandler::getParams(array('customfilterfile' => 'extension/messagebird/classes/filter/webhooks.php','format_filter' => true, 'use_override' => true, 'uparams' => $Params['user_parameters_unordered']));
    $filterParams['is_search'] = true;
} else {
    $filterParams = erLhcoreClassSearchHandler::getParams(array('customfilterfile' => 'extension/messagebird/classes/filter/webhooks.php','format_filter' => true, 'uparams' => $Params['user_parameters_unordered']));
    $filterParams['is_search'] = false;
}

try {
    $instance = LiveHelperChatExtension\messagebird\providers\MessageBirdLiveHelperChat::getInstance();
    if (is_numeric($filterParams['input_form']->business_account_id) && $filterParams['input_form']->business_account_id > 0) {
        $businessAccount = \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdAccount::fetch($filterParams['input_form']->business_account_id);
        $instance->setAccessKey($businessAccount->access_key);
    }
    $webhooks = $instance->getWebhooks();
} catch (Exception $e) {
    $tpl->set('error', $e->getMessage());
    $webhooks = [];
}

$tpl->set('input',$filterParams['input_form']);
$tpl->set('webhooks', $webhooks);
$tpl->set('instance', $instance);

$Result['content'] = $tpl->fetch();
$Result['path'] = array(
    array('url' => erLhcoreClassDesign::baseurl('messagebird/index'), 'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('module/fbmessenger','MessageBird')),
    array(
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('module/fbmessenger', 'WhatsApp Webhooks')
    )
);

?>