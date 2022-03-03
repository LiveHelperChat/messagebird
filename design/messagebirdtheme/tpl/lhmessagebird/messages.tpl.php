<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Messages history');?></h1>

<?php include(erLhcoreClassDesign::designtpl('lhmessagebird/parts/form_filter.tpl.php'));?>

<?php if (isset($items)) : ?>
    <table cellpadding="0" cellspacing="0" class="table table-sm" width="100%" ng-non-bindable>
        <thead>
        <tr>
            <th width="1%"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','ID');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Department');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','User');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Phone');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Send status');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Chat ID');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Initiator');?></th>
        </tr>
        </thead>
        <?php foreach ($items as $item) : ?>
            <tr>
                <td nowrap="" title="<?php echo date(erLhcoreClassModule::$dateDateHourFormat,$item->created_at);?> | <?php echo htmlspecialchars($item->mb_id_message) ?> | <?php echo htmlspecialchars($item->conversation_id) ?>">
                    <?php echo htmlspecialchars($item->id) ?> <a class="material-icons" onclick="lhc.revealModal({'url':WWW_DIR_JAVASCRIPT+'messagebird/rawjson/<?php echo $item->id?>'})">info_outline</a>
                </td>
                <td>
                    <?php echo htmlspecialchars((string)$item->department)?>
                </td>
                <td>
                    <?php echo htmlspecialchars((string)$item->user)?>
                </td>
                <td>
                    <?php echo htmlspecialchars((string)$item->phone)?>
                </td>
                <td>
                    <?php if ($item->status == \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdMessage::STATUS_PENDING) : ?>
                        <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Pending');?>
                    <?php elseif ($item->status == \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdMessage::STATUS_READ) : ?>
                        <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Read');?>
                    <?php elseif ($item->status == \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdMessage::STATUS_DELIVERED) : ?>
                        <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Delivered');?>
                    <?php elseif ($item->status == \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdMessage::STATUS_IN_PROCESS) : ?>
                        <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','In process');?>
                    <?php elseif ($item->status == \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdMessage::STATUS_PENDING_PROCESS) : ?>
                        <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Pending to be processed');?>
                    <?php elseif ($item->status == \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdMessage::STATUS_SENT) : ?>
                        <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Sent');?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($item->conversation_id != '') : ?>
                        <span class="material-icons" title=" <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Covnersation');?> - <?php echo $item->conversation_id?>">question_answer</span>
                    <?php endif; ?>
                    <?php echo htmlspecialchars($item->chat_id)?>
                </td>
                <td>
                    <?php if ($item->initiation == \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdMessage::INIT_US) : ?>
                    We
                    <?php else : ?>
                    Third party
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php include(erLhcoreClassDesign::designtpl('lhkernel/secure_links.tpl.php')); ?>

    <?php if (isset($pages)) : ?>
        <?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>
    <?php endif;?>
<?php endif; ?>