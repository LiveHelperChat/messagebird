<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Send a single message');?></h1>

<?php if (isset($errors)) : ?>
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/validation_error.tpl.php'));?>
<?php endif; ?>

<?php if (isset($updated)) : $msg = erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Sent!'); ?>
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/alert_success.tpl.php'));?>
<?php endif; ?>

<form action="" method="post" ng-non-bindable>

    <?php include(erLhcoreClassDesign::designtpl('lhkernel/csfr_token.tpl.php'));?>

    <div class="row">
        <div class="col-8">
            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Phone');?>*</label>
                <div class="input-group input-group-sm mb-3">

                        <span class="input-group-text" id="basic-addon1">+</span>
                    
                    <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars((string)$send->phone)?>" aria-label="Username" aria-describedby="basic-addon1">
                </div>
            </div>

            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Department');?>*</label>
                <?php echo erLhcoreClassRenderHelper::renderCombobox(array(
                    'input_name'     => 'dep_id',
                    'optional_field' => erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Select department'),
                    'selected_id'    => $send->dep_id,
                    'css_class'      => 'form-control form-control-sm',
                    'list_function'  => 'erLhcoreClassModelDepartament::getList',
                    'list_function_params'  => array('limit' => false, 'sort' => 'name ASC'),
                )); ?>
            </div>

            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Business account');?>, <small><i><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','you can set a custom business account');?></i></small></label>
                <?php echo erLhcoreClassRenderHelper::renderCombobox( array (
                    'input_name'     => 'business_account_id',
                    'optional_field' => erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Default configuration'),
                    'selected_id'    => $send->business_account_id,
                    'css_class'      => 'form-control form-control-sm',
                    'list_function'  => '\LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdAccount::getList'
                )); ?>
            </div>

            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Template');?>*</label>
                <select name="template" class="form-control form-control-sm" id="template-to-send">
                    <option value=""><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Choose a template');?></option>
                    <?php foreach ($templates['items'] as $template) : if (\LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdTemplateDisabled::getCount(['filter' => ['id' => $template['id']]]) > 0) {continue;}?>
                        <option <?php if ($send->template == $template['name']) : ?>selected="selected"<?php endif;?> value="<?php echo htmlspecialchars($template['name'] . '||' . $template['language'])?>"><?php echo htmlspecialchars($template['name'] . ' [' . $template['language'] . ']')?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <script>
                var messageFieldsValues = <?php echo json_encode($send->message_variables_array);?>;
                var businessAccountId = <?php echo (int)$send->business_account_id?>;
            </script>

            <div id="arguments-template-form"></div>
        </div>
        <div class="col-4">
            <div id="arguments-template"></div>
        </div>
    </div>

    <button class="btn btn-secondary btn-sm" type="submit" value=""><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Send a test message');?></button>
</form>