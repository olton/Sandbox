<?php

namespace Event_Admin;

define('HASH_SALT',    "My super hash salt!");
define('DSP',          DIRECTORY_SEPARATOR);
define('APP_ROOT',     dirname(__FILE__).DSP);
define('CONFIG_PATH',  APP_ROOT.DSP.'Config'.DSP);
define('CLASSES_PATH', APP_ROOT.DSP.'Classes'.DSP);
define('MODELS_PATH',  APP_ROOT.DSP.'Models'.DSP);
define('HOOKS_PATH',   APP_ROOT.DSP.'Hooks'.DSP);
define('SANDBOX_PATH', APP_ROOT.DSP.'Sandbox'.DSP);
define('VIEW_PATH',    DSP.'Views'.DSP);
define('VIEW_RENDER_PATH',    'Views'.DSP);

error_reporting(E_ALL);

ini_set('session.gc_maxlifetime', 60*60);
ini_set('max_input_vars', 10000);
ini_set('memory_limit', '256M');

//ini_set('memory_limit', '-1');

session_start();

include('bootstrap.php');

use Classes\Application;
use Classes\Security;
use Classes\Url;

$config = array(
    'database'   => include(CONFIG_PATH.'database.php'),
    'routes'     => include(CONFIG_PATH.'routes.php'),
    'controller' => 'Controllers\\',
    'salt' => 'Борода не делает козла раввином'
);

$_SESSION['current'] = 1;
$_SESSION['user']['name'] = 'olton';

$GET = Security::XSS($_GET, ENT_QUOTES);
$POST = Security::XSS($_POST, ENT_QUOTES);
$REQUEST = Security::XSS($_REQUEST, ENT_QUOTES);

$app = new Application($config);
$app->Run(
    array(
        'preprocess' => array('Hooks/preprocess.php'),
        'postprocess' => array('Hooks/postprocess.php')
    )
);
