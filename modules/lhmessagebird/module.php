<?php

$Module = array( "name" => "MessageBird",
    'variable_params' => true );

$ViewList = array();

$ViewList['index'] = array(
    'params' => array(),
    'uparams' => array(),
    'functions' => array('use_admin'),
);

$ViewList['settings'] = array(
    'params' => array(),
    'uparams' => array(),
    'functions' => array('use_admin'),
);

$ViewList['messages'] = array(
    'params' => array(),
    'uparams' => array(),
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
    'functions' => array('use_admin'),
);

$ViewList['rendersend'] = array(
    'params' => array('template'),
    'uparams' => array(),
    'functions' => array('use_admin'),
);

$ViewList['templates'] = array(
    'params' => array(),
    'uparams' => array(),
    'functions' => array('use_admin'),
);

$FunctionList['use_admin'] = array('explain' => 'Allow operator to use MessageBird');