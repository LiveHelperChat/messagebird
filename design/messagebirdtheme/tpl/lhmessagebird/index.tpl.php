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

<?php if ( erLhcoreClassUser::instance()->hasAccessTo('lhmessagebird','use_whatsapp_manage') || erLhcoreClassUser::instance()->hasAccessTo('lhmessagebird','use_whatsapp') ) : ?>
<h4><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','WhatsApp');?></h4>
<ul>
    <?php if (erLhcoreClassUser::instance()->hasAccessTo('lhmessagebird','use_whatsapp_manage')) : ?>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('messagebird/templates')?>"><span class="material-icons">description</span><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','WhatsApp Templates');?></a></li>
    <?php endif; ?>

    <?php if (erLhcoreClassUser::instance()->hasAccessTo('lhmessagebird','use_whatsapp')) : ?>
        <li><a href="<?php echo erLhcoreClassDesign::baseurl('messagebird/send')?>"><span class="material-icons">send</span><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Send WhatsApp a single message');?></a></li>
        <li><a href="<?php echo erLhcoreClassDesign::baseurl('messagebird/massmessage')?>"><span class="material-icons">forum</span><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Send WhatsApp a mass message');?></a></li>
    <?php endif; ?>

    <?php if (erLhcoreClassUser::instance()->hasAccessTo('lhmessagebird','use_whatsapp_manage')) : ?>
        <li><a href="<?php echo erLhcoreClassDesign::baseurl('messagebird/messages')?>"><span class="material-icons">chat</span><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','WhatsApp Messages');?></a></li>
        <li><a href="<?php echo erLhcoreClassDesign::baseurl('messagebird/account')?>"><span class="material-icons">call</span><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','WhatsApp Numbers');?></a></li>
        <li><a href="<?php echo erLhcoreClassDesign::baseurl('messagebird/webhooks')?>"><span class="material-icons">webhook</span><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','WhatsApp Webhooks');?></a></li>
    <?php endif; ?>
</ul>
<?php endif; ?>

<?php if (erLhcoreClassUser::instance()->hasAccessTo('lhmessagebird','use_admin')) : ?>
<div class="btn-group mb-2" role="group" aria-label="Basic example">
    <a class="btn btn-sm btn-secondary csfr-required" href="<?php echo erLhcoreClassDesign::baseurl('messagebird/index')?>/(action)/whatsapp" >Install/Update WhatsApp Handler</a>
    <a class="btn btn-sm btn-danger csfr-required " data-trans="delete_confirm" href="<?php echo erLhcoreClassDesign::baseurl('messagebird/index')?>/(action)/removewhatsapp" >Remove WhatsApp Handler</a>
</div>
<?php endif; ?>


<?php if ( erLhcoreClassUser::instance()->hasAccessTo('lhmessagebird','use_sms') || erLhcoreClassUser::instance()->hasAccessTo('lhmessagebird','use_sms_manage') ) : ?>
<h4><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','SMS');?></h4>
<ul>

    <?php if ( erLhcoreClassUser::instance()->hasAccessTo('lhmessagebird','use_sms_manage') ) : ?>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('messagebird/phonenumbers')?>"><span class="material-icons">phone</span><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','SMS Phone Numbers');?></a></li>
    <?php endif; ?>

    <?php if ( erLhcoreClassUser::instance()->hasAccessTo('lhmessagebird','use_sms') ) : ?>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('messagebird/sendsms')?>"><span class="material-icons">sms</span><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Send SMS');?></a></li>
    <?php endif; ?>

    <?php if ( erLhcoreClassUser::instance()->hasAccessTo('lhmessagebird','use_sms_manage') ) : ?>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('messagebird/smsmessages')?>"><span class="material-icons">sms</span><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','SMS Messages');?></a></li>
    <?php endif; ?>

</ul>
<?php endif; ?>

<?php if (erLhcoreClassUser::instance()->hasAccessTo('lhmessagebird','use_admin')) : ?>
<div class="btn-group" role="group" aria-label="Basic example">
    <a class="btn btn-sm btn-secondary csfr-required" href="<?php echo erLhcoreClassDesign::baseurl('messagebird/index')?>/(action)/sms" >Install/Update SMS Handler</a>
    <a class="btn btn-sm btn-danger csfr-required " data-trans="delete_confirm" href="<?php echo erLhcoreClassDesign::baseurl('messagebird/index')?>/(action)/removesms" >Remove SMS Handler</a>
</div>
<?php endif; ?>

<?php include(erLhcoreClassDesign::designtpl('lhkernel/secure_links.tpl.php')); ?>