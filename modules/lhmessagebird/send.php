<?php

$tpl = erLhcoreClassTemplate::getInstance('lhmessagebird/send.tpl.php');

$item = new LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdMessage();

$instance = \LiveHelperChatExtension\messagebird\providers\MessageBirdLiveHelperChat::getInstance();

if (isset($_POST['business_account_id']) && $_POST['business_account_id'] > 0) {
    $Params['user_parameters_unordered']['business_account_id'] = (int)$_POST['business_account_id'];
}

if (is_numeric($Params['user_parameters_unordered']['business_account_id']) && $Params['user_parameters_unordered']['business_account_id'] > 0) {

    $account = \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdAccount::fetch($Params['user_parameters_unordered']['business_account_id']);

    $instance->setAccessKey($account->access_key);
    $instance->setChannelId($account->channel_id);
    $instance->setNamespace($account->template_id_namespace);

    $item->business_account_id = $account->id;
}

$templates = $instance->getTemplates();

if (ezcInputForm::hasPostData()) {

    if (!isset($_POST['csfr_token']) || !$currentUser->validateCSFRToken($_POST['csfr_token'])) {
        erLhcoreClassModule::redirect('messagebird/send');
        exit;
    }

    $definition = array(
        'phone' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        ),
        'template' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        ),
        'dep_id' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'int', array('min_range' => 1)
        ),
        'business_account_id' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'int', array('min_range' => 0)
        ),
        'field_1' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        ),
        'field_2' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        ),
        'field_3' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        ),
        'field_4' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        ),
        'field_5' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        ),
        'field_6' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        ),
    );

    $form = new ezcInputForm( INPUT_POST, $definition );
    $Errors = array();

    if ($form->hasValidData( 'phone' ) && $form->phone != '') {
        $item->phone = trim((int)str_replace('+','',$form->phone));
    } else {
        $Errors[] = erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Please enter a phone');
    }

    if ($form->hasValidData( 'dep_id' )) {
        $item->dep_id = $form->dep_id;
    } else {
        $Errors[] = erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Please choose a department!');
    }

    if ($form->hasValidData( 'business_account_id' )) {

        $item->business_account_id = $form->business_account_id;

        if ($item->business_account_id > 0 && is_object($item->business_account)) {

            // Override variables by account
            $instance->setAccessKey($item->business_account->access_key);
            $instance->setChannelId($item->business_account->channel_id);
            $instance->setNamespace($item->business_account->template_id_namespace);

            // Refresh templates
            $templates = $instance->getTemplates();
        }

    } else {
        $Errors[] = erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Please choose a business account!');
    }

    $messageVariables = $item->message_variables_array;
    
    for ($i = 0; $i < 6; $i++) {
        if ($form->hasValidData('field_' . $i) && $form->{'field_' . $i}) {
            $messageVariables['field_' . $i] = $form->{'field_' . $i};
        }
    }

    $item->message_variables_array = $messageVariables;
    $item->message_variables = json_encode($messageVariables);

    if ($form->hasValidData( 'template' ) && $form->template != '') {
        $template = explode('||',$form->template);
        $item->template = $template[0];
        $item->language = $template[1];
    } else {
        $Errors[] = erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Please choose a template!');
    }

    if (count($Errors) == 0) {
        try {

            $instance->sendTemplate($item, $templates, false);
            
            $item->user_id = $currentUser->getUserID();
            $item->saveThis();

            if ($item->status == \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdMessage::STATUS_FAILED) {
                $tpl->set('errors', array($item->send_status_raw));
            } else {
                $tpl->set('updated', true);
            }

        } catch (Exception $e) {
            $tpl->set('errors',array($e->getMessage()));
        }
    } else {
        $tpl->set('errors',$Errors);
    }

}


$tpl->setArray([
        'send' => $item,
        'templates' => $templates
]);

$Result['content'] = $tpl->fetch();

$Result['additional_footer_js'] = '<script type="text/javascript" src="'.erLhcoreClassDesign::designJS('js/extension.messagebird.js').'"></script>';

$Result['path'] = array(
    array(
        'url' => erLhcoreClassDesign::baseurl('messagebird/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','MessageBird')
    ),
    array(
        'url' => erLhcoreClassDesign::baseurl('messagebird/settings'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Send')
    )
);

?>