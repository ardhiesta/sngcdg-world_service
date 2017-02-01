<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
spl_autoload_register(function ($classname) {
    require ("../src/classes/" . $classname . ".php");
});

//config
$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$config['db']['host']   = "[host]";
$config['db']['user']   = "[user]";
$config['db']['pass']   = "[password]";
$config['db']['dbname'] = "[dbname]";

$app = new \Slim\App(["settings" => $config]);

$container = $app->getContainer();

$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler("../logs/app.log");
    $logger->pushHandler($file_handler);
    return $logger;
};

$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'],
        $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

$app->get('/countries', function (Request $request, Response $response) {
    $this->logger->addInfo("Country list");
    $mapper = new WorldMapper($this->db);
    $tickets = $mapper->getCountries();

    $response = json_encode($tickets);
    return $response;
});

$app->get('/country', function (Request $request, Response $response) {
	$allGetVars = $request->getQueryParams();
	$starts_record = (int)$allGetVars['starts'];
	$records_per_page = (int)$allGetVars['total'];

    $this->logger->addInfo("Country list");
    $mapper = new WorldMapper($this->db);
    $tickets = $mapper->getCountriesPaging($starts_record, $records_per_page);

    $response = json_encode($tickets);
    return $response;
});

$app->run();
