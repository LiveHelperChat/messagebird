<div class="accordion mb-2" id="accordionExample">
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                Show the code
            </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <pre><?php print_r($template);?></pre>
            </div>
        </div>
    </div>
</div>

<h6><?php echo htmlspecialchars($template['name'])?> <span class="badge bg-secondary"><?php echo htmlspecialchars($template['category'])?></span></h6>
<?php $fieldsCount = 0;?>
<div class="rounded bg-light p-2">
    <?php foreach ($template['components'] as $component) : ?>
        <?php if ($component['type'] == 'HEADER' && $component['format'] == 'IMAGE' && isset($component['example']['header_url'][0])) : ?>
            <img src="<?php echo htmlspecialchars($component['example']['header_url'][0])?>" />
        <?php endif; ?>
        <?php if ($component['type'] == 'HEADER' && $component['format'] == 'DOCUMENT' && isset($component['example']['header_url'][0])) : ?>
            <div>
                <span class="badge bg-secondary">FILE: <?php echo htmlspecialchars($component['example']['header_url'][0])?></span>
            </div>
        <?php endif; ?>
        <?php if ($component['type'] == 'HEADER' && $component['format'] == 'VIDEO' && isset($component['example']['header_url'][0])) : ?>
            <div>
                <span class="badge bg-secondary">VIDEO: <?php echo htmlspecialchars($component['example']['header_url'][0])?></span>
            </div>
        <?php endif; ?>

        <?php if ($component['type'] == 'HEADER' && $component['format'] == 'TEXT') : ?>
            <div>
                <h5><?php echo htmlspecialchars($component['text'])?></h5>
            </div>
        <?php endif; ?>

    <?php endforeach; ?>
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
                <label class="fw-bold">{{<?php echo $i+1?>}}</label>
                <input type="text" class="form-control form-control-sm" name="field_<?php echo $i+1?>" value="<?php if (isset($data['field_' .  $i + 1])) : ?><?php echo htmlspecialchars($data['field_' .  $i + 1])?><?php endif; ?>">
            </div>
        </div>
    <?php endfor; ?>
</div>

<?php /*
<pre>
<?php echo json_encode($template, JSON_PRETTY_PRINT)?>
</pre>*/ ?>
