<?php

class Brm_Model_Other extends Brm_Model_Abstract {
    /**
     *
     * @var FP_Model_Base
     */
    private $_mainModel;

    /**
     * 
     * @param Brm_Model_Brmlab $mainModel
     * @param Array $options
     */
    public function __construct(Brm_Model_Brmsklad $mainModel, $options) {
        parent::__construct($options);

        $this->_mainModel = $mainModel;
    }

    /**
     * Vrati hlavni model aplikace, aby dilci model mel pristup k cele aplikaci
     * 
     * @return Brm_Model_Brmlab
     */
    protected function getMainModel() {
       return $this->_mainModel;
    }
}