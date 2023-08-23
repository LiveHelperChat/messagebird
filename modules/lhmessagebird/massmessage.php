<?php

$tpl = erLhcoreClassTemplate::getInstance('lhmessagebird/massmessage.tpl.php');

$itemDefault = new \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdMessage();

$instance = \LiveHelperChatExtension\messagebird\providers\MessageBirdLiveHelperChat::getInstance();

if (isset($_POST['business_account_id']) && $_POST['business_account_id'] > 0) {
    $Params['user_parameters_unordered']['business_account_id'] = (int)$_POST['business_account_id'];
}

if (is_numeric($Params['user_parameters_unordered']['business_account_id']) && $Params['user_parameters_unordered']['business_account_id'] > 0) {

    $account = \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdAccount::fetch($Params['user_parameters_unordered']['business_account_id']);

    $instance->setAccessKey($account->access_key);
    $instance->setChannelId($account->channel_id);
    $instance->setNamespace($account->template_id_namespace);

    $itemDefault->business_account_id = $account->id;
}

$templates = $instance->getTemplates();

if (isset($_POST['UploadFileAction'])) {

    $errors = [];

    if (!isset($_POST['csfr_token']) || !$currentUser->validateCSFRToken($_POST['csfr_token'])) {
        erLhcoreClassModule::redirect('messagebird/massmessage');
        exit;
    }

    $definition = array(
        'dep_id' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'int', array('min_range' => 1)
        ),
        'business_account_id' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'int', array('min_range' => 0)
        ),
        'template' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        )
    );

    $form = new ezcInputForm( INPUT_POST, $definition );
    $Errors = array();

    if ($form->hasValidData( 'dep_id' )) {
        $itemDefault->dep_id = $form->dep_id;
    } else {
        $Errors[] = erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Please choose a department!');
    }

    if ($form->hasValidData( 'business_account_id' )) {
        $itemDefault->business_account_id = $form->business_account_id;
    } else {
        $Errors[] = erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Please choose a business account!');
    }

    if ($form->hasValidData( 'template' ) && $form->template != '') {
        $template = explode('||',$form->template);
        $itemDefault->template = $template[0];
        $itemDefault->language = $template[1];
    } else {
        $Errors[] = erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Please choose a template!');
    }

    if (empty($Errors) && erLhcoreClassSearchHandler::isFile('files',array('csv'))) {

        $dir = 'var/tmpfiles/';
        erLhcoreClassChatEventDispatcher::getInstance()->dispatch('theme.temppath', array('dir' => & $dir));

        erLhcoreClassFileUpload::mkdirRecursive( $dir );

        $filename = erLhcoreClassSearchHandler::moveUploadedFile('files', $dir);

        $header = NULL;
        $data = array();

        if (($handle = fopen($dir . $filename, 'r')) !== FALSE)
        {
            while (($row = fgetcsv($handle, 10000, ",")) !== FALSE)
            {
                if(!$header) {
                    $header = $row;
                } else {
                    if (count($header) != count($row)) {
                        $row = $row + array_fill(count($row),count($header) - count($row),'');
                    }
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }

        unlink($dir . $filename);

        $canned = ['phone', 'field_1', 'field_2', 'field_3', 'field_4', 'field_5', 'field_6'];

        $stats = array(
            'imported' => 0,
        );

        if ($canned === $header) {

            foreach ($data as $item) {
                $messagePrepared = new \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdMessage();
                $messagePrepared->user_id = $currentUser->getUserID();
                $messagePrepared->phone = trim(str_replace('+','',$item['phone']));
                unset($item['phone']);
                $messagePrepared->message_variables = json_encode($item);
                $messagePrepared->template = $itemDefault->template;
                $messagePrepared->language = $itemDefault->language;
                $messagePrepared->dep_id = $itemDefault->dep_id;
                $messagePrepared->business_account_id = $itemDefault->business_account_id;
                $messagePrepared->status = \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdMessage::STATUS_PENDING_PROCESS;
                $messagePrepared->saveThis();
                $stats['imported']++;
            }

            $tpl->set('update', $stats);

        } else {
            $tpl->set('errors', [erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Expected columns does not match!')]);
        }

    } elseif (!empty($Errors)) {
        $tpl->set('errors', $Errors);
    } else {
        $tpl->set('errors', [erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Invalid file format')]);
    }
}

$tpl->setArray([
    'send' => $itemDefault,
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
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Mass message')
    )
);

?>