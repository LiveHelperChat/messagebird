<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Integration settings'); ?></h1>

<form action="" method="post" ng-non-bindable>

    <?php include(erLhcoreClassDesign::designtpl('lhkernel/csfr_token.tpl.php'));?>

    <?php if (isset($updated) && $updated == 'done') : $msg = erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Settings updated'); ?>
        <?php include(erLhcoreClassDesign::designtpl('lhkernel/alert_success.tpl.php'));?>
    <?php endif; ?>

    <div class="form-group">
        <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Integration endpoint'); ?></label>
        <input type="text" name="endpoint" placeholder="Sandbox - https://integrations.messagebird.com" class="form-control form-control-sm" list="endpoint" value="<?php isset($mb_options['endpoint']) ? print htmlspecialchars($mb_options['endpoint']) : ''?>" />
        <datalist id="endpoint">
            <option value="https://integrations.messagebird.com">
        </datalist>
    </div>

    <div class="form-group">
        <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Channel ID'); ?></label>
        <input type="text" name="channel_id" class="form-control form-control-sm" value="<?php isset($mb_options['channel_id']) ? print htmlspecialchars($mb_options['channel_id']) : ''?>" />
    </div>

    <div class="form-group">
        <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Template ID Namespace'); ?></label>
        <input type="text" name="namespace" class="form-control form-control-sm" value="<?php isset($mb_options['namespace']) ? print htmlspecialchars($mb_options['namespace']) : ''?>" />
    </div>

    <div class="form-group">
        <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Conversation endpoint'); ?></label>
        <input type="text" name="convendpoint" placeholder="Sandbox - https://conversations.messagebird.com" class="form-control form-control-sm" list="convendpoint" value="<?php isset($mb_options['convendpoint']) ? print htmlspecialchars($mb_options['convendpoint']) : ''?>" />
        <datalist id="convendpoint">
            <option value="https://whatsapp-sandbox.messagebird.com">
            <option value="https://conversations.messagebird.com">
        </datalist>
    </div>

    <div class="form-group">
        <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Access Key'); ?></label>
        <input type="text" name="access_key" class="form-control form-control-sm" value="<?php isset($mb_options['access_key']) ? print htmlspecialchars($mb_options['access_key']) : ''?>" />
    </div>

    <button name="StoreOptions" class="btn btn-sm btn-secondary" type="submit"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Save'); ?></button>

</form>