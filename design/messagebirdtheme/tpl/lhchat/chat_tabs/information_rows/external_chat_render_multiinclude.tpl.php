<?php if ($incomingWebhook->scope == 'messagebirdsms') :
    $customExternalRendered = true;
    if (isset($chat_variables_array['iwh_field']) && !empty($chat_variables_array['iwh_field'])) {
        $phoneNumber = LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdPhoneNumber::findOne(['filter' => ['phone' => $chat_variables_array['iwh_field']]]);
    }
?>
<div class="col-6 pb-1">
    <span class="material-icons" <?php if ($incomingWebhook->icon_color != '') : ?>style="color: <?php echo htmlspecialchars($incomingWebhook->icon_color)?>"<?php endif;?> ><?php if ($incomingWebhook->icon != '') : ?><?php echo htmlspecialchars($incomingWebhook->icon)?><?php else : ?>extension<?php endif; ?></span><?php echo htmlspecialchars(isset($phoneNumber) && is_object($phoneNumber) ? $phoneNumber->name : $incomingWebhook->name)?>
</div>
<?php endif; ?>
