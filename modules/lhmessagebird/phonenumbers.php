<?php

$tpl = erLhcoreClassTemplate::getInstance('lhmessagebird/phonenumbers.tpl.php');

if (isset($_GET['doSearch'])) {
    $filterParams = erLhcoreClassSearchHandler::getParams(array('customfilterfile' => 'extension/messagebird/classes/filter/phonenumbers.php','format_filter' => true, 'use_override' => true, 'uparams' => $Params['user_parameters_unordered']));
    $filterParams['is_search'] = true;
} else {
    $filterParams = erLhcoreClassSearchHandler::getParams(array('customfilterfile' => 'extension/messagebird/classes/filter/phonenumbers.php','format_filter' => true, 'uparams' => $Params['user_parameters_unordered']));
    $filterParams['is_search'] = false;
}

erLhcoreClassChatStatistic::formatUserFilter($filterParams, 'lhc_messagebird_phone');

if (isset($filterParams['filter']['filterin']['lh_chat.dep_id'])) {
    $filterParams['filter']['filterin']['dep_id'] = $filterParams['filter']['filterin']['lh_chat.dep_id'];
    unset($filterParams['filter']['filterin']['lh_chat.dep_id']);
}

$append = erLhcoreClassSearchHandler::getURLAppendFromInput($filterParams['input_form']);

$pages = new lhPaginator();
$pages->items_total = \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdPhoneNumber::getCount($filterParams['filter']);
$pages->translationContext = 'chat/activechats';
$pages->serverURL = erLhcoreClassDesign::baseurl('messagebird/phonenumbers').$append;
$pages->paginate();
$tpl->set('pages',$pages);

if ($pages->items_total > 0) {
    $items = \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdPhoneNumber::getList(array_merge(array('limit' => $pages->items_per_page, 'offset' => $pages->low),$filterParams['filter']));
    $tpl->set('items',$items);
}

$filterParams['input_form']->form_action = erLhcoreClassDesign::baseurl('messagebird/phonenumbers');

$tpl->set('input',$filterParams['input_form']);
$tpl->set('inputAppend',$append);

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
    array(
        'url' => erLhcoreClassDesign::baseurl('messagebird/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','MessageBird')
    ),
    array(
        'url' => erLhcoreClassDesign::baseurl('messagebird/messages'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Phone Numbers')
    )
);

?>