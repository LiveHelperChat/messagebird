<?php

namespace LiveHelperChatExtension\messagebird\providers;

class erLhcoreClassModelMessageBirdSMSMessage
{
    use \erLhcoreClassDBTrait;

    public static $dbTable = 'lhc_messagebird_sms_message';

    public static $dbTableId = 'id';

    public static $dbSessionHandler = 'erLhcoreClassExtensionMessagebird::getSession';

    public static $dbSortOrder = 'DESC';

    public function getState()
    {
        return array(
            'id' => $this->id,
            'sender_phone_id' => $this->sender_phone_id,
            'originator' => $this->originator,
            'phone' => $this->phone,
            'message' => $this->message,
            'mb_id_message' => $this->mb_id_message,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'status' => $this->status,
            'status_txt' => $this->status_txt,
            'user_id' => $this->user_id,
            'send_status_raw' => $this->send_status_raw,
            'chat_id' => $this->chat_id,
            'dep_id' => $this->dep_id,
            'message_variables' => $this->message_variables
        );
    }

    public function beforeSave($params = array())
    {
        if ($this->created_at == 0) {
            $this->created_at = time();
        }

        $this->updated_at = time();
        $this->phone = str_replace('+','',$this->phone);
    }

    public function __toString()
    {
        return $this->phone;
    }

    public function __get($var)
    {
        switch ($var) {

            case 'updated_at_ago':
                $this->updated_at_ago = \erLhcoreClassChat::formatSeconds(time() - $this->updated_at);
                return $this->updated_at_ago;

            case 'user':
                $this->user = null;
                if ($this->user_id > 0) {
                    $this->user = \erLhcoreClassModelUser::fetch($this->user_id);
                }
                return $this->user;

            case 'sender_phone':
                $this->sender_phone = null;
                if ($this->sender_phone_id > 0) {
                    $this->sender_phone = \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdPhoneNumber::fetch($this->sender_phone_id);
                }
                return $this->sender_phone;

            case 'department':
                $this->department = null;
                if ($this->dep_id > 0) {
                    try {
                        $this->department = \erLhcoreClassModelDepartament::fetch($this->dep_id,true);
                    } catch (\Exception $e) {

                    }
                }
                return $this->department;

            case 'message_variables_array':
                if (!empty($this->message_variables)) {
                    $jsonData = json_decode($this->message_variables,true);
                    if ($jsonData !== null) {
                        $this->message_variables_array = $jsonData;
                    } else {
                        $this->message_variables_array = $this->message_variables;
                    }
                } else {
                    $this->message_variables_array = array();
                }
                return $this->message_variables_array;

            default:
                ;
                break;
        }
    }

    const STATUS_PENDING = 0;
    const STATUS_SENT = 1;
    const STATUS_DELIVERED = 2;
    const STATUS_BUFFERED = 3;
    const STATUS_EXPIRED = 4;
    const STATUS_FAILED = 5;
    const STATUS_SCHEDULED = 6;
    const STATUS_IN_PROCESS = 7;
    const STATUS_PENDING_PROCESS = 8;

    public $id = null;
    public $phone = '';
    public $message = '';
    public $mb_id_message = '';
    public $sender_phone_id = 0;
    public $originator = '';
    public $created_at = 0;
    public $updated_at = 0;
    public $status = self::STATUS_PENDING;
    public $status_txt = '';
    public $user_id = 0;
    public $send_status_raw = '';
    public $message_variables = '';
    public $chat_id = 0;
    public $dep_id = 0;
}

?>