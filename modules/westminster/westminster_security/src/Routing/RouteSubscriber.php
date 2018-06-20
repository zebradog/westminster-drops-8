<?php

  namespace Drupal\westminster_security\Routing;

  use Drupal\Core\Routing\RouteSubscriberBase;
  use Symfony\Component\Routing\RouteCollection;

  /**
   * Performs various security enhancements when routes are built.
   */
  Class RouteSubscriber extends RouteSubscriberBase {

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

    /**
     * {@inheritdoc}
     */
    protected function alterRoutes(RouteCollection $collection) {
      if ($this->_shouldPreventAnonymousNodeAccess()) {
        $this->_preventAnonymousNodeAccess($collection);
      }
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

    /**
     * Prevents anonymous access to canonical node URLs (e.g. /node/{id}).
     * @param RouteCollection $collection
     */
    protected function _preventAnonymousNodeAccess(RouteCollection $collection) {
      if ($route = $collection->get('entity.node.canonical')) {
        $route->setRequirement('_role', 'authenticated');
      }
    }

    /**
     * Returns whether the module has been configured to prevent anonymous access
     * to canonical node URLs (e.g. /node/{id}).
     * @return boolean
     */
    protected function _shouldPreventAnonymousNodeAccess() {
      return !!$this->_getConfiguration()->get('prevent_anonymous_node_access');
    }

  }
