<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','SMS Messages history');?></h1>

<?php include(erLhcoreClassDesign::designtpl('lhmessagebird/parts/form_sms_filter.tpl.php'));?>

<?php if (isset($items)) : ?>
    <table cellpadding="0" cellspacing="0" class="table table-sm" width="100%" ng-non-bindable>
        <thead>
        <tr>
            <th width="1%"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','ID');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Department');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','User');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Phone');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Originator');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Send status');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Chat ID');?></th>
            <th width="1%"></th>
        </tr>
        </thead>
        <?php foreach ($items as $item) : ?>
            <tr>
                <td nowrap="" title="<?php echo date(erLhcoreClassModule::$dateDateHourFormat,$item->created_at);?> | <?php echo htmlspecialchars($item->mb_id_message) ?>">
                    <?php echo htmlspecialchars($item->id) ?> <a class="material-icons" onclick="lhc.revealModal({'url':WWW_DIR_JAVASCRIPT+'messagebird/rawjsonsms/<?php echo $item->id?>'})">info_outline</a>
                </td>
                <td>
                    <?php echo htmlspecialchars((string)$item->department)?>
                </td>
                <td>
                    <?php if ($item->user_id > 0) : ?>
                        <?php echo htmlspecialchars((string)$item->user)?>
                    <?php elseif ($item->user_id == -1) : ?>
                        <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','System');?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php echo htmlspecialchars((string)$item->phone)?>
                </td>
                <td>
                    <?php if ($item->originator != '') : ?>
                        <?php echo htmlspecialchars((string)$item->originator)?>
                    <?php else : ?>
                        <?php echo htmlspecialchars((string)$item->sender_phone)?>
                    <?php endif; ?>
                </td>
                <td>
                    <span class="badge bg-secondary me-1"><?php if ($item->status == \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdSMSMessage::STATUS_PENDING) : ?>
                        <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Pending');?>
                    <?php elseif ($item->status == \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdSMSMessage::STATUS_SENT) : ?>
                        <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Sent');?>
                    <?php elseif ($item->status == \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdSMSMessage::STATUS_DELIVERED) : ?>
                        <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Delivered');?>
                    <?php elseif ($item->status == \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdSMSMessage::STATUS_EXPIRED) : ?>
                        <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Expired');?>
                    <?php elseif ($item->status == \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdSMSMessage::STATUS_BUFFERED) : ?>
                        <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Buffered');?>
                    <?php elseif ($item->status == \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdSMSMessage::STATUS_IN_PROCESS) : ?>
                        <?php if ($item->mb_id_message == '') : ?>
                            <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','In process');?>
                        <?php else : ?>
                            <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Processed. Pending callback.');?>
                        <?php endif; ?>
                    <?php elseif ($item->status == \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdSMSMessage::STATUS_FAILED) : ?>
                        <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Failed');?>
                    <?php elseif ($item->status == \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdSMSMessage::STATUS_SCHEDULED) : ?>
                        <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Scheduled');?>
                    <?php elseif ($item->status == \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdSMSMessage::STATUS_PENDING_PROCESS) : ?>
                        <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Pending to be processed');?>
                    <?php endif; ?>
                    </span>

                    &nbsp;<?php echo htmlspecialchars($item->status_txt)?>
                </td>
                <td>
                    <?php if ($item->conversation_id != '') : ?>
                        <span class="material-icons" title=" <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Covnersation');?> - <?php echo $item->conversation_id?>">question_answer</span>
                    <?php endif; ?>
                    <?php echo htmlspecialchars($item->chat_id)?>
                </td>
                <td>
                    <a class="csfr-required csfr-post material-icons text-danger" data-trans="delete_confirm" title="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Delete message');?>" href="<?php echo erLhcoreClassDesign::baseurl('messagebird/deletesmsmessage')?>/<?php echo htmlspecialchars($item->id) ?>">delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php include(erLhcoreClassDesign::designtpl('lhkernel/secure_links.tpl.php')); ?>

    <?php if (isset($pages)) : ?>
        <?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>
    <?php endif;?>
<?php endif; ?>