<?php

$tpl = erLhcoreClassTemplate::getInstance('lhmessagebird/send.tpl.php');

$item = new LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdMessage();

$templates = LiveHelperChatExtension\messagebird\providers\MessageBirdLiveHelperChat::getInstance()->getTemplates();

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
        $item->phone = $form->phone;
    } else {
        $Errors[] = erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Please enter a phone');
    }

    if ($form->hasValidData( 'dep_id' )) {
        $item->dep_id = $form->dep_id;
    } else {
        $Errors[] = erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Please choose a department!');
    }

    for ($i = 0; $i < 6; $i++) {
        if ($form->hasValidData('field_' . $i) && $form->{'field_' . $i}) {
            $item->{'field_' . $i} = $form->{'field_' . $i};
        }
    }

    if ($form->hasValidData( 'template' ) && $form->template != '') {
        $template = explode('||',$form->template);
        $item->template = $template[0];
        $item->language = $template[1];
    } else {
        $Errors[] = erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Please choose a template!');
    }

    if (count($Errors) == 0) {
        try {

            LiveHelperChatExtension\messagebird\providers\MessageBirdLiveHelperChat::getInstance()->sendTemplate($item, $templates);
            
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