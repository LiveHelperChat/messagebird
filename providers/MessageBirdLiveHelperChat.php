<?php

namespace LiveHelperChatExtension\messagebird\providers {

    class MessageBirdLiveHelperChat {

        public static function getInstance() {

            if (self::$instance !== null){
                return self::$instance;
            }

            self::$instance = new self();

            return self::$instance;
        }

        public function __construct() {
            $mbOptions = \erLhcoreClassModelChatConfig::fetch('mb_options');
            $data = (array)$mbOptions->data;

            if (!isset($data['access_key']) || empty($data['access_key'])) {
                throw new \Exception('Access Key is not set!',100);
            }

            if (!isset($data['endpoint']) || empty($data['endpoint'])) {
                throw new \Exception('Endpoint is not set!',100);
            }

            if (!isset($data['channel_id']) || empty($data['channel_id'])) {
                throw new \Exception('ChannelID is not set!',100);
            }

            if (!isset($data['namespace']) || empty($data['namespace'])) {
                throw new \Exception('Namespace is not set!',100);
            }

            if (!isset($data['convendpoint']) || empty($data['convendpoint'])) {
                throw new \Exception('Conversation endpoint is not set!',100);
            }

            $this->endpoint = $data['endpoint'];
            $this->access_key = $data['access_key'];
            $this->namespace = $data['namespace'];
            $this->channel_id = $data['channel_id'];
            $this->convendpoint = $data['convendpoint'];
        }

        public function getTemplates() {
            return $this->getRestAPI([
                'baseurl' => $this->endpoint,
                'method' => 'v3/platforms/whatsapp/templates',
            ]);
        }

        public function getTemplate($name, $language) {
            return $this->getRestAPI([
                'baseurl' => $this->endpoint,
                'method' => "v2/platforms/whatsapp/templates/{$name}/{$language}",
            ]);
        }

        public function sendSMS($item) {

            $response = null;

            try {

                $paramsRequest = [
                    'baseurl' => 'https://rest.messagebird.com/',
                    'method' => 'messages',
                    'body_json' => json_encode([
                        'body' => $item->message,
                        'originator' => ($item->originator != '' ? $item->originator : $item->sender_phone->phone),
                        'recipients' => $item->phone,
                        'reference'  => 'verification'
                    ])
                ];

                $response = $this->getRestAPI($paramsRequest);

                // Responder
                if (isset($response['id'])) {
                    $item->mb_id_message = $response['id'];
                } else {
                    throw new \Exception('Message ID was not returned.');
                }

                if (isset($response['recipients']['items']) && is_array($response['recipients']['items'])) {
                    foreach ($response['recipients']['items'] as $itemRecipient) {
                        if ($itemRecipient['recipient'] == $item->phone) {
                            $statusMap = [
                                'pending' => \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdSMSMessage::STATUS_PENDING,
                                'sent' => \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdSMSMessage::STATUS_SENT,
                                'delivered' => \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdSMSMessage::STATUS_DELIVERED,
                                'buffered' => \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdSMSMessage::STATUS_BUFFERED,
                                'expired' => \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdSMSMessage::STATUS_EXPIRED,
                                'delivery_failed' => \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdSMSMessage::STATUS_FAILED,
                                'scheduled' => \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdSMSMessage::STATUS_SCHEDULED,
                            ];
                            if (isset($statusMap[$itemRecipient['status']])) {
                                $item->status = $statusMap[$itemRecipient['status']];
                            } else {
                                throw new \Exception('Unknown status - ' . $itemRecipient['status']);
                            }
                        }
                    }
                }

                $item->send_status_raw = json_encode($response);
                $item->saveThis();

            } catch (\Exception $e) {
                $item->send_status_raw = json_encode($response) . $e->getTraceAsString() . $e->getMessage();
                $item->status = \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdSMSMessage::STATUS_FAILED;
                $item->saveThis();
            }
        }

        public function sendTemplate($item, $templates = []) {

            $argumentsTemplate = [];

            $templatePresent = null;
            foreach ($templates['items'] as $template) {
                if ($template['name'] == $item->template && $template['language'] == $item->language) {
                    $templatePresent = $template;
                }
            }

            $bodyArguments = [];

            $bodyText = '';
            foreach ($templatePresent['components'] as $component) {
                if ($component['type'] == 'BODY') {
                    $bodyText = $component['text'];
                } elseif ($component['type'] == 'BUTTONS') {
                    foreach ($component['buttons'] as $indexButton => $button) {
                        if ($button['type'] == 'QUICK_REPLY') {
                            $bodyArguments[] = [
                                "type" => "button",
                                "sub_type" => "quick_reply",
                                "index" => (int)$indexButton,
                                "parameters" => [
                                    [
                                        "type" => "payload",
                                        "payload" => $item->template.'-quick_reply_'.$indexButton,
                                    ]
                                ]
                            ];
                        }
                    }
                } elseif ($component['type'] == 'HEADER' && $component['format'] == 'VIDEO') {
                    $bodyArguments[] = [
                        "type" => "header",
                        "parameters" => [
                            [
                                "type"=> "video",
                                "video"=> [
                                    "url"=> (isset($component['example']['header_url'][0]) ? $component['example']['header_url'][0] : 'https://omni.enviosok.com/design/defaulttheme/images/general/logo.png'),
                                ]
                            ]
                        ]
                    ];
                } elseif ($component['type'] == 'HEADER' && $component['format'] == 'DOCUMENT') {
                    $bodyArguments[] = [
                        "type" => "header",
                        "parameters" => [
                            [
                                "type"=> "document",
                                "document"=> [
                                    "url"=> (isset($component['example']['header_url'][0]) ? $component['example']['header_url'][0] : 'https://omni.enviosok.com/design/defaulttheme/images/general/logo.png'),
                                ]
                            ]
                        ]
                    ];
                } elseif ($component['type'] == 'HEADER' && $component['format'] == 'IMAGE') {
                    $bodyArguments[] = [
                        "type" => "header",
                        "parameters" => [
                            [
                                "type"=> "image",
                                "image"=> [
                                    "url"=> (isset($component['example']['header_url'][0]) ? $component['example']['header_url'][0] : 'https://omni.enviosok.com/design/defaulttheme/images/general/logo.png'),
                                ]
                            ]
                        ]
                    ];
                }
            }

            $item->message = $bodyText;

            $messageVariables = $item->message_variables_array;

            for ($i = 0; $i < 6; $i++) {
                if (isset($messageVariables['field_' . $i]) && $messageVariables['field_' . $i] != '') {
                    $item->message = str_replace('{{'.$i.'}}', $messageVariables['field_' . $i], $item->message);
                    $argumentsTemplate[] = ['type' => 'text','text' => $messageVariables['field_' . $i]];
                }
            }

            if (!empty($argumentsTemplate)) {
                $bodyArguments[] = [
                    'type' => 'body',
                    'parameters' => $argumentsTemplate,
                ];
            }

            $requestParams = [
                'baseurl' => $this->convendpoint,
                'method' => 'v1/send',
                'body_json' => json_encode([
                    'to' => '+'.$item->phone,
                    'type' => 'hsm',
                    'from' => $this->channel_id,
                    'content' => [
                        'hsm' => [
                            'namespace' => $this->namespace,
                            'templateName' => $item->template,
                            'language' => [
                                'policy' => 'deterministic',
                                'code' => $item->language
                            ],
                            'components' => $bodyArguments
                        ]
                    ]
                ])
            ];

            $response = null;

            try {

                $response = $this->getRestAPI($requestParams);

                // Responder
                if (isset($response['id'])) {
                    $item->mb_id_message = $response['id'];
                } else {
                    throw new \Exception('Message ID was not returned.');
                }

                $item->send_status_raw = json_encode($response);
                $item->saveThis();

            } catch (\Exception $e) {
                $item->send_status_raw = json_encode($response) . $e->getTraceAsString() . $e->getMessage();
                $item->status = \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdMessage::STATUS_FAILED;
                $item->saveThis();
            }
        }

        public function getRestAPI($params)
        {
            $try = isset($params['try']) ? $params['try'] : 3;

            for ($i = 0; $i < $try; $i++) {

                $ch = curl_init();
                $url = rtrim($params['baseurl'], '/') . '/' . $params['method'] . (isset($params['args']) ? '?' . http_build_query($params['args']) : '');

                if (!isset(self::$lastCallDebug['request_url'])) {
                    self::$lastCallDebug['request_url'] = array();
                }

                if (!isset(self::$lastCallDebug['request_url_response'])) {
                    self::$lastCallDebug['request_url_response'] = array();
                }

                self::$lastCallDebug['request_url'][] = $url;

                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT, self::$apiTimeout);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

                if (isset($params['method_type']) && $params['method_type'] == 'delete') {
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                }

                $headers = array(
                    'Accept: application/json',
                    'Authorization: AccessKey ' . $this->access_key
                );

                if (isset($params['body_json']) && !empty($params['body_json'])) {
                    curl_setopt($ch, CURLOPT_POST,1 );
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $params['body_json']);
                    $headers[] = 'Content-Type: application/json';
                    $headers[] = 'Expect:';
                }

                if (isset($params['bearer']) && !empty($params['bearer'])) {
                    $headers[] = 'Authorization: Bearer ' . $params['bearer'];
                }

                if (isset($params['headers']) && !empty($params['headers'])) {
                    $headers = array_merge($headers, $params['headers']);
                }

                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

                $startTime = date('H:i:s');
                $additionalError = ' ';

                if (isset($params['test_mode']) && $params['test_mode'] == true) {
                    $content = $params['test_content'];
                    $httpcode = 200;
                } else {
                    $content = curl_exec($ch);

                    if (curl_errno($ch))
                    {
                        $additionalError = ' [ERR: '. curl_error($ch).'] ';
                    }

                    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                }

                $endTime = date('H:i:s');

                if (isset($params['log_response']) && $params['log_response'] == true) {
                    self::$lastCallDebug['request_url_response'][] = '[T' . self::$apiTimeout . '] ['.$httpcode.']'.$additionalError.'['.$startTime . ' ... ' . $endTime.'] - ' . ((isset($params['body_json']) && !empty($params['body_json'])) ? $params['body_json'] : '') . ':' . $content;
                }

                if ($httpcode == 204) {
                    return array();
                }

                if ($httpcode == 404) {
                    throw new \Exception('Resource could not be found!');
                }

                if (isset($params['return_200']) && $params['return_200'] == true && $httpcode == 200) {
                    return $content;
                }

                if ($httpcode == 401) {
                    throw new \Exception('No permission to access resource!');
                }

                if ($content !== false)
                {
                    if (isset($params['raw_response']) && $params['raw_response'] == true){
                        return $content;
                    }

                    $response = json_decode($content,true);
                    if ($response === null) {
                        if ($i == 2) {
                            throw new \Exception('Invalid response was returned. Expected JSON');
                        }
                    } else {
                        if ($httpcode != 500) {
                            return $response;
                        }
                    }

                } else {
                    if ($i == 2) {
                        throw new \Exception('Invalid response was returned');
                    }
                }

                if ($httpcode == 500 && $i >= 2) {
                    throw new \Exception('Invalid response was returned');
                }

                usleep(300);
            }
        }

        private $endpoint = null;
        private $access_key = null;
        private $channel_id = null;
        private $convendpoint = null;
        private $namespace = null;

        private static $instance = null;
        public static $lastCallDebug = array();
        public static $apiTimeout = 40;
    }
}