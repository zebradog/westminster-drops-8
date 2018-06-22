<?php

  namespace Drupal\westminster_analytics;

  use GuzzleHttp\Exception\BadResponseException;

  /**
   * Provides helpers for initializing, authorizing, and working with Google_Client instances.
   * @todo Consider converting this to a service.
   */
  Class GoogleHelper {

    /**
     * Returns a usable Google_Client instance.
     * @param mixed[] $credentials
     * @param mixed[] $accessToken
     * @return \Google_Client
     */
    public function createClient($credentials = [], $accessToken = []) {
      $googleClient = new \Google_Client([]);

      try {
        $googleClient->setAuthConfig($credentials);
        $googleClient->setAccessToken($accessToken);
      } catch (Exception $e) {}

      return $googleClient;
    }

    /**
     * Employs a Google_Client instance to fetch an access_token for the configured credentials.
     * @return mixed[]
     */
    public function fetchAccessToken($credentials = []) {
      $googleClient = $this->_createClient();

      try {
        $googleClient->setAuthConfig($credentials);
        $googleClient->fetchAccessTokenWithAssertion();
      } catch (BadResponseException $e) {
      } catch (Exception $e) {}

      return $googleClient->getAccessToken();
    }

    /**
     * Returns a new Google_Client instance.
     * @return Google_Client
     */
    protected function _createClient() {
      $googleClient = new \Google_Client([]);

      $googleClient->setScopes([
        \Google_Service_Analytics::ANALYTICS_READONLY,
      ]);

      return $googleClient;
    }

  }
