<?php

  namespace Drupal\westminster_support\Controller;

  use Drupal\Core\Config\Config;
  use Drupal\Core\Controller\ControllerBase;
  use Symfony\Component\HttpFoundation\Request;

  Class DefaultController extends ControllerBase {

    /**
     * Name of the module configuration object.
     */
    const CONFIGURATION_NAME = 'westminster_security.configuration';

    /**
     * Instance of the module configuration object.
     * @see _getConfiguration()
     * @var Config
     */
    protected $_configuration;

    public function default(Request $request) {
      $configuration = $this->_getConfiguration();

      return [
        '#theme' => 'westminster-support--default',
      ];
    }

    /**
     * Returns the module configuration object.
     * @return Config
     */
    protected function _getConfiguration() {
      if (!$this->_configuration) {
        $this->_configuration = \Drupal::config(static::CONFIGURATION_NAME);
      }

      return $this->_configuration;
    }

  }
