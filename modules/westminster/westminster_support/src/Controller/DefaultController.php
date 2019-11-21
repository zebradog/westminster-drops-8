<?php

  namespace Drupal\westminster_support\Controller;

  use Drupal\Core\Controller\ControllerBase;

  Class DefaultController extends ControllerBase {

    public function default() {
      return [
        '#config' => \Drupal::config('westminster_support.configuration')->get(),
        '#theme' => 'westminster-support--default',
      ];
    }

  }
