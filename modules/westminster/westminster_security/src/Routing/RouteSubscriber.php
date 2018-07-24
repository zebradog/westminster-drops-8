<?php

  namespace Drupal\westminster_security\Routing;

  use Drupal\Core\Routing\RouteSubscriberBase;
  use Symfony\Component\Routing\Route;
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

      if ($this->_shouldPreventAnonymousTaxonomyTermAccess()) {
        $this->_preventAnonymousTaxonomyTermAccess($collection);
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
        $this->_requireAuthenticatedUser($route);
      }
    }

    /**
     * Prevents anonymous access to canonical taxonomy term URLs (e.g. /taxonomy/term/{taxonomy_term}).
     * @param RouteCollection $collection
     */
    protected function _preventAnonymousTaxonomyTermAccess(RouteCollection $collection) {
      if ($route = $collection->get('entity.taxonomy_term.canonical')) {
        $this->_requireAuthenticatedUser($route);
      }
    }

    /**
     * Requires an authenticated user role to access $route.
     * @param Route $route
     */
    protected function _requireAuthenticatedUser(Route $route) {
      $route->setRequirement('_role', 'authenticated');
    }

    /**
     * Returns whether the module has been configured to prevent anonymous access to canonical node URLs (e.g. /node/{node}).
     * @return boolean
     */
    protected function _shouldPreventAnonymousNodeAccess() {
      return (bool) $this->_getConfiguration()->get('prevent_anonymous_node_access');
    }

    /**
     * Returns whether the module has been configured to prevent anonymous access to canonical taxonomy term URLs (e.g. /taxonomy/term/{taxonomy_term}).
     * @return boolean
     */
    protected function _shouldPreventAnonymousTaxonomyTermAccess() {
      return (bool) $this->_getConfiguration()->get('prevent_anonymous_taxonomy_term_access');
    }

  }
