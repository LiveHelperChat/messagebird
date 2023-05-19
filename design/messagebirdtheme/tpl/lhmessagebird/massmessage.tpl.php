<form action="" method="post" enctype="multipart/form-data">

    <?php include(erLhcoreClassDesign::designtpl('lhkernel/csfr_token.tpl.php'));?>

    <div class="row">
        <div class="col-8">
            <?php if (isset($errors)) : ?>
                <?php include(erLhcoreClassDesign::designtpl('lhkernel/validation_error.tpl.php'));?>
            <?php endif; ?>

            <?php if (isset($update)) : ?>
                <div role="alert" class="alert alert-success alert-dismissible fade show">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">

                    </button>
                    <ul>
                        <li><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Imported');?> - <?php echo $update['imported']?></li>
                    </ul>
                </div>
            <?php endif; ?>

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
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Template');?>*</label>
                <select name="template" class="form-control form-control-sm" id="template-to-send">
                    <option value=""><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Choose a template');?></option>
                    <?php foreach (LiveHelperChatExtension\messagebird\providers\MessageBirdLiveHelperChat::getInstance()->getTemplates()['items'] as $template) : if (\LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdTemplateDisabled::getCount(['filter' => ['id' => $template['id']]]) > 0) {continue;}?>
                        <option <?php if ($send->template == $template['name']) : ?>selected="selected"<?php endif;?> value="<?php echo htmlspecialchars($template['name'] . '||' . $template['language'])?>"><?php echo htmlspecialchars($template['name'] . ' [' . $template['language'] . ']')?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>CSV</label>
                <input type="file" name="files" />
            </div>

            <script>
                var messageFieldsValues = <?php echo json_encode($send->message_variables_array);?>;
            </script>

            <p><small><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','First row in CSV is skipped. Columns order');?> - </small>
                <span class="badge bg-secondary me-2">phone</span>
                <span class="badge bg-secondary me-2">field_1</span>
                <span class="badge bg-secondary me-2">field_2</span>
                <span class="badge bg-secondary me-2">field_3</span>
                <span class="badge bg-secondary me-2">field_4</span>
                <span class="badge bg-secondary me-2">field_5</span>
                <span class="badge bg-secondary">field_6</span>
            </p>

            <input type="submit" class="btn btn-sm btn-secondary" name="UploadFileAction" value="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Import and start sending');?>" />
        </div>
        <div class="col-4">
            <div id="arguments-template"></div>
        </div>
    </div>
</form>