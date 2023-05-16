<?php if (erLhcoreClassUser::instance()->hasAccessTo('lhmessagebird','use_admin')) : ?>
    <li class="nav-item"><a class="nav-link" href="<?php echo erLhcoreClassDesign::baseurl('messagebird/index')?>"><i class="material-icons">send</i><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','MessageBird');?></a></li>
<?php endif; ?>

<?php if (!erLhcoreClassUser::instance()->hasAccessTo('lhmessagebird','use_admin') && erLhcoreClassUser::instance()->hasAccessTo('lhmessagebird','use_whatsapp')) : ?>
    <li class="nav-item"><a class="nav-link" href="<?php echo erLhcoreClassDesign::baseurl('messagebird/indexwhatsapp')?>"><i class="material-icons">send</i><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','WhatsApp');?></a></li>
<?php endif; ?>
