<?php

  namespace Drupal\westminster_support\Controller;

  use Drupal\Core\Controller\ControllerBase;

  Class DefaultController extends ControllerBase {

    public function default() {
      return [
        '#theme' => 'westminster-support--default',
      ];
    }

  }
