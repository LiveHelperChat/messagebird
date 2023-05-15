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
    'functions' => array('use_admin'),
);

$ViewList['deletemessage'] = array(
    'params' => array('id'),
    'functions' => array('use_admin'),
);

$ViewList['rawjson'] = array(
    'params' => array('id'),
    'uparams' => array(),
    'functions' => array('use_admin'),
);

$ViewList['send'] = array(
    'params' => array(),
    'uparams' => array(),
    'functions' => array('use_whatsapp'),
);

$ViewList['massmessage'] = array(
    'params' => array(),
    'uparams' => array(),
    'functions' => array('use_whatsapp'),
);

$ViewList['rendersend'] = array(
    'params' => array('template'),
    'uparams' => array(),
    'functions' => array('use_whatsapp'),
);

$ViewList['templates'] = array(
    'params' => array(),
    'uparams' => array(),
    'functions' => array('use_admin'),
);

/* SMS Actions */
$ViewList['sendsms'] = array(
    'params' => array(),
    'uparams' => array(),
    'functions' => array('use_admin'),
);

$ViewList['phonenumbers'] = array(
    'params' => array(),
    'uparams' => array(),
    'functions' => array('configure'),
);

$ViewList['newphonenumber'] = array(
    'params' => array(),
    'uparams' => array(),
    'functions' => array('configure'),
);

$ViewList['editphonenumber'] = array(
    'params' => array('id'),
    'uparams' => array(),
    'functions' => array('configure'),
);

$ViewList['deletephone'] = array(
    'params' => array('id'),
    'uparams' => array('csfr'),
    'functions' => array('configure'),
);

$ViewList['smsmessages'] = array(
    'params' => array(),
    'uparams' => array(),
    'functions' => array('use_admin'),
);

$ViewList['rawjsonsms'] = array(
    'params' => array('id'),
    'uparams' => array(),
    'functions' => array('use_admin'),
);

$ViewList['deletesmsmessage'] = array(
    'params' => array('id'),
    'functions' => array('use_admin'),
);

$FunctionList['use_admin'] = array('explain' => 'Allow operator to use MessageBird');
$FunctionList['use_whatsapp'] = array('explain' => 'Allow operator to use MessageBird WhatsApp Send Messages functions');
$FunctionList['configure'] = array('explain' => 'Allow operator to configure MessageBird');