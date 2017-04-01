<?php
/**
 * @file
 * Contains \Drupal\phparch\Controller\WeatherController.
 * Created by PhpStorm.
 * User: Bree
 * Date: 3/25/17
 * Time: 11:26 AM
 */

namespace Drupal\phparch\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Utility\LinkGeneratorInterface;
use GuzzleHttp\Client;

/**
 * Provides route responses for the phparch  module.
 */
class WeatherController extends ControllerBase {

  public function zip () {
    // check $_GET for a zip code
    if (!isset($_GET['zipcode'])) {
      return $this->redirect('phparch.weather');
    }
    // filter zipcode
    $zipcode = filter_var($_GET['zipcode'], FILTER_SANITIZE_STRING);

    // get weather
    try {
      /*      var_dump($weather);
      var_dump($weather->coord);
      var_dump($weather->weather);
      var_dump($weather->base);
      var_dump($weather->main);
      var_dump($weather->visibility);
      var_dump($weather->wind);
      var_dump($weather->clouds);
      var_dump($weather->dt);
      var_dump($weather->sys);
      die();
      $element = array('#markup' => 'whatever');*/

      /*      Replace this now that we have refactored to use a service
      $weather = $this->fetchWeather($zipcode);*/

      /** @var \Drupal\phparch\Service\WeatherService $weatherService */
      $weatherService = \Drupal::service('phparch.weather_service');
      $config = $this->config('phparch.settings');
      /*      var_dump($config->get('api_key'));
      $weatherService->setApiKey('02fed267d4374fb2e31bfe416278c3c6');*/
      $weatherService->setApiKey($config->get('api_key'));
      $weather = $weatherService->fetchByZipCode($zipcode);

      // use our theme function to render twig template
      $element = array(
        '#theme' => 'phparch_current_weather',
        '#location' => $weather->name,
        '#temperature' => $weather->main->temp,
        '#description' => $weather->weather[0]->description,
        '#zipcode' => $zipcode,
        '#zipurl' => '/phparch/zip',
      );
      $element['#cache']['max-age'] = 0;
      return $element;

    } catch (\Exception $e) {

      drupal_set_message(t('Could not fetch weather, please try again later:' . $e->getMessage()), 'error');
      return $this->redirect('phparch.weather');

    }
  }

  //moved out of this class and into a service
  /*  public function fetchWeather($zipcode) {
    $client = new Client();

    // @var \GuzzleHttp\Message\Response $result
    $request = $client->get(
      'http://api.openweathermap.org/data/2.5/weather',
      [
        'query' => [
          'appid' => '02fed267d4374fb2e31bfe416278c3c6',
          'q' => $zipcode . ',USA',
          'units' => 'imperial',
          'cnt' => 7
        ]
      ]
    );

    if (200 == $request->getStatusCode()) {
      return json_decode($request->getBody());
    }
  }*/

}