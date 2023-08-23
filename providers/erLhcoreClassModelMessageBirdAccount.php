<?php

namespace LiveHelperChatExtension\messagebird\providers;

class erLhcoreClassModelMessageBirdAccount
{
    use \erLhcoreClassDBTrait;

    public static $dbTable = 'lhc_messagebird_account';

    public static $dbTableId = 'id';

    public static $dbSessionHandler = 'erLhcoreClassExtensionMessagebird::getSession';

    public static $dbSortOrder = 'DESC';

    public function getState()
    {
        return array(
            'id' => $this->id,
            'channel_id' => $this->channel_id,
            'name' => $this->name,
            'template_id_namespace' => $this->template_id_namespace,
            'access_key' => $this->access_key,
            'dep_id' => $this->dep_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'active' => $this->active,
        );
    }

    public function beforeSave($params = array())
    {
        if ($this->created_at == 0) {
            $this->created_at = time();
        }

        $this->updated_at = time();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function __get($var)
    {
        switch ($var) {

            case 'updated_at_ago':
                $this->updated_at_ago = \erLhcoreClassChat::formatSeconds(time() - $this->updated_at);
                return $this->updated_at_ago;

            case 'created_at_front':
                $this->created_at_front = date('Ymd') == date('Ymd',$this->created_at) ? date(\erLhcoreClassModule::$dateHourFormat,$this->created_at) : date(\erLhcoreClassModule::$dateDateHourFormat,$this->created_at);
                return $this->created_at_front;

            case 'department':
                $this->department = null;
                if ($this->dep_id > 0) {
                    try {
                        $this->department = \erLhcoreClassModelDepartament::fetch($this->dep_id,true);
                    } catch (\Exception $e) {

                    }
                }
                return $this->department;

            default:
                ;
                break;
        }
    }

    public $id = null;
    public $channel_id = '';
    public $name = '';
    public $template_id_namespace = '';
    public $access_key = '';
    public $dep_id = 0;
    public $created_at = 0;
    public $updated_at = 0;
    public $active = 1;

}

?>