<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Webhooks'); ?></h1>

<p>This is all webhooks we have found in MessageBird</p>

<table class="table table-sm" ng-non-bindable>
    <thead>
    <tr>
        <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','ID'); ?></th>
        <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','URL'); ?></th>
        <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Channel ID'); ?></th>
        <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Events'); ?></th>
        <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Status'); ?></th>
        <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Created At'); ?></th>
        <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Updated At'); ?></th>
        <th></th>
    </tr>
    </thead>
    <?php foreach ($webhooks['items'] as $webhook) : ?>
        <tr>
            <td>
                <?php echo htmlspecialchars($webhook['id'])?>
            </td>
            <td>
                <?php echo htmlspecialchars($webhook['url'])?>
                <?php if (strpos($webhook['url'],'webhooks/incoming') !== false) : ?>
                    <?php
                    $webhookParts = explode('/',$webhook['url']);
                    $incomingWebhook = erLhcoreClassModelChatIncomingWebhook::findOne(array('filter' => array('identifier' =>  array_pop($webhookParts))));
                    if (is_object($incomingWebhook)) : ?>
                        <br/><a href="<?php echo erLhcoreClassDesign::baseurl('webhooks/editincoming')?>/<?php echo $incomingWebhook->id?>"><span class="badge bg-info"><?php echo htmlspecialchars($incomingWebhook->name)?></span></a>
                    <?php endif; ?>
                <?php endif; ?>
            </td>
            <td>
                <?php echo htmlspecialchars($webhook['channelId'])?>
                <?php if ($instance->getChannelId() == $webhook['channelId']) : ?>
                    <br/><a href="<?php echo erLhcoreClassDesign::baseurl('messagebird/settings')?>"><span class="badge bg-info">Default</span></a>
                <?php elseif (($whatsappAccount = LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdAccount::findOne(['filter' => ['channel_id' => $webhook['channelId']]])) !== false) : ?>
                    <br/><a href="<?php echo erLhcoreClassDesign::baseurl('messagebird/editaccount')?>/<?php echo $whatsappAccount->id?>"><span class="badge bg-info"><?php echo htmlspecialchars($whatsappAccount)?></span></a>
                <?php endif; ?>
            </td>
            <td>
                <?php echo htmlspecialchars(implode(', ',$webhook['events']))?>
            </td>
            <td>
                <?php echo htmlspecialchars($webhook['status'])?>
            </td>
            <td>
                <?php echo htmlspecialchars($webhook['createdDatetime'])?>
            </td>
            <td>
                <?php echo htmlspecialchars(is_string($webhook['updatedDatetime']) ? $webhook['updatedDatetime'] : '')?>
            </td>
            <td>
                <a class="csfr-required csfr-post material-icons text-danger" data-trans="delete_confirm" title="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Delete webhook');?>" href="<?php echo erLhcoreClassDesign::baseurl('messagebird/deletewebhook')?>/<?php echo htmlspecialchars($webhook['id']) ?>">delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<form action="" method="post">

    <?php include(erLhcoreClassDesign::designtpl('lhkernel/csfr_token.tpl.php'));?>

    <h5>Set webhook</h5>

    <div class="form-group">
        <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Business account');?></label>
        <?php echo erLhcoreClassRenderHelper::renderCombobox( array (
            'input_name'     => 'business_account_id',
            'optional_field' => erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Default configuration'),
            'selected_id'    => 0,
            'css_class'      => 'form-control form-control-sm',
            'list_function'  => '\LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdAccount::getList'
        )); ?>
    </div>

    <div class="form-group">
        <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Incoming webhook, look for MessageBirdWhatsApp');?></label>
        <?php echo erLhcoreClassRenderHelper::renderCombobox( array (
            'input_name'     => 'webhook_id',
            'selected_id'    => 0,
            'css_class'      => 'form-control form-control-sm',
            'list_function_params' => ['limit' => false],
            'list_function'  => 'erLhcoreClassModelChatIncomingWebhook::getList'
        )); ?>
    </div>

    <button type="submit" name="setWebhook" class="btn btn-sm btn-secondary" value="setWebhook">Set webhook</button>

</form>
