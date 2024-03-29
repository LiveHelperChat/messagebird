<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Integration settings'); ?></h1>

<form action="" method="post" ng-non-bindable>

    <?php include(erLhcoreClassDesign::designtpl('lhkernel/csfr_token.tpl.php'));?>

    <?php if (isset($updated) && $updated == 'done') : $msg = erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Settings updated'); ?>
        <?php include(erLhcoreClassDesign::designtpl('lhkernel/alert_success.tpl.php'));?>
    <?php endif; ?>

    <div class="form-group">
        <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Integration endpoint (WhatsApp)'); ?></label>
        <input type="text" name="endpoint" placeholder="Sandbox - https://integrations.messagebird.com" class="form-control form-control-sm" list="endpoint" value="<?php isset($mb_options['endpoint']) ? print htmlspecialchars($mb_options['endpoint']) : ''?>" />
        <datalist id="endpoint">
            <option value="https://integrations.messagebird.com">
        </datalist>
    </div>

    <div class="form-group">
        <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Channel ID (WhatsApp)'); ?></label>
        <input type="text" name="channel_id" class="form-control form-control-sm" value="<?php isset($mb_options['channel_id']) ? print htmlspecialchars($mb_options['channel_id']) : ''?>" />
        <div class="fs12"><i>https://dashboard.messagebird.com/en/channels/whatsapp</i></div>
    </div>

    <div class="form-group">
        <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','WhatsApp Template ID Namespace'); ?></label>
        <input type="text" name="namespace" class="form-control form-control-sm" value="<?php isset($mb_options['namespace']) ? print htmlspecialchars($mb_options['namespace']) : ''?>" />
    </div>

    <div class="form-group">
        <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Conversation endpoint (WhatsApp)'); ?></label>
        <input type="text" name="convendpoint" placeholder="Sandbox - https://conversations.messagebird.com" class="form-control form-control-sm" list="convendpoint" value="<?php isset($mb_options['convendpoint']) ? print htmlspecialchars($mb_options['convendpoint']) : ''?>" />
        <datalist id="convendpoint">
            <option value="https://whatsapp-sandbox.messagebird.com">
            <option value="https://conversations.messagebird.com">
        </datalist>
    </div>

    <div class="form-group">
        <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Access Key (WhatsApp)'); ?></label>
        <input type="text" name="access_key" class="form-control form-control-sm" value="<?php isset($mb_options['access_key']) ? print htmlspecialchars($mb_options['access_key']) : ''?>" />
    </div>

    <div class="form-group">
        <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Access Key (SMS)'); ?></label>
        <input type="text" name="access_key_sms" class="form-control form-control-sm" value="<?php isset($mb_options['access_key_sms']) ? print htmlspecialchars($mb_options['access_key_sms']) : ''?>" />
    </div>

    <?php $incomingWebhook = erLhcoreClassModelChatIncomingWebhook::findOne(array('filter' => array('name' => 'MessageBirdWhatsApp')));
    if (is_object($incomingWebhook)) : ?>
        <div class="text-muted"><a href="<?php echo erLhcoreClassDesign::baseurl('webhooks/editincoming')?>/<?php echo $incomingWebhook->id?>"><span class="badge bg-info"><?php echo htmlspecialchars($incomingWebhook->name)?></span></a> Change default department</div>
    <?php endif; ?>

    <hr>

    <div class="form-group">
        <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Alert e-mail'); ?></label>
        <input type="text" name="alert_email" class="form-control form-control-sm" value="<?php isset($mb_options['alert_email']) ? print htmlspecialchars($mb_options['alert_email']) : ''?>" />
        <p><small><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','To this e-mail we will send an alert indicating there is a problem with messages delivery. If 5 or more times accours'); ?></small></p>
        <?php if (isset($mb_options['fail_sent']) && $mb_options['fail_sent'] == 1) : ?>
            <span class="badge bg-info"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Alert was sent!'); ?></span>
        <?php endif; ?>

        <?php if (isset($mb_options['fail_counter'])): ?>
            <span class="badge bg-info"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Failure counter - '); ?><?php echo (int)$mb_options['fail_counter']?></span>
        <?php endif; ?>
    </div>

    <button name="StoreOptions" class="btn btn-sm btn-secondary" type="submit"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Save'); ?></button>

</form>