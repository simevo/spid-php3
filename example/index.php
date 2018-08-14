<?php
require_once(__DIR__ . "/../vendor/autoload.php");

use SpidPHP\SpidPHP;
use SpidPHP\Spid\Saml;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$spid = new SpidPHP([
'sp_entityid' => 'http://sp3.simevo.com/',
    'sp_key_file' => '/Users/lorenzocattaneo/Projects/spid-php3/example/sp.key',
    'sp_cert_file' => '/Users/lorenzocattaneo/Projects/spid-php3/example/sp.crt',
    'sp_assertionconsumerservice' => 'http://sp3.simevo.com/acs',
    'sp_singlelogoutservice' => 'http://sp3.simevo.com/slo',
    'sp_org_name' => 'test_simevo',
    'sp_org_display_name' => 'Test Simevo'
]);

//$spid->loadIdpMetadata("");
$request_uri = explode('?', $_SERVER['REQUEST_URI'], 2);

switch ($request_uri[0]) {
    // Home page
    case '/':
        require './views/home.php';
        break;
    // Metadata page
    case '/metadata':
        require './views/metadata.php';
        break;
    // Everything else
    default:
        echo "404 not found";
        break;
}
?>