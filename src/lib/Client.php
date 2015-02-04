<?php

namespace UoMCS\OpenBadges\Frontend;

class Client
{
  public static function get($path)
  {
    $url = OPEN_BADGES_BACKEND_URL . $path;

    $client = new \Zend\Http\Client();

    $request = new \Zend\Http\Request();
    $request->setUri($url);

    return $client->send($request);
  }
}
