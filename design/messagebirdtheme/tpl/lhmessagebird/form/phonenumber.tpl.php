<div class="form-group">
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/mattermost','Name');?></label>
    <input type="text" maxlength="20" class="form-control form-control-sm" placeholder="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/mattermost','VIP Support');?>" name="name" value="<?php echo htmlspecialchars($item->name)?>" />
</div>

<div class="form-group">
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Phone number');?>*</label>
    <div class="input-group input-group-sm mb-3">
       
            <span class="input-group-text" id="basic-addon1">+</span>

        <input type="text" maxlength="20" class="form-control form-control-sm" placeholder="E.g 37065277777" name="phone" value="<?php echo htmlspecialchars($item->phone)?>" />
    </div>
</div>

<div class="form-group">
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/mattermost','Department');?></label>
    <?php echo erLhcoreClassRenderHelper::renderMultiDropdown( array (
        'input_name'     => 'dep_id',
        'optional_field' => erTranslationClassLhTranslation::getInstance()->getTranslation('module/mailconvmb', 'Choose a department'),
        'selected_id'    => [$item->dep_id],
        'data_prop'      => 'data-limit="1"',
        'css_class'      => 'form-control',
        'type'           => 'radio',
        'display_name'   => 'name',
        'no_selector'    => true,
        'list_function_params' => array('limit' => false),
        'list_function'  => 'erLhcoreClassModelDepartament::getList',
    )); ?>
</div>

<script>
    $(function() {
        $('.btn-block-department').makeDropdown();
    });
</script>