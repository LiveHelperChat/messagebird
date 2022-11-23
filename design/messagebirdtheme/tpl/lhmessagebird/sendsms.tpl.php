<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Send a single SMS message');?></h1>

<?php if (isset($errors)) : ?>
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/validation_error.tpl.php'));?>
<?php endif; ?>

<?php if (isset($updated)) : $msg = erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Updated'); ?>
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/alert_success.tpl.php'));?>
<?php endif; ?>

<form action="" method="post" ng-non-bindable>

    <?php include(erLhcoreClassDesign::designtpl('lhkernel/csfr_token.tpl.php'));?>

    <div class="row">
        <div class="col-6">
            <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Originator');?></label>
            <input type="text" class="form-control form-control-sm" placeholder="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Customer Support');?>" name="originator" value="<?php echo htmlspecialchars($send->originator)?>">
        </div>
        <div class="col-6">
            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Phone number as originator');?></label>
                <?php echo erLhcoreClassRenderHelper::renderCombobox(array(
                    'input_name'     => 'sender_phone_id',
                    'display_name'     => 'phone_name',
                    'optional_field' => erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Select a phone number'),
                    'selected_id'    => $send->sender_phone_id,
                    'css_class'      => 'form-control form-control-sm',
                    'list_function'  => '\LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdPhoneNumber::getList',
                    'list_function_params'  => array('limit' => false, 'sort' => 'name ASC'),
                )); ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Recipient phone');?>*</label>
        <div class="input-group input-group-sm mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">+</span>
            </div>
            <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars((string)$send->phone)?>" aria-label="Username" aria-describedby="basic-addon1">
        </div>
     </div>

     <div class="form-group">
        <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Message');?>*</label>
         <textarea class="form-control form-control-sm" name="message"><?php echo $send->message;?></textarea>
     </div>

     <button class="btn btn-secondary btn-sm" type="submit" value=""><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Send a message');?></button>
</form>