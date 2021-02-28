<?php
ini_set('memory_limit', '4096M');
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
if ($_SERVER['REQUEST_URI'] == '/news/v-kakom-rajone-sanktpeterburga-luchshe-kupit-kvartiru') {
	//unset($_COOKIE['v-kakom-rajone-sanktpeterburga-luchshe-kupit-kvartiru#rating']);
	array_walk($_COOKIE, function ($item, $key) {
		if (strpos($key, '#') !== false) {
			unset($_COOKIE[$key]);
		}
	});
}

if($_SERVER['REQUEST_URI'] == "/index.php") {
    header("Location: /", TRUE, 301);
    exit();
}
if($_SERVER['REQUEST_URI'] == "/voen-ipoteka") {
    header("Location: /military", TRUE, 301);
    exit();
}
if(strpos($_SERVER["REQUEST_URI"],'lamberi-1014')){
	header("Location: /404", TRUE, 301);
	exit();
}




$ajax_request = ! empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
if(!$ajax_request) {

    $in_array_url = array(
        'shuvalovskiy',
        'zhk-sofiya',
        'yuzhnaya-akvatoriya',
        'zhk-parusa',
        'novayaohta'
    );

    $explode_url = explode('/', $_SERVER['QUERY_STRING']);
    $count_url = count($explode_url);
    if (empty($explode_url[1])) {
        $explode_url[1] = '';
    }
    if ($count_url > 1 && !empty($explode_url[1])) {
        if (!in_array($explode_url[1], $in_array_url)) {
            $last = array_pop($explode_url);
            if (empty($last)) {
                $host = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'];
				//echo $host;
                $find = $_SERVER['QUERY_STRING'];
                $replace = implode('/', $explode_url);
                $url = str_replace($find, $replace, $_SERVER['REQUEST_URI']); // если будет GET запрос
                $location = $host . $url;

                header("Location: " . $location, TRUE, 301);
                exit();
            }
        }
    }
}

set_time_limit(600);
session_start();

/*
 *---------------------------------------------------------------
 * APPLICATION ENVIRONMENT
 *---------------------------------------------------------------
 *
 * You can load different configurations depending on your
 * current environment. Setting the environment also influences
 * things like logging and error reporting.
 *
 * This can be set to anything, but default usage is:
 *
 *     development
 *     testing
 *     production
 *
 * NOTE: If you change these, also change the error_reporting() code below
 *



if (isset($_SERVER['APPLICATION_ENV']) && in_array($_SERVER['APPLICATION_ENV'], array('vdev', 'dev')))
{
	define('ENVIRONMENT', 'development');
}
else
{
	define('ENVIRONMENT', 'production');
}

/*
 *---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------
 *
 * Different environments will require different levels of error reporting.
 * By default development will show errors but testing and live will hide them.


if (defined('ENVIRONMENT'))
{
	switch (ENVIRONMENT)
	{
		case 'development':
			// error_reporting(E_ALL & ~E_NOTICE); // & ~E_NOTICE - отключаем Notice: (ошибка когда не существует переменной)
			error_reporting(0);
		break;

		case 'testing':
            error_reporting(E_ALL); // & ~E_NOTICE - отключаем Notice: (ошибка когда не существует переменной)
		break;

		case 'production':
			error_reporting(0);
       	     		error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
		break;

		default:
			exit('The application environment is not set correctly.');
	}
}
 */
/*
 *---------------------------------------------------------------
 * SYSTEM FOLDER NAME
 *---------------------------------------------------------------
 *
 * This variable must contain the name of your "system" folder.
 * Include the path if the folder is not in the same  directory
 * as this file.
 *
 */
	$system_path = 'codeigniter';

/*
 *---------------------------------------------------------------
 * APPLICATION FOLDER NAME
 *---------------------------------------------------------------
 *
 * If you want this front controller to use a different "application"
 * folder then the default one you can set its name here. The folder
 * can also be renamed or relocated anywhere on your server.  If
 * you do, use a full server path. For more info please see the user guide:
 * http://codeigniter.com/user_guide/general/managing_apps.html
 *
 * NO TRAILING SLASH!
 *
 */
	$application_folder = 'apex';

/*
 * --------------------------------------------------------------------
 * DEFAULT CONTROLLER
 * --------------------------------------------------------------------
 *
 * Normally you will set your default controller in the routes.php file.
 * You can, however, force a custom routing by hard-coding a
 * specific controller class/function here.  For most applications, you
 * WILL NOT set your routing here, but it's an option for those
 * special instances where you might want to override the standard
 * routing in a specific front controller that shares a common CI installation.
 *
 * IMPORTANT:  If you set the routing here, NO OTHER controller will be
 * callable. In essence, this preference limits your application to ONE
 * specific controller.  Leave the function name blank if you need
 * to call functions dynamically via the URI.
 *
 * Un-comment the $routing array below to use this feature
 *
 */
	// The directory name, relative to the "controllers" folder.  Leave blank
	// if your controller is not in a sub-folder within the "controllers" folder
	// $routing['directory'] = '';

	// The controller class file name.  Example:  Mycontroller
	// $routing['controller'] = '';

	// The controller function you wish to be called.
	// $routing['function']	= '';


/*
 * -------------------------------------------------------------------
 *  CUSTOM CONFIG VALUES
 * -------------------------------------------------------------------
 *
 * The $assign_to_config array below will be passed dynamically to the
 * config class when initialized. This allows you to set custom config
 * items or override any default config values found in the config.php file.
 * This can be handy as it permits you to share one application between
 * multiple front controller files, with each file containing different
 * config values.
 *
 * Un-comment the $assign_to_config array below to use this feature
 *
 */
    $assign_to_config['language_prefix'] = 'ru';
    $assign_to_config['default_theme'] = 'default';



// --------------------------------------------------------------------
// END OF USER CONFIGURABLE SETTINGS.  DO NOT EDIT BELOW THIS LINE
// --------------------------------------------------------------------

/*
 * ---------------------------------------------------------------
 *  Resolve the system path for increased reliability
 * ---------------------------------------------------------------
 */

	// Set the current directory correctly for CLI requests
	if (defined('STDIN'))
	{
		chdir(dirname(__FILE__));
	}

	if (realpath($system_path) !== FALSE)
	{
		$system_path = realpath($system_path).'/';
	}

	// ensure there's a trailing slash
	$system_path = rtrim($system_path, '/').'/';

	// Is the system path correct?
	if ( ! is_dir($system_path))
	{
		exit("Your system folder path does not appear to be set correctly. Please open the following file and correct this: ".pathinfo(__FILE__, PATHINFO_BASENAME));
	}

/*
 * -------------------------------------------------------------------
 *  Now that we know the path, set the main path constants
 * -------------------------------------------------------------------
 */
	// The name of THIS file
	define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

	// The PHP file extension
	// this global constant is deprecated.
	define('EXT', '.php');

	// Path to the system folder
	define('BASEPATH', str_replace("\\", "/", $system_path));

	// Path to the front controller (this file)
	define('FCPATH', str_replace(SELF, '', __FILE__));

    // Path to the module (path)
	$ks = str_replace(SELF, '', __FILE__).'modules/';
    define('MDPATH', str_replace("\\", "/", $ks));

	// Name of the "system folder"
	define('SYSDIR', trim(strrchr(trim(BASEPATH, '/'), '/'), '/'));


    // Базовый УРЛ | http://domain.name
    define('BASEURL', '');

    // Базовый путь к картинкам
    define('URLIMAGE', '/asset/img/');

	// The path to the "application" folder
	if (is_dir($application_folder))
	{
		define('APPPATH', $application_folder.'/');
	}
	else
	{
		if ( ! is_dir(BASEPATH.$application_folder.'/'))
		{
			exit("Your application folder path does not appear to be set correctly. Please open the following file and correct this: ".SELF);
		}

		define('APPPATH', BASEPATH.$application_folder.'/');
	}

/*
 * --------------------------------------------------------------------
 * LOAD THE BOOTSTRAP FILE
 * --------------------------------------------------------------------
 *
 * And away we go...
 *
 */



global $h;
require_once(__DIR__ . '/apex/libraries/PhpConsole/__autoload.php');

$h = PhpConsole\Handler::getInstance();
$h->start();



require_once BASEPATH.'core/CodeIgniter.php';

/* End of file index.php */
/* Location: ./index.php */



function dump($var, $info = false) {
    global $h;
    if (!empty($h) && method_exists($h, 'debug')) {
        $h->debug($var);
    }
}
?>