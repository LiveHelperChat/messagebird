<?php

namespace LiveHelperChatExtension\messagebird\providers;

class erLhcoreClassModelMessageBirdTemplateDisabled
{
    use \erLhcoreClassDBTrait;

    public static $dbTable = 'lhc_messagebird_tmpl_disabled';

    public static $dbTableId = 'id';

    public static $dbSessionHandler = 'erLhcoreClassExtensionMessagebird::getSession';

    public static $dbSortOrder = 'DESC';

    public function getState()
    {
        return array(
            'id' => $this->id
        );
    }

    public $id = null;
}

?>