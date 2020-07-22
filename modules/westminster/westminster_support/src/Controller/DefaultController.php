<?php

  namespace Drupal\westminster_support\Controller;

  use Drupal\Core\Controller\ControllerBase;

  Class DefaultController extends ControllerBase {

    public function default() {
      $config = \Drupal::config('westminster_support.configuration');
      $form = \Drupal::formBuilder()->getForm('Drupal\westminster_support\Form\ContactForm');

      $build = [
        '#config' => $config->get(),
        '#form' => $form,
        '#theme' => 'westminster-support--default',
      ];

      \Drupal::service('renderer')->addCacheableDependency($build, $config);

      return $build;
    }

  }
