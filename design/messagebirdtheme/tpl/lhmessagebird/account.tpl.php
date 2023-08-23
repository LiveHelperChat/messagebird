<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Business Accounts');?></h1>

<?php if (isset($items)) : ?>
    <table cellpadding="0" cellspacing="0" class="table table-sm" width="100%" ng-non-bindable>
        <thead>
        <tr>
            <th width="1%"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','ID');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Name');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Department');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Channel ID');?></th>
            <th width="1%"></th>
        </tr>
        </thead>
        <?php foreach ($items as $item) : ?>
            <tr>
                <td>
                    <?php echo $item->id?>
                </td>
                <td>
                    <a title="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Send a single message');?>" href="<?php echo erLhcoreClassDesign::baseurl('messagebird/send')?>/(business_account_id)/<?php echo htmlspecialchars($item->id) ?>"><span class="material-icons">send</span></a>
                    <a title="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Send a mass message');?>" href="<?php echo erLhcoreClassDesign::baseurl('messagebird/massmessage')?>/(business_account_id)/<?php echo htmlspecialchars($item->id) ?>"><span class="material-icons">forum</span></a>
                    <a href="<?php echo erLhcoreClassDesign::baseurl('messagebird/editaccount')?>/<?php echo htmlspecialchars($item->id) ?>"><span class="material-icons">edit</span><?php echo htmlspecialchars((string)$item->name)?></a>
                </td>
                <td>
                    <?php echo htmlspecialchars((string)$item->department)?>
                </td>
                <td>
                    <?php echo $item->channel_id?>
                </td>
                <td>
                    <a class="csfr-required csfr-post material-icons text-danger" data-trans="delete_confirm" title="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Delete account');?>" href="<?php echo erLhcoreClassDesign::baseurl('messagebird/deleteaccount')?>/<?php echo htmlspecialchars($item->id) ?>">delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php include(erLhcoreClassDesign::designtpl('lhkernel/secure_links.tpl.php')); ?>

    <?php if (isset($pages)) : ?>
        <?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>
    <?php endif;?>
<?php endif; ?>

<a href="<?php echo erLhcoreClassDesign::baseurl('messagebird/newaccount')?>" class="btn btn-sm btn-secondary"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','New');?></a>
