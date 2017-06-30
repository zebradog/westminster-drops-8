<?php

  namespace Drupal\westminster_analytics;

  use Drupal\westminster_analytics\ConfigurationHelper;

  Class GoogleHelper {

    static protected $_configurationHelper;

    public function authorizeGoogleClient(\Google_Client $googleClient, $forceRefresh = false) {
      $configurationHelper = $this->_getConfigurationHelper();

      try {
        if (!$configurationHelper->isAccessTokenExpired() && !$forceRefresh) {
          $googleClient->setAccessToken($configurationHelper->getAccessToken());
          return true;
        } elseif ($configurationHelper->hasCredentials()) {
          $googleClient->fetchAccessTokenWithAssertion();

          if ($accessToken = $googleClient->getAccessToken()) {
            if ($configurationHelper->isValidAccessToken($accessToken)) {
              return true;
            }
          }
        }
      } catch (Exception $e) {}

      return false;
    }

    public function configureGoogleClient(\Google_Client $googleClient) {
      $configurationHelper = $this->_getConfigurationHelper();

      try {
        $googleClient->setScopes([\Google_Service_Analytics::ANALYTICS_READONLY]);

        if ($configurationHelper->hasCredentials()) {
          $googleClient->setAuthConfig($configurationHelper->getCredentials());
          return true;
        }
      } catch (Exception $e) {}

      return false;
    }

    public function getAuthorizedGoogleClient($clientConfiguration = array(), $forceRefresh = false) {
      $googleClient = $this->getConfiguredGoogleClient($clientConfiguration);

      $this->authorizeGoogleClient($googleClient, $forceRefresh);

      return $googleClient;
    }

    public function getConfiguredGoogleClient($clientConfiguration = array()) {
      $googleClient = $this->getGoogleClient($clientConfiguration);

      $this->configureGoogleClient($googleClient);

      return $googleClient;
    }

    public function getGoogleClient($clientConfiguration = array()) {
      return new \Google_Client($clientConfiguration);
    }

    public function getInjectableServerAuthorization() {
      $googleClient = $this->getConfiguredGoogleClient();

      if ($this->authorizeGoogleClient($googleClient)) {
        $accessToken = $googleClient->getAccessToken();

        return array(
          'access_token' => $accessToken['access_token'],
        );
      }

      return [];
    }

    protected function _getConfigurationHelper() {
      if (!static::$_configurationHelper) {
        static::$_configurationHelper = new ConfigurationHelper();
      }

      return static::$_configurationHelper;
    }

  }
