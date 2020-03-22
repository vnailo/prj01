<?php

///*
echo "<pre>";
echo "#_SERVER - ".print_r($_SERVER, true)."\n";
//echo "#_REQUEST - ".print_r($_REQUEST, true)."\n";
echo "#_GET - ".print_r($_GET, true)."\n";
echo "#_POST - ".print_r($_POST, true)."\n";
echo "\n\n";
//*/

include_once 'router.Request.php';
include_once 'router.Router.php';
$router = new Router(new Request);

//$router = new Router();

$router->get('/', function($requests) {
  return <<<HTML
  <h1>Hello world</h1>
HTML;
});


$router->get('/v2', function($request) {
 //return json_encode($_REQUEST);
  return json_encode($request->getBody());
});


$router->get('/profile', function($request) {
  return <<<HTML
  <h1>Profile</h1>
HTML;
});

$router->post('/data', function($request) {

  return json_encode($request->getBody());
});
