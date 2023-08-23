<?php
$tpl = erLhcoreClassTemplate::getInstance('lhmessagebird/newaccount.tpl.php');

$item = new \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdAccount();

$tpl->set('item',$item);

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

$Result['content'] = $tpl->fetch();
$Result['path'] = array(
    array(
        'url' => erLhcoreClassDesign::baseurl('messagebird/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('module/fbmessenger','MessageBird')),
    array(
        'url' =>erLhcoreClassDesign::baseurl('messagebird/account'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('module/fbmessenger', 'WhatsApp Accounts')
    ),
    array(
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('module/fbmessenger', 'New WhatsApp Accounts')
    )
);


?>