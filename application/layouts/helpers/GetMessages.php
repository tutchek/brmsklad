<?php

class Brm_View_Helper_GetMessages extends Zend_View_Helper_Abstract {
    public function GetMessages() {
        $model = $this->view->model;

        //$messagesLocal = $model->getMessages();
	$messagesLocal = array();
	
        $flashmessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');

        $messages = array_merge($messagesLocal, $flashmessenger->getMessages());

        $val = '';
        foreach ($messages as $message) {
            $val .= "<p class=\"msg info\">{$message}</p>";
        }

        return $val;
    }
}