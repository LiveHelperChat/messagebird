<?php

$tpl = erLhcoreClassTemplate::getInstance('lhmessagebird/editphonenumber.tpl.php');

$item = \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdPhoneNumber::fetch($Params['user_parameters']['id']);

if (ezcInputForm::hasPostData()) {

    $Errors = \LiveHelperChatExtension\messagebird\providers\MessageBirdLiveHelperChatValidator::validatePhone($item);

    if (count($Errors) == 0) {
        try {
            $item->saveThis();

            if (!isset($_POST['Update_action'])) {
                erLhcoreClassModule::redirect('messagebird/phonenumbers');
                exit ;
            } else {
                $tpl->set('updated',true);
            }

        } catch (Exception $e) {
            $tpl->set('errors',array($e->getMessage()));
        }

    } else {
        $tpl->set('errors',$Errors);
    }
}

$tpl->set('item', $item);

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
    array(
        'url' => erLhcoreClassDesign::baseurl('messagebird/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('module/mattermost', 'MessageBird')
    ),
    array(
        'url' => erLhcoreClassDesign::baseurl('messagebird/phonenumbers'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('module/mattermost', 'Phone Numbers')
    ),
    array(
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('module/mattermost', 'Edit')
    )
);



?>