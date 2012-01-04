<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {
    /**
     * Front controller
     *
     * @var Zend_Controller_Front
     */
    protected $_fc = null;
    protected $_model = null;

    protected function _initLayout() {
    	Zend_Layout::startMvc(APPLICATION_PATH . '/layouts/scripts');
    
    	$view = Zend_Layout::getMvcInstance()->getView();
    	$view->doctype('XHTML1_STRICT');
    	$view->addBasePath(dirname(__FILE__) . '/views');
    	$view->addHelperPath(APPLICATION_PATH . '/layouts/helpers/', 'Brm_View_Helper');
    
    	$model = $this->getModel();
    	$view->model = $model;
    }

    protected function _initPaginator() {
    	Zend_Paginator::setDefaultScrollingStyle('Sliding');
    	Zend_View_Helper_PaginationControl::setDefaultViewPartial(
    		'partials/pagination.phtml'
    	);
    }

    protected function _initEnv() {
    	$fc = $this->getFrontController();
    	$fc->setParam('env', APPLICATION_ENV);
    }

    protected function _initResourcesLoading() {
      	$resourceLoader = new Zend_Loader_Autoloader_Resource(array(
      		    'basePath' => APPLICATION_PATH . '/resources',
      		    'namespace' => 'Brm',
      		));
      
      	$resourceLoader
      		->addResourceType('acl', 'acls/', 'Acl')
      		->addResourceType('auth', 'auths/', 'Auth')
      		->addResourceType('controller', 'controllers/', 'Controller')
      		->addResourceType('data', 'data/', 'Data')
      		->addResourceType('form', 'forms/', 'Form')
      		->addResourceType('model', 'models/', 'Model')
      		->addResourceType('plugin', 'plugins/', 'Plugin');
    }

    public function _initModel() {
      	$this->bootstrap('ResourcesLoading');
      
      	$model = $this->getModel();
      
      	$fc = $this->getFrontController();
      	$fc->setParam('model', $model);
    }

    public function _initPlugins() {
    	$this->bootstrap(array('ResourcesLoading', 'Db'));
    	$fc = $this->getFrontController();
    
    	$model = $this->getModel();
    	
    	$acl = new Brm_Acl_Main();
    	$model->setAcl($acl);
    	
    	$navigation = new Brm_Plugin_Navigation($fc, $model, $acl);
    	$fc->registerPlugin($navigation);
    	
    	/*
    	$navigation = new Brm_Plugin_Navigation($fc, $model, $acl);
    	$fc->registerPlugin($navigation);
    	
    	$passwordPlugin = new FP_Plugin_ChangePassword($fc, $model);
    	$fc->registerPlugin($passwordPlugin);
    
    	$aclPlugin = new FP_Plugin_Acl($fc, $model, $acl);
    	$fc->registerPlugin($aclPlugin);
    */
    }

    /**
     * Vrati FrontController
     * 
     * @return Zend_Controller_Front
     */
    protected function getFrontController() {
    	if (is_null($this->_fc)) {
    	    $this->bootstrap('FrontController');
    	    $this->_fc = $this->getResource('FrontController');
    	}
    	return $this->_fc;
    }

    /**
     * Vrati FrontController
     *
     * @return Zend_Controller_Front
     */
    protected function getModel() {
    	if (is_null($this->_model)) {
    	    $this->bootstrap('ResourcesLoading');
    	    $this->_model = new Brm_Model_Brmsklad($this->getOptions());
    	}
    	return $this->_model;
    }

}