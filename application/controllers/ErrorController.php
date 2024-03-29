<?php

class ErrorController extends Brm_Controller_Action {
    public function errorAction() {
	// Ensure the default view suffix is used so we always return good
        // content
        $this->_helper->viewRenderer->setViewSuffix('phtml');

        // Grab the error object from the request
        $errors = $this->_getParam('error_handler');

        \Nette\Debug::processException($errors->exception);

        // $errors will be an object set as a parameter of the request object,
        // type is a property
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:

                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $this->view->message = 'Stránka nenalezena';

                $layout = Zend_Layout::getMvcInstance();
                $layout -> title = "Stránka nenalezena, chyba 404";
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $this->view->message = 'Chyba aplikace';

                $layout = Zend_Layout::getMvcInstance();
                $layout -> title = "Chyba aplikace, chyba 500";
                break;
        }

        // pass the environment to the view script so we can conditionally
        // display more/less information
        $this->view->env       = $this->getInvokeArg('env');

        // pass the actual exception object to the view
        $this->view->exception = $errors->exception;

        // pass the request to the view
        $this->view->request   = $errors->request;
    }
}