<?php

class Brm_Plugin_Navigation extends Zend_Controller_Plugin_Abstract {
    /**
     *
     * @var Zend_Navigarion 
     */
    protected $_container;

    /**
     *
     * @var Zend_Controller_Front
     */
    protected $_frontController;

    protected $_acl;
    /**
     *
     * @var FP_Model_Base
     */
    protected $_model;

    public function __construct( Zend_Controller_Front $frontController, Brm_Model_Brmsklad $model, Zend_Acl $acl ) {
        $this->_frontController = $frontController;
        $this->_container = new Zend_Navigation();
        $this->_acl = $acl;
        $this->_model = $model;
    }

    public function routeShutdown(Zend_Controller_Request_Abstract $request) {
	$module = 'default';
	
	$directory = APPLICATION_PATH . '/controllers';
	foreach (new DirectoryIterator($directory) as $file) {
	    if (!$file->isFile()) { continue; }

	    if ( strpos($file, 'Controller.php') === false ) { continue; }

	    require_once $file->getPathname();
	    $rf = new Zend_Reflection_File($file->getPathname());
	    $classes = $rf->getClasses();

	    foreach ($classes as $class) {
		if ( !preg_match(';^([A-Z][a-z]+_)?([A-Z][a-z]+)Controller$;', $class->getName(), $m) ) { continue; }

		try {
		    $docBlock = $class->getDocblock();
		}
		catch (Zend_Reflection_Exception $e) {
		    continue;
		}

		if (!$docBlock->hasTag('menu')) {
		    continue;
		}
		$tag = $docBlock->getTag('menu');
		$name = $tag->getDescription();

		$controller = strtolower($m[2]);

		if (!$this->_isAllowed($controller, 'index')) {
		    continue;
		}

		$dpage = new Zend_Navigation_Page_Mvc();
		$dpage->setModule($module);
		$dpage->setController($controller);
		$dpage->setAction('index');
		$dpage->setLabel($name);
		$dpage->setResetParams(true);
		$dpage->setId("{$controller}:index");

		$methods = $class->getMethods();
		foreach ($methods as $method) {
		    if ( !preg_match(';^([a-z]+)Action$;i', $method->getName(), $n) ) { continue; }

		    try {
			$docBlockMethod = $method->getDocblock();
		    }
		    catch (Zend_Reflection_Exception $e) {
			continue;
		    }

		    if (!$docBlockMethod->hasTag('menu')) {
			continue;
		    }

		    $action = strtolower($n[1]);

		    if (!$this->_isAllowed($controller, $action)) {
			continue;
		    }

		    $tagMethod = $docBlockMethod->getTag('menu');
		    $nameMethod = $tagMethod->getDescription();

		    $subpage = new Zend_Navigation_Page_Mvc();

		    $subpage->setModule($module);
		    $subpage->setController($controller);
		    $subpage->setAction($action);
		    $subpage->setLabel($nameMethod);
		    $subpage->setResetParams(true);
		    $subpage->setId("{$controller}:{$action}");

		    $dpage->addPage($subpage);
		}
		$this->_container->addPage($dpage);
	    }
	    
	}
	
        $view = Zend_Layout::getMvcInstance()->getView();
        $view->navigation($this->_container);
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $request->getActionName();

        $controllerClass = ($module != 'default' ? UcFirst($module).'_' : '').UcFirst($controller).'Controller';

        require_once sprintf('%s/controllers/%sController.php', APPLICATION_PATH, ucfirst($controller));

        $reflectionClass = new Zend_Reflection_Class( $controllerClass );
        $reflectionMethod = $reflectionClass->getMethod($action.'Action');

        try {
            $docBlock = $reflectionMethod->getDocblock();
        }
        catch (Zend_Reflection_Exception $e) {
            return;
        }

        if (!$docBlock->hasTag('menu') && $docBlock->hasTag('menuRef')) {
            $tag = $docBlock->getTag('menuRef');
            $value = $tag->getDescription();

            $found = $this->_container->findAllById($value);
            foreach ($found as $page) {
                $page->setActive(true);
            }
        }
    }

    protected function _isAllowed( $controller, $action ) {
        return true;
	
	$role = $this->_model->getRole();

        $stack = array('mvc', $module, $controller);

        while (1) {
            if (empty($stack)) {
                break;
            }
            $resource = implode('.', $stack);

            if ($this->_acl->has($resource)) {
                if ( !$this->_acl->isAllowed($role, $resource, $action) ) {
                    return false;
                }
                return true;
            }
            array_pop($stack);
        }
        return false;
    }
}