<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../autoload.php';
require_once __DIR__ . '/../vendor/autoload.php';

use UoMCS\OpenBadges\Frontend\Client;

$app = new Silex\Application();

$app->get('/badges', function() use ($app) {
  $response = Client::get('/badges');

  if (!$response->isOk())
  {
    $app->abort($response->getStatusCode(), 'Error retrieving data: ' . $response->getBody());
  }

  $loader = new Twig_Loader_Filesystem(TWIG_TEMPLATES_DIR);
  $twig = new Twig_Environment($loader);

  $badges = json_decode($response->getBody(), true);

  return $twig->render('badges/index.html', array('badges' => $badges));
});

$app['debug'] = OPEN_BADGES_DEBUG_MODE;
$app->run();
