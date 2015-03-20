<?php

namespace OpenBadges\Frontend;

class Client
{
  private $client = null;
  private $response = null;

  public function __construct()
  {
    $this->client = new \Zend\Http\Client();
  }

  public function get($path)
  {
    $url = OPEN_BADGES_BACKEND_URL . $path;

    $request = new \Zend\Http\Request();
    $request->setUri($url);

    $this->response = $this->client->send($request);

    return $this->response;
  }

  public function getBodyArray()
  {
    if ($json = $this->response->getBody())
    {
      return json_decode($json, true);
    }

    return null;
  }
}
