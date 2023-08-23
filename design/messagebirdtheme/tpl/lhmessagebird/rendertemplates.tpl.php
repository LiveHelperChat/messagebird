<option value=""><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Choose a template');?></option>
<?php foreach ($templates as $template) :  if (\LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdTemplateDisabled::getCount(['filter' => ['id' => $template['id']]]) > 0) {continue;} ?>
    <option value="<?php echo htmlspecialchars($template['name'] . '||' . $template['language'])?>"><?php echo htmlspecialchars($template['name'] . ' [' . $template['language'] . ']')?></option>
<?php endforeach; ?>