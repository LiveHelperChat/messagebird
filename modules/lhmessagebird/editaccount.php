<?php

$tpl = erLhcoreClassTemplate::getInstance('lhmessagebird/editaccount.tpl.php');

$item = \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdAccount::fetch($Params['user_parameters']['id']);

if (ezcInputForm::hasPostData()) {

    if (!isset($_POST['csfr_token']) || !$currentUser->validateCSFRToken($_POST['csfr_token'])) {
        erLhcoreClassModule::redirect('messagebird/account');
        exit;
    }

    $Errors = \LiveHelperChatExtension\messagebird\providers\MessageBirdLiveHelperChatValidator::validateAccount($item);

    if (count($Errors) == 0) {
        try {
            $item->saveThis();
            erLhcoreClassModule::redirect('messagebird/account');
            exit ;

        } catch (Exception $e) {
            $tpl->set('errors',array($e->getMessage()));
        }
    } else {
        $tpl->set('errors',$Errors);
    }
}

$tpl->set('item',$item);

$Result['content'] = $tpl->fetch();
$Result['path'] = array(
    array(
        'url' => erLhcoreClassDesign::baseurl('messagebird/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','MessageBird')),
    array(
        'url' =>erLhcoreClassDesign::baseurl('messagebird/account'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module', 'WhatsApp Accounts')
    ),
    array(
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module', 'Edit WhatsApp Accounts')
    )
);

?>