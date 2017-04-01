<?php

/**
 * @file
 * Contains \Drupal\phparch\Controller\WeatherController.
 */

namespace Drupal\phparch\Service;
use \GuzzleHttp\ClientInterface;

class WeatherService
{
  protected $client;
  protected $api_key;

  function __construct (ClientInterface $client, $api_key) {
    $this->client = $client;
    $this->api_key = $api_key;
  }

  public function setApiKey($api_key) {
    $this->api_key = $api_key;
  }

  public function fetchByZipcode($zipcode) {

      /* @var \GuzzleHttp\Message\Response $result */
      $request = $this->client->get(
        'http://api.openweathermap.org/data/2.5/weather',
        [
          'query' => [
            'appid' => $this->api_key,
            'q' => $zipcode . ',USA',
            'units' => 'imperial',
            'cnt' => 7
          ]
        ]
      );

      if (200 == $request->getStatusCode()) {
        return json_decode($request->getBody());
      }
    }

}