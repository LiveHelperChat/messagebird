<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Templates'); ?></h1>

<?php $idsTemplate = []; foreach ($templates['items'] as $template) {
    $idsTemplate[] = $template['id'];
}

$records = !empty($idsTemplate) ? array_keys(\LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdTemplateDisabled::getList(['filterin' => ['id' => $idsTemplate]])) : [] ?>

<table class="table table-sm" ng-non-bindable>
    <thead>
    <tr>
        <th width="1%">
            <span title="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Disabled templates are checked'); ?>" class="material-icons">visibility_off</span>
        </th>
        <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Name'); ?></th>
        <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Language'); ?></th>
        <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Status'); ?></th>
        <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Category'); ?></th>
        <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Components'); ?></th>
    </tr>
    </thead>
    <?php foreach ($templates['items'] as $template) : ?>
        <tr>
            <td>
                <input <?php if (in_array($template['id'],$records)) : ?>checked<?php endif;?> type="checkbox" class="disable-template-action" value="<?php echo htmlspecialchars($template['id'])?>">
            </td>
            <td>
                <?php echo htmlspecialchars($template['name'])?><br>
                <span class="hide text-muted" id="save-status-<?php echo htmlspecialchars($template['id'])?>"><span class="material-icons " >done</span> <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Updated'); ?></span>
            </td>
            <td>
                <?php echo htmlspecialchars($template['language'])?>
            </td>
            <td>
                <?php echo htmlspecialchars($template['status'])?>
            </td>
            <td>
                <?php echo htmlspecialchars($template['category'])?>
            </td>
            <td>
                <textarea class="form-control form-control-sm fs12"><?php echo htmlspecialchars(json_encode($template['components'],JSON_PRETTY_PRINT)); ?></textarea>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<script>
    $('.disable-template-action').change(function(){
        $('#save-status-'+$(this).val()).removeClass('hide');
        $.post(WWW_DIR_JAVASCRIPT + 'messagebird/templates/(id)/'+$(this).val()+'/(action)/disable/(checked)/'+$(this).is(':checked'))
    })
</script>