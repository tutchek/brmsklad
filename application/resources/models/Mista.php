<?php

class Brm_Model_Mista extends Brm_Model_Other {
    public function getMista() {
	$table = new Brm_Data_Mista();
	
	return $table->fetchAll();
    }
    
    /**
     * @return Zend_Form
     */
    public function getFormNoveMisto() {
	$form = $this->_getFormMisto();
	
	return $form;
    }
    
    public function _getFormMisto() {
	$form = new Zend_Form();
	
	$nazevElement = new Zend_Form_Element_Text('nazev');
	$nazevElement
	    ->setLabel('Název místa')
	    ->setRequired(true);
	
	$popisElement = new Zend_Form_Element_Textarea('popis');
	$popisElement
	    ->setAttrib('rows', '5')
	    ->setAttrib('cols', '80')
	    ->setLabel('Popis místa');
	
	$submitElement = new Zend_Form_Element_Submit('submit');
	$submitElement->setLabel('Uložit');
	
	$form
	    ->addElement($nazevElement)
	    ->addElement($popisElement)
	    ->addElement($submitElement);
	
	return $form;
    }
}