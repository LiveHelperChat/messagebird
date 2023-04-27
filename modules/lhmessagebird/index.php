<?php
$tpl = erLhcoreClassTemplate::getInstance('lhmessagebird/index.tpl.php');

// SMS
if ($Params['user_parameters_unordered']['action'] == 'sms' && isset($Params['user_parameters_unordered']['csfr']) && $currentUser->validateCSFRToken($Params['user_parameters_unordered']['csfr'])) {
    \LiveHelperChatExtension\messagebird\providers\MessageBirdLiveHelperChatActivator::installOrUpdate();
    $tpl->set('update',true);
}

if ($Params['user_parameters_unordered']['action'] == 'removesms' && isset($Params['user_parameters_unordered']['csfr']) && $currentUser->validateCSFRToken($Params['user_parameters_unordered']['csfr'])) {
    \LiveHelperChatExtension\messagebird\providers\MessageBirdLiveHelperChatActivator::remove();
    $tpl->set('update',true);
}

// WhatsApp
if ($Params['user_parameters_unordered']['action'] == 'whatsapp' && isset($Params['user_parameters_unordered']['csfr']) && $currentUser->validateCSFRToken($Params['user_parameters_unordered']['csfr'])) {
    \LiveHelperChatExtension\messagebird\providers\MessageBirdLiveHelperChatActivator::installOrUpdateWhatsApp();
    $tpl->set('update',true);
}

if ($Params['user_parameters_unordered']['action'] == 'removewhatsapp' && isset($Params['user_parameters_unordered']['csfr']) && $currentUser->validateCSFRToken($Params['user_parameters_unordered']['csfr'])) {
    \LiveHelperChatExtension\messagebird\providers\MessageBirdLiveHelperChatActivator::removeWhatsApp();
    $tpl->set('update',true);
}

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
    array(
        'url' => erLhcoreClassDesign::baseurl('messagebird/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','MessageBird')
    )
);

?>