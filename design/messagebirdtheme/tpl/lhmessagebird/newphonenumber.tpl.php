<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','New');?></h1>

<?php if (isset($errors)) : ?>
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/validation_error.tpl.php'));?>
<?php endif; ?>

<form action="<?php echo erLhcoreClassDesign::baseurl('messagebird/newphonenumber')?>" method="post" ng-non-bindable autocomplete="off">

    <?php include(erLhcoreClassDesign::designtpl('lhmessagebird/form/phonenumber.tpl.php'));?>

    <div class="btn-group" role="group" aria-label="...">
        <input type="submit" class="btn btn-secondary" name="Update_action" value="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('system/buttons','Save and continue');?>"/>
        <input type="submit" class="btn btn-secondary" name="Save_page" value="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('system/buttons','Save');?>"/>
        <input type="submit" class="btn btn-secondary" name="Cancel_page" value="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('system/buttons','Cancel');?>"/>
    </div>

</form>