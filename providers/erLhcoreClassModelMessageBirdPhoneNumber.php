<?php

namespace LiveHelperChatExtension\messagebird\providers;
#[\AllowDynamicProperties]
class erLhcoreClassModelMessageBirdPhoneNumber
{
    use \erLhcoreClassDBTrait;

    public static $dbTable = 'lhc_messagebird_phone';

    public static $dbTableId = 'id';

    public static $dbSessionHandler = 'erLhcoreClassExtensionMessagebird::getSession';

    public static $dbSortOrder = 'DESC';

    public function getState()
    {
        return array(
            'id' => $this->id,
            'phone' => $this->phone,
            'name' => $this->name,
            'dep_id' => $this->dep_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        );
    }

    public function beforeSave($params = array())
    {
        if ($this->created_at == 0) {
            $this->created_at = time();
        }

        $this->updated_at = time();
        $this->phone = trim(str_replace('+','',$this->phone));
    }

    public function __toString()
    {
        return $this->name . ' [' . $this->phone . ']';
    }

    public function __get($var)
    {
        switch ($var) {

            case 'phone_name':
                $this->phone_name = $this->__toString();
                return $this->phone_name;

            case 'updated_at_ago':
                $this->updated_at_ago = \erLhcoreClassChat::formatSeconds(time() - $this->updated_at);
                return $this->updated_at_ago;

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
    public $phone = '';
    public $name = '';
    public $created_at = 0;
    public $updated_at = 0;
    public $chat_id = 0;
    public $dep_id = 0;
}

?>