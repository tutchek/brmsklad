<?php
/*
class NotImplementedException extends Exception
{
    public function __construct( $message = "MESSAGE NOT GIVEN" )
    {
        parent::__construct("The {$message} is not implemented.");
    }
}
*/
class NotSetException extends Exception
{
    public function __construct( $message )
    {
        parent::__construct("The {$message} is not set.");
    }
}

class FileNotExistsException extends Exception
{
    public function __construct( $message )
    {
        parent::__construct("The file {$message} does not exists.");
    }
}

set_time_limit(0);

mb_internal_encoding("UTF-8");


// Define path to application directory

Header("Pragma: cache");
Header("Cache-Control: cache");
Header("Expires: ".GMDate("D, d M Y H:i:s", Time())." GMT");

date_default_timezone_set('Europe/Prague');


if (!defined('APPLICATION_PATH')) {
	define('APPLICATION_PATH', getenv('APPLICATION_PATH') ? realpath(getenv('APPLICATION_PATH')) : realpath(dirname(__FILE__) . '/../application/'));
}

if (!defined('PARTIALS_PATH')) {
	define('PARTIALS_PATH', getenv('PARTIALS_PATH') ? realpath(getenv('PARTIALS_PATH')) : realpath(dirname(__FILE__) . '/../application/views/scripts/partials/'));
}

if (!defined('PUBLIC_PATH')) {
  define('PUBLIC_PATH', dirname(__FILE__));
}

// Define application environment
defined('APPLICATION_ENV')
	|| 	define('APPLICATION_ENV',
    	(getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Typically, you will also want to add your library/ directory
// to the include_path, particularly if it contains your ZF install
set_include_path(implode(PATH_SEPARATOR, array(
    APPLICATION_PATH . '/../library',
    get_include_path(),
)));
if (APPLICATION_ENV == 'development')
{
	error_reporting(E_ALL|E_STRICT);
	ini_set('display_errors','on');
}
require_once 'Nette/loader.php';

/*if (APPLICATION_ENV == 'development')
{
	error_reporting(E_ALL|E_STRICT);
	ini_set('display_errors','on');
}*/

$mode = \Nette\Debug::PRODUCTION;
if (APPLICATION_ENV == 'development')
{
    $mode = \Nette\Debug::DEVELOPMENT;
}

\Nette\Debug::enable($mode, dirname(__FILE__).'/log/php_error_'.date('Y-m-d').'.txt', 'michal@tulacek.eu');
\Nette\Debug::enableProfiler();

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    Array(
    	'config' => APPLICATION_PATH . '/configs/application.ini'
    )
);

//try
//{
	$application
		->	bootstrap()
		->	run();
/*}catch (Exception $e)
{
	//TODO UTF-8 meta tag
	echo '<h1>Chyba!</h1>';
	echo '<p>Chyba aplikace behem... a to je jedno :-) Omlouvame se, pracujeme na naprave!</p>';
	echo 'Chyba: <strong>'.$e->getMessage().'</strong>';
	echo '<pre>'.$e->getTraceAsString().'</pre>';
}*/