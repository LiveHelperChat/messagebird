<?php

$tpl = erLhcoreClassTemplate::getInstance('lhmessagebird/sendsms.tpl.php');

$item = new LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdSMSMessage();

if (ezcInputForm::hasPostData()) {

    if (!isset($_POST['csfr_token']) || !$currentUser->validateCSFRToken($_POST['csfr_token'])) {
        erLhcoreClassModule::redirect('messagebird/sendsms');
        exit;
    }

    $definition = array(
        'phone' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        ),
        'message' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        ),
        'sender_phone_id' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'int', array('min_range' => 1)
        ),
        'originator' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        ),
    );

    $form = new ezcInputForm( INPUT_POST, $definition );
    $Errors = array();

    if ($form->hasValidData( 'phone' ) && $form->phone != '') {
        $item->phone = $form->phone;
    } else {
        $Errors[] = erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Please enter a phone');
    }

    if ($form->hasValidData( 'message' )) {
        $item->message = $form->message;
    } else {
        $Errors[] = erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Please choose a department!');
    }

    if ($form->hasValidData( 'originator' )) {
        $item->originator = $form->originator;
    }

    if ($form->hasValidData( 'sender_phone_id' )) {
        $item->sender_phone_id = $form->sender_phone_id;
    }

    if (empty($item->originator) || $item->sender_phone_id == 0) {
        erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Please enter an originator or choose a phone number!');
    }

    if (count($Errors) == 0) {
        try {

            LiveHelperChatExtension\messagebird\providers\MessageBirdLiveHelperChat::getInstance()->sendSMS($item);

            $item->user_id = $currentUser->getUserID();
            $item->saveThis();

            $tpl->set('updated',true);
        } catch (Exception $e) {
            $tpl->set('errors',array($e->getMessage()));
        }
    } else {
        $tpl->set('errors',$Errors);
    }
}

$tpl->setArray([
    'send' => $item,
]);

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
    array(
        'url' => erLhcoreClassDesign::baseurl('messagebird/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','MessageBird')
    ),
    array(
        'url' => erLhcoreClassDesign::baseurl('messagebird/sendsms'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Send SMS')
    )
);

?>