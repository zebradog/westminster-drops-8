<?php

  namespace Drupal\westminster_support\Controller;

  use Drupal\Core\Controller\ControllerBase;

  Class DefaultController extends ControllerBase {

    public function default() {
      $config = \Drupal::config('westminster_support.configuration');

      $build = [
        '#config' => $config->get(),
        '#theme' => 'westminster-support--default',
      ];

      \Drupal::service('renderer')->addCacheableDependency($build, $config);

      return $build;
    }

  }
