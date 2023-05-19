<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lhc_messagebird_tmpl_disabled";
$def->class = '\LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdTemplateDisabled';

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentManualGenerator' );

return $def;

?>