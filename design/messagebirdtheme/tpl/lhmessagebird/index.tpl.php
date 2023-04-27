<h4><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','MessageBird');?></h4>

<?php if (erLhcoreClassUser::instance()->hasAccessTo('lhmessagebird','configure')) : ?>
<ul>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('messagebird/settings')?>"><span class="material-icons">settings</span><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Settings');?></a></li>
</ul>
<?php endif;?>

<?php if (isset($update)) : ?>
    <?php $msg = erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Settings updated'); ?>
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/alert_success.tpl.php'));?>
<?php endif; ?>

<h4><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','WhatsApp');?></h4>
<ul>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('messagebird/templates')?>"><span class="material-icons">description</span><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','WhatsApp Templates');?></a></li>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('messagebird/send')?>"><span class="material-icons">send</span><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Send WhatsApp a single message');?></a></li>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('messagebird/massmessage')?>"><span class="material-icons">forum</span><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Send WhatsApp a mass message');?></a></li>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('messagebird/messages')?>"><span class="material-icons">chat</span><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','WhatsApp Messages');?></a></li>
</ul>

<div class="btn-group mb-2" role="group" aria-label="Basic example">
    <a class="btn btn-sm btn-secondary csfr-required" href="<?php echo erLhcoreClassDesign::baseurl('messagebird/index')?>/(action)/whatsapp" >Install/Update WhatsApp Handler</a>
    <a class="btn btn-sm btn-danger csfr-required " data-trans="delete_confirm" href="<?php echo erLhcoreClassDesign::baseurl('messagebird/index')?>/(action)/removewhatsapp" >Remove WhatsApp Handler</a>
</div>

<h4><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','SMS');?></h4>
<ul>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('messagebird/phonenumbers')?>"><span class="material-icons">phone</span><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','SMS Phone Numbers');?></a></li>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('messagebird/sendsms')?>"><span class="material-icons">sms</span><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Send SMS');?></a></li>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('messagebird/smsmessages')?>"><span class="material-icons">sms</span><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','SMS Messages');?></a></li>
</ul>

<div class="btn-group" role="group" aria-label="Basic example">
    <a class="btn btn-sm btn-secondary csfr-required" href="<?php echo erLhcoreClassDesign::baseurl('messagebird/index')?>/(action)/sms" >Install/Update SMS Handler</a>
    <a class="btn btn-sm btn-danger csfr-required " data-trans="delete_confirm" href="<?php echo erLhcoreClassDesign::baseurl('messagebird/index')?>/(action)/removesms" >Remove SMS Handler</a>
</div>

<?php include(erLhcoreClassDesign::designtpl('lhkernel/secure_links.tpl.php')); ?>