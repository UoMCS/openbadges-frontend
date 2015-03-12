<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../autoload.php';
require_once __DIR__ . '/../vendor/autoload.php';

use UoMCS\OpenBadges\Frontend\Client;

use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();

$app->get('/assertions/{uid}', function($uid) use ($app) {
  $client = new Client();
  $response = $client->get("/assertions/$uid");

  if (!$response->isOk())
  {
    $app->abort($response->getStatusCode());
  }

  $badge = $client->getBodyArray();

  $loader = new Twig_Loader_Filesystem(TWIG_TEMPLATES_DIR);
  $twig = new Twig_Environment($loader);

  return $twig->render('assertions/show.html', array('badge' => $badge));
})
->assert('uid', '[0-9a-zA-Z]+');

$app->get('/assertions/{email}', function($email) use($app) {
  $client = new Client();
  $response = $client->get("/assertions/$email");

  if (!$response->isOk())
  {
    $app->abort($response->getStatusCode());
  }

  $badges = $client->getBodyArray();

  $loader = new Twig_Loader_Filesystem(TWIG_TEMPLATES_DIR);
  $twig = new Twig_Environment($loader);

  return $twig->render('assertions/list.html', array('badges' => $badges));
});

$app->get('/badges/images/{$id}', function($id) use ($app) {
  $client = new Client();
  $client_response = $client->get("/badges/images/$id");

  if (!$client_response->isOk())
  {
    $app->abort($client_response->getStatusCode());
  }

  $response = new Response();
  $response->setContent($client_response->getBody());
  $response->headers->set('Content-Type', 'image/png');

  return $response;
})
->assert('id', '[1-9][0-9]*');

$app->get('/badges/{id}', function($id) use ($app) {
  $client = new Client();
  $response = $client->get("/badges/$id");

  if (!$response->isOk())
  {
    $app->abort($response->getStatusCode());
  }

  $badge = $client->getBodyArray();

  $loader = new Twig_Loader_Filesystem(TWIG_TEMPLATES_DIR);
  $twig = new Twig_Environment($loader);

  return $twig->render('badges/show.html', array('badge' => $badge));
})
->assert('id', '[1-9][0-9]*');

$app->get('/badges', function() use ($app) {
  $client = new Client();
  $response = $client->get('/badges');

  if (!$response->isOk())
  {
    $app->abort($response->getStatusCode(), 'Error retrieving data: ' . $response->getBody());
  }

  $badges = $client->getBodyArray();

  $loader = new Twig_Loader_Filesystem(TWIG_TEMPLATES_DIR);
  $twig = new Twig_Environment($loader);

  return $twig->render('badges/index.html', array('badges' => $badges));
});

$app->get('/issuers/{id}', function($id) use ($app) {
  $client = new Client();
  $response = $client->get("/issuers/$id");

  if (!$response->isOk())
  {
    $app->abort($response->getStatusCode());
  }

  $issuer = $client->getBodyArray();

  $loader = new Twig_Loader_Filesystem(TWIG_TEMPLATES_DIR);
  $twig = new Twig_Environment($loader);

  return $twig->render('issuers/show.html', array('issuer' => $issuer));
});

$app->get('/issuers', function() use ($app) {
  $client = new Client();
  $response = $client->get('/issuers');

  if (!$response->isOk())
  {
    $app->abort($response->getStatusCode(), 'Error retrieving data: ' . $response->getBody());
  }

  $issuers = $client->getBodyArray();

  $loader = new Twig_Loader_Filesystem(TWIG_TEMPLATES_DIR);
  $twig = new Twig_Environment($loader);

  return $twig->render('issuers/index.html', array('issuers' => $issuers));
});

$app['debug'] = OPEN_BADGES_DEBUG_MODE;
$app->run();
