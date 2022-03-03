<h6><?php echo htmlspecialchars($template['name'])?> <span class="badge badge-secondary"><?php echo htmlspecialchars($template['category'])?></span></h6>
<?php $fieldsCount = 0;?>
<div class="rounded bg-light p-2">
<?php foreach ($template['components'] as $component) : ?>
    <?php if ($component['type'] == 'BODY') :
        $matchesReplace = [];
        preg_match_all('/\{\{[0-9]\}\}/is',$component['text'],$matchesReplace);
        if (isset($matchesReplace[0])) {
            $fieldsCount = count($matchesReplace[0]);
        }
        ?><p><?php echo htmlspecialchars($component['text'])?></p><?php endif; ?>
    <?php if ($component['type'] == 'FOOTER') : ?><p class="text-secondary"><?php echo htmlspecialchars($component['text'])?></p><?php endif; ?>
    <?php if ($component['type'] == 'BUTTONS') : ?>
        <?php foreach ($component['buttons'] as $button) : ?>
            <div class="pb-2"><button class="btn btn-sm btn-secondary"><?php echo htmlspecialchars($button['text'])?> | <?php echo htmlspecialchars($button['type'])?></button></div>
        <?php endforeach; ?>
    <?php endif; ?>
<?php endforeach; ?>
</div>
<!--=========||=========-->
<div class="row">
<?php for ($i = 0; $i < $fieldsCount; $i++) : ?>
    <div class="col-6" ng-non-bindable>
        <div class="form-group">
            <label class="font-weight-bold">{{<?php echo $i+1?>}}</label>
            <input type="text" class="form-control form-control-sm" name="field_<?php echo $i+1?>" value="<?php if (isset($data['field_' .  $i + 1])) : ?><?php echo htmlspecialchars($data['field_' .  $i + 1])?><?php endif; ?>">
        </div>
    </div>
<?php endfor; ?>
</div>

<?php /*
<pre>
<?php echo json_encode($template, JSON_PRETTY_PRINT)?>
</pre>*/ ?>
