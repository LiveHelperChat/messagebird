<?php

namespace LiveHelperChatExtension\messagebird\providers {

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
    }
}