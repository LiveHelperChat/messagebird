<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Phone numbers');?></h1>

<?php include(erLhcoreClassDesign::designtpl('lhmessagebird/parts/form_filter_phonenumbers.tpl.php'));?>

<?php if (isset($items)) : ?>
    <table cellpadding="0" cellspacing="0" class="table table-sm" width="100%" ng-non-bindable>
        <thead>
        <tr>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Phone Number');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Department');?></th>
            <th width="1%"></th>
            <th width="1%"></th>
        </tr>
        </thead>
        <?php foreach ($items as $item) : ?>
            <tr>
                <td nowrap="" title="<?php echo date(erLhcoreClassModule::$dateDateHourFormat,$item->created_at);?>">
                    <?php echo htmlspecialchars($item) ?>
                </td>
                <td>
                    <?php echo htmlspecialchars((string)$item->department)?>
                </td>
                <td>
                    <a class="material-icons" title="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Edit');?>" href="<?php echo erLhcoreClassDesign::baseurl('messagebird/editphonenumber')?>/<?php echo htmlspecialchars($item->id) ?>">edit</a>
                </td>
                <td>
                    <a class="csfr-required csfr-post material-icons text-danger" data-trans="delete_confirm" title="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Delete');?>" href="<?php echo erLhcoreClassDesign::baseurl('messagebird/deletephone')?>/<?php echo htmlspecialchars($item->id) ?>">delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php include(erLhcoreClassDesign::designtpl('lhkernel/secure_links.tpl.php')); ?>

    <?php if (isset($pages)) : ?>
        <?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>
    <?php endif;?>
<?php endif; ?>

<a href="<?php echo erLhcoreClassDesign::baseurl('messagebird/newphonenumber')?>" class="btn btn-sm btn-secondary"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/fbmessenger','New');?></a>
