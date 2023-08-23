<?php

$Module = array( "name" => "MessageBird",
    'variable_params' => true );

$ViewList = array();

$ViewList['index'] = array(
    'params' => array(),
    'uparams' => array('action','csfr'),
    'functions' => array('use_admin'),
);

$ViewList['indexwhatsapp'] = array(
    'params' => array(),
    'uparams' => array('action','csfr'),
    'functions' => array('use_whatsapp'),
);

$ViewList['settings'] = array(
    'params' => array(),
    'uparams' => array(),
    'functions' => array('configure'),
);

$ViewList['messages'] = array(
    'params' => array(),
    'uparams' => array(),
    'functions' => array('use_whatsapp_manage'),
);

$ViewList['webhooks'] = array(
    'params' => array(),
    'uparams' => array('business_account_id'),
    'functions' => array('use_whatsapp_manage'),
);

$ViewList['deletewebhook'] = array(
    'params' => array('id','business_account_id'),
    'uparams' => array('csfr'),
    'functions' => array('use_whatsapp_manage'),
);

$ViewList['account'] = array(
    'params' => array(),
    'uparams' => array(),
    'functions' => array('use_whatsapp_manage'),
);

$ViewList['newaccount'] = array(
    'params' => array(),
    'uparams' => array(),
    'functions' => array('use_whatsapp_manage'),
);

$ViewList['editaccount'] = array(
    'params' => array('id'),
    'uparams' => array(),
    'functions' => array('use_whatsapp_manage'),
);

$ViewList['deleteaccount'] = array(
    'params' => array('id'),
    'uparams' => array('csfr'),
    'functions' => array('use_whatsapp_manage'),
);

$ViewList['deletemessage'] = array(
    'params' => array('id'),
    'functions' => array('use_whatsapp_manage'),
);

$ViewList['rawjson'] = array(
    'params' => array('id'),
    'uparams' => array(),
    'functions' => array('use_whatsapp_manage'),
);

$ViewList['send'] = array(
    'params' => array(),
    'uparams' => array('business_account_id'),
    'functions' => array('use_whatsapp'),
);

$ViewList['massmessage'] = array(
    'params' => array(),
    'uparams' => array('business_account_id'),
    'functions' => array('use_whatsapp'),
);

$ViewList['rendersend'] = array(
    'params' => array('template','business_account_id'),
    'uparams' => array(),
    'functions' => array('use_whatsapp'),
);

$ViewList['rendertemplates'] = array(
    'params' => array('business_account_id'),
    'uparams' => array(),
    'functions' => array('use_whatsapp'),
);

$ViewList['templates'] = array(
    'params' => array(),
    'uparams' => array('id','action','checked'),
    'functions' => array('use_whatsapp_manage'),
);

/* SMS Actions */
$ViewList['sendsms'] = array(
    'params' => array(),
    'uparams' => array(),
    'functions' => array('use_sms'),
);

$ViewList['phonenumbers'] = array(
    'params' => array(),
    'uparams' => array(),
    'functions' => array('use_sms_manage'),
);

$ViewList['newphonenumber'] = array(
    'params' => array(),
    'uparams' => array(),
    'functions' => array('use_sms_manage'),
);

$ViewList['editphonenumber'] = array(
    'params' => array('id'),
    'uparams' => array(),
    'functions' => array('use_sms_manage'),
);

$ViewList['deletephone'] = array(
    'params' => array('id'),
    'uparams' => array('csfr'),
    'functions' => array('use_sms_manage'),
);

$ViewList['smsmessages'] = array(
    'params' => array(),
    'uparams' => array(),
    'functions' => array('use_sms_manage'),
);

$ViewList['rawjsonsms'] = array(
    'params' => array('id'),
    'uparams' => array(),
    'functions' => array('use_sms_manage'),
);

$ViewList['deletesmsmessage'] = array(
    'params' => array('id'),
    'functions' => array('use_sms_manage'),
);

$FunctionList['use_admin'] = array('explain' => 'Allow operator to use MessageBird');

$FunctionList['use_whatsapp'] = array('explain' => 'Allow operator to use MessageBird WhatsApp Send Messages functions');
$FunctionList['use_whatsapp_manage'] = array('explain' => 'Allow operator to use MessageBird WhatsApp Messages and WhatsApp templates list');

$FunctionList['use_sms'] = array('explain' => 'Allow operator to use MessageBird SMS Send function');
$FunctionList['use_sms_manage'] = array('explain' => 'Allow operator to use MessageBird SMS List and Phone number manage');

$FunctionList['configure'] = array('explain' => 'Allow operator to configure MessageBird');