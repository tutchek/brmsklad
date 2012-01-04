<?php

class Brm_Model_Brmsklad extends Brm_Model_Abstract {
    protected $_others = array();
    
    public function setAcl(Brm_Acl_Main $acl) {
	
    }
    
    /**
     * @return Brm_Model_Karty
     */
    public function getKarty() {
	if (!in_array('karty', $this->_others)) {
	    $this->_others['karty'] = new Brm_Model_Karty($this, $this->getOptions());
	}
	
	return $this->_others['karty'];
    }
    
    /**
     * @return Brm_Model_Mista
     */
    public function getMista() {
	if (!in_array('mista', $this->_others)) {
	    $this->_others['mista'] = new Brm_Model_Mista($this, $this->getOptions());
	}
	
	return $this->_others['mista'];
    }
}