<?php if (erLhcoreClassUser::instance()->hasAccessTo('lhmessagebird','use_admin')) : ?>
<li class="nav-item"><a class="nav-link" href="<?php echo erLhcoreClassDesign::baseurl('messagebird/index')?>"><i class="material-icons">send</i><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','MessageBird');?></a></li>
<?php endif; ?>