<?php

abstract class Brm_Model_Abstract extends \Nette\Object {
    /**
     * Konfiguracni moznosti z configu
     *
     * @var Array
     */
    private $_options;

    public function __construct($options) {
        $this->_options = $options;
    }

    protected function getOptions() {
        return $this->_options;
    }
}