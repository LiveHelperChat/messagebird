<div class="form-group">
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Name');?>*</label>
    <input required="required" type="text" maxlength="250" class="form-control form-control-sm" name="name" value="<?php echo htmlspecialchars($item->name)?>" />
</div>

<div class="form-group">
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Channel ID');?>*</label>
    <input required="required" type="text" maxlength="250" class="form-control form-control-sm" name="channel_id" value="<?php echo htmlspecialchars($item->channel_id)?>" />
</div>

<div class="form-group">
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Template Namespace');?></label>
    <input type="text" maxlength="250" class="form-control form-control-sm" name="template_id_namespace" value="<?php echo htmlspecialchars($item->template_id_namespace)?>" />
    <p class="text-muted"><small>If the same Template Namespace is used as in default configuration you do not need to enter it.</small></p>
</div>

<div class="form-group">
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Access Key');?></label>
    <input type="text" maxlength="250" class="form-control form-control-sm" name="access_key" value="<?php echo htmlspecialchars($item->access_key)?>" />
    <p class="text-muted"><small>If the same Access Key is used as in default configuration you do not need to enter it.</small></p>
</div>

<div class="form-group">
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Department');?>*</label>
    <?php echo erLhcoreClassRenderHelper::renderCombobox(array(
        'input_name'     => 'dep_id',
        'optional_field' => erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Select department'),
        'selected_id'    => $item->dep_id,
        'css_class'      => 'form-control form-control-sm',
        'list_function'  => 'erLhcoreClassModelDepartament::getList',
        'list_function_params'  => array(),
    )); ?>
</div>

<div class="form-group">
    <label><input type="checkbox" name="active" value="on" <?php $item->active == 1 ? print ' checked="checked" ' : ''?> > <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Active');?></label>
</div>
