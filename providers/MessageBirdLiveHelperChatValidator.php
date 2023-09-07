<?php

namespace LiveHelperChatExtension\messagebird\providers {
    #[\AllowDynamicProperties]
    class MessageBirdLiveHelperChatValidator {
        public static function validatePhone(& $item) {
            $definition = array(
                'phone' => new \ezcInputFormDefinitionElement(
                    \ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
                ),
                'name' => new \ezcInputFormDefinitionElement(
                    \ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
                ),
                'dep_id' => new \ezcInputFormDefinitionElement(
                    \ezcInputFormDefinitionElement::OPTIONAL, 'int', array('min_range' => 1)
                ),
            );

            $form = new \ezcInputForm( INPUT_POST, $definition );
            $Errors = array();

            if ($form->hasValidData( 'phone' ) && $form->phone != '') {
                $item->phone = $form->phone;
            } else {
                $Errors[] = \erTranslationClassLhTranslation::getInstance()->getTranslation('module/mattermost','Please enter a phone!');
            }

            if ($form->hasValidData( 'name' ) && $form->name != '') {
                $item->name = $form->name;
            } else {
                $Errors[] = \erTranslationClassLhTranslation::getInstance()->getTranslation('module/mattermost','Please enter a name!');
            }

            if ( $form->hasValidData( 'dep_id' )) {
                $item->dep_id = $form->dep_id;
            } else {
                $Errors[] = \erTranslationClassLhTranslation::getInstance()->getTranslation('module/mattermost','Please choose a department!');
            }

            return $Errors;
        }
        
        public static function validateAccount(& $item) {
            $definition = array(
                'name' => new \ezcInputFormDefinitionElement(
                    \ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
                ),
                'channel_id' => new \ezcInputFormDefinitionElement(
                    \ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
                ),
                'access_key' => new \ezcInputFormDefinitionElement(
                    \ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
                ),
                'template_id_namespace' => new \ezcInputFormDefinitionElement(
                    \ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
                ),
                'active' => new \ezcInputFormDefinitionElement(
                    \ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
                ),
                'dep_id' => new \ezcInputFormDefinitionElement(
                    \ezcInputFormDefinitionElement::OPTIONAL, 'int', array('min_range' => 1)
                ),
            );

            $form = new \ezcInputForm( INPUT_POST, $definition );
            $Errors = array();

            if ($form->hasValidData( 'name' ) && $form->name != '') {
                $item->name = $form->name;
            } else {
                $Errors[] = \erTranslationClassLhTranslation::getInstance()->getTranslation('module/mattermost','Please enter a name!');
            }

            if ($form->hasValidData( 'channel_id' ) && $form->channel_id != '') {
                $item->channel_id = $form->channel_id;
            } else {
                $Errors[] = \erTranslationClassLhTranslation::getInstance()->getTranslation('module/mattermost','Please enter a Channel ID!');
            }

            if ($form->hasValidData( 'access_key' ) && $form->access_key != '') {
                $item->access_key = $form->access_key;
            } else {
                $item->access_key = '';
            }

            if ($form->hasValidData( 'template_id_namespace' ) && $form->template_id_namespace != '') {
                $item->template_id_namespace = $form->template_id_namespace;
            } else {
                $item->template_id_namespace = '';
            }

            if ($form->hasValidData( 'active' ) && $form->active != '') {
                $item->active = $form->active;
            } else {
                $item->active = '';
            }

            if ( $form->hasValidData( 'dep_id' )) {
                $item->dep_id = $form->dep_id;
            } else {
                $Errors[] = \erTranslationClassLhTranslation::getInstance()->getTranslation('module/mattermost','Please choose a department!');
            }

            return $Errors;
        }

        public static function setWebhook()
        {
            $definition = array(
                'business_account_id' => new \ezcInputFormDefinitionElement(
                    \ezcInputFormDefinitionElement::OPTIONAL, 'int', array('min_range' => 0)
                ),
                'webhook_id' => new \ezcInputFormDefinitionElement(
                    \ezcInputFormDefinitionElement::OPTIONAL, 'int', array('min_range' => 1)
                )
            );

            $form = new \ezcInputForm( INPUT_POST, $definition );
            $Errors = array();
            $data = [];

            if ($form->hasValidData( 'business_account_id' )) {
                $data['business_account_id'] = $form->business_account_id;
            } else {
                $Errors[] = \erTranslationClassLhTranslation::getInstance()->getTranslation('module/mattermost','Please choose an account!');
            }

            if ($form->hasValidData( 'webhook_id' )) {
                $data['webhook_id'] = $form->webhook_id;
            } else {
                $Errors[] = \erTranslationClassLhTranslation::getInstance()->getTranslation('module/mattermost','Please choose a webhook!');
            }

            if (empty($Errors)) {
                try {
                    $dataWebhook = [];
                    $instance = \LiveHelperChatExtension\messagebird\providers\MessageBirdLiveHelperChat::getInstance();

                    if ($data['business_account_id'] == 0){
                        $dataWebhook['channel_id'] = $instance->getChannelId();
                    } else {
                        $account = \LiveHelperChatExtension\messagebird\providers\erLhcoreClassModelMessageBirdAccount::fetch($data['business_account_id']);
                        $dataWebhook['channel_id'] = $account->channel_id;
                        $instance->setAccessKey($account->access_key);
                        $instance->setChannelId($account->channel_id);
                    }

                    $webhook = \erLhcoreClassModelChatIncomingWebhook::fetch($data['webhook_id']);
                    $dataWebhook['url'] = \erLhcoreClassSystem::getHost() . \erLhcoreClassDesign::baseurldirect('webhooks/incoming') . '/' . $webhook->identifier;

                    $instance->setWebhook($dataWebhook);

                    return $Errors;
                    
                } catch (\Exception $e) {
                    $Errors[] = $e->getMessage();
                }
            }

            return $Errors;
        }
    }
}