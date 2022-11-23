<?php

$tpl = erLhcoreClassTemplate::getInstance('lhmessagebird/settings.tpl.php');

$mbOptions = erLhcoreClassModelChatConfig::fetch('mb_options');
$data = (array)$mbOptions->data;

if (isset($_POST['StoreOptions'])) {

    if (!isset($_POST['csfr_token']) || !$currentUser->validateCSFRToken($_POST['csfr_token'])) {
        erLhcoreClassModule::redirect('messagebird/settings');
        exit;
    }

    $definition = array(
        'access_key' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        ),
        'endpoint' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        ),
        'channel_id' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        ),
        'convendpoint' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        ),
        'namespace' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        )
    );

    $form = new ezcInputForm( INPUT_POST, $definition );
    $Errors = array();

    if ( $form->hasValidData( 'access_key' )) {
        $data['access_key'] = $form->access_key;
    } else {
        $data['access_key'] = '';
    }

    if ( $form->hasValidData( 'endpoint' )) {
        $data['endpoint'] = $form->endpoint;
    } else {
        $data['endpoint'] = '';
    }
    
    if ( $form->hasValidData( 'convendpoint' )) {
        $data['convendpoint'] = $form->convendpoint;
    } else {
        $data['convendpoint'] = '';
    }

    if ( $form->hasValidData( 'channel_id' )) {
        $data['channel_id'] = $form->channel_id;
    } else {
        $data['channel_id'] = '';
    }

    if ( $form->hasValidData( 'namespace' )) {
        $data['namespace'] = $form->namespace;
    } else {
        $data['namespace'] = '';
    }

    $mbOptions->explain = '';
    $mbOptions->type = 0;
    $mbOptions->hidden = 1;
    $mbOptions->identifier = 'mb_options';
    $mbOptions->value = serialize($data);
    $mbOptions->saveThis();

    // Update access key instantly
    $incomingWebhook = \erLhcoreClassModelChatIncomingWebhook::findOne(['filter' => ['name' => 'MessageBirdSMS']]);

    if (is_object($incomingWebhook)) {
        $conditionsArray = $incomingWebhook->conditions_array;
        if (isset($conditionsArray['attr']) && is_array($conditionsArray['attr'])) {
            foreach ($conditionsArray['attr'] as $attrIndex => $attrValue) {
                if ($attrValue['key'] == 'access_key') {
                    $attrValue['value'] = $data['access_key'];
                    $conditionsArray['attr'][$attrIndex] = $attrValue;
                }
            }
        }
        $incomingWebhook->conditions_array = $conditionsArray;
        $incomingWebhook->configuration = json_encode($conditionsArray);
        $incomingWebhook->updateThis(['update' => ['configuration']]);
    }

    $tpl->set('updated','done');
}

$tpl->set('mb_options',$data);

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
    array(
        'url' => erLhcoreClassDesign::baseurl('messagebird/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','MessageBird')
    ),
    array(
        'url' => erLhcoreClassDesign::baseurl('messagebird/settings'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Settings')
    )
);

?>