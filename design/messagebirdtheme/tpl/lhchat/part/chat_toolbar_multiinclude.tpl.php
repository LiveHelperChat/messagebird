<?php if (($chat->iwh_id > 0 && ($incomingWebhook = erLhcoreClassModelChatIncomingWebhook::fetch($chat->iwh_id)) instanceof erLhcoreClassModelChatIncomingWebhook) && $incomingWebhook->scope == 'messagebird' ) : ?>
    <div class="me-auto">
        <?php if (max($chat->last_user_msg_time,$chat->pnd_time) < time() - 24 * 3600) : ?>
            <span class="text-danger fw-bold"><img src="<?php echo erLhcoreClassDesign::design('images/whatsapp.png')?>" /> WhatsApp: Expired</span>
        <?php else : ?>
            <span class="text-primary fw-bold"><img src="<?php echo erLhcoreClassDesign::design('images/whatsapp-blue.png')?>" /> WhatsApp: <?php echo erLhcoreClassChat::formatSeconds(24 * 3600 - (time() - max($chat->last_user_msg_time,$chat->pnd_time))); ?> left.</span>
        <?php endif; ?>
    </div>
<?php endif; ?>


