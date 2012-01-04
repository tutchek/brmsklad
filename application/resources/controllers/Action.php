<?php

class Brm_Controller_Action extends Zend_Controller_Action {
    /**
     * @var Zend_Controller_Action_Helper_Redirector
     */
    private $_redirector = null;

    /**
     * @var Zend_Controller_Action_Helper_FlashMessenger
     */
    private $_flashMessenger = null;

    /**
     *
     * @return Brm_Model_Brmsklad
     */
    protected function getModel() {
        return $this->getInvokeArg('model');
    }


    /**
     * @return Zend_Controller_Action_Helper_Redirector
     */
    public function getRedirector() {
        if (is_null($this->_redirector)) {
            $this->_redirector = $this->_helper->getHelper('Redirector');
        }

        return $this->_redirector;
    }

    /**
     *
     * @return Zend_Controller_Action_Helper_FlashMessenger
     */
    public function getFlashMessenger() {
        if (is_null($this->_flashMessenger)) {
            $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        }

        return $this->_flashMessenger;
    }
}