<?php

/**
 * @menu Skladová místa
 */
class MistaController extends Brm_Controller_Action {
    public function indexAction() {
	$model = $this->getModel()->getMista();
	
	$mista = $model->getMista();
	
	$this->view->mista = $mista;
    }
    
    /**
     * @menu Nová karta
     */
    public function newAction() {
	$model = $this->getModel()->getMista();
	
	$form = $model->getFormNoveMisto();
	
	$this->view->form = $form;
    }
}