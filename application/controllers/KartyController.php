<?php

/**
 * @menu Skladové karty
 */
class KartyController extends Brm_Controller_Action {
    public function indexAction() {
	
    }
    
    /**
     * @menu Nová karta
     */
    public function newAction() {
	$model = $this->getModel()->getKarty();
	
	$form = $model->getFormNovaKarta();
	
	$this->view->form = $form;
    }
}