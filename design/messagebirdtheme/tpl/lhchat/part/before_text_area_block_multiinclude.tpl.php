<?php if (($chat->iwh_id > 0 && ($incomingWebhook = erLhcoreClassModelChatIncomingWebhook::fetch($chat->iwh_id)) instanceof erLhcoreClassModelChatIncomingWebhook) && $incomingWebhook->scope == 'messagebird' && max($chat->last_user_msg_time,$chat->pnd_time) < time() - 24 * 3600 ) : ?>
    <div class="position-absolute alert alert-danger p-1 m-2">
        <span class="text-muted fw-bold text-white"><span class="material-icons text-danger">warning</span> You can't reply to this conversation</span>
    </div>
<?php endif; ?>

