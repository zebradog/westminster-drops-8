<?php

  namespace Drupal\westminster_security\Routing;

  use Drupal\Core\Routing\RouteSubscriberBase;
  use Symfony\Component\Routing\RouteCollection;

  Class RouteSubscriber extends RouteSubscriberBase {

    const CONFIGURATION_NAME = 'westminster_security.configuration';

    protected $_configuration;

    protected function alterRoutes(RouteCollection $collection) {
      $this->_preventAnonymousNodeAccess($collection);
    }

    protected function _getConfiguration() {
      if (!$this->_configuration) {
        $this->_configuration = \Drupal::config(static::CONFIGURATION_NAME);
      }

      return $this->_configuration;
    }

    protected function _preventAnonymousNodeAccess(RouteCollection $collection) {
      if ($this->_shouldPreventAnonymousNodeAccess()) {
        if ($route = $collection->get('entity.node.canonical')) {
          $route->setRequirement('_role', 'authenticated');
        }
      }
    }

    protected function _shouldPreventAnonymousNodeAccess() {
      return !!$this->_getConfiguration()->get('prevent_anonymous_node_access');
    }

  }
