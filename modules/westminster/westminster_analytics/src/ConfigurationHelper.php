<?php

  namespace Drupal\westminster_analytics;

  use Drupal\westminster_analytics\GoogleHelper;

  Class ConfigurationHelper {

    const CONFIGURATION_KEY_CREDENTIALS = 'credentials';
    const CONFIGURATION_KEY_TOKEN_ACCESS = 'token_access';

    const CONFIGURATION_NAME = 'westminster_analytics.configuration';

    static protected $_configuration;
    static protected $_googleHelper;

    public function clearAccessToken($saveConfiguration = false) {
      return $this->setAccessToken(NULL, $saveConfiguration);
    }

    public function clearCredentials($saveConfiguration = false) {
      return $this->setCredentials(NULL, $saveConfiguration);
    }

    public function getAccessToken($forceRefresh = false) {
      if ($forceRefresh || $this->isExpiredAccessToken($this->_getAccessToken())) {
        $this->refreshAccessToken();
      }

      return $this->_getAccessToken();
    }

    public function getConfiguration() {
      if (!static::$_configuration) {
        static::$_configuration = \Drupal::configFactory()->getEditable(static::CONFIGURATION_NAME);
      }

      return static::$_configuration;
    }

    public function getCredentials() {
      return json_decode($this->getConfiguration()->get(static::CONFIGURATION_KEY_CREDENTIALS), true);
    }

    public function getRedactedAccessToken($redactionString = 'REDACTED') {
      return $this->_redactArrayKeys($this->getAccessToken(), [
        'access_token',
        'refresh_token',
      ]);
    }

    public function getRedactedCredentials($redactionString = 'REDACTED') {
      return $this->_redactArrayKeys($this->getCredentials(), [
        'client_secret',
        'private_key',
      ]);
    }

    public function hasAccessToken() {
      return !!$this->getAccessToken();
    }

    public function hasCredentials() {
      return !!$this->getCredentials();
    }

    public function isAccessTokenExpired($threshold = 30) {
      return $this->isExpiredAccessToken($this->_getAccessToken(), $threshold);
    }

    public function isExpiredAccessToken($accessToken, $threshold = 30) {
      if (is_array($accessToken)) {
        if (isset($accessToken['created']) && isset($accessToken['expires_in'])) {
          $tokenExpiry = $accessToken['created'];
          $tokenExpiry += $accessToken['expires_in'];
          $tokenExpiry -= $threshold;

          return $tokenExpiry < time();
        }
      } elseif ($decodedAccessToken = json_decode($accessToken, true)) {
        return $this->isExpiredAccessToken($decodedAccessToken);
      }

      return true;
    }

    public function isValidAccessToken($accessToken = array()) {
      if (is_array($accessToken)) {
        return !array_diff([
          'access_token',
        ], array_keys($accessToken));
      } elseif ($decodedAccessToken = json_decode($accessToken, true)) {
        return $this->isValidAccessToken($decodedAccessToken);
      }

      return false;
    }

    public function isValidCredentials($credentials = array()) {
      if (is_array($credentials)) {
        return !array_diff([
          'client_id',
          'type',
        ], array_keys($credentials));
      } elseif ($decodedCredentials = json_decode($accessToken, true)) {
        return $this->isValidCredentials($decodedCredentials);
      }

      return false;
    }

    public function isValidCredentialsFile($credentialsFilePath = '') {
      if (file_exists($credentialsFilePath)) {
        if ($credentialsFileContents = file_get_contents($credentialsFilePath)) {
          return $this->isValidCredentialsJson($credentialsFileContents);
        }
      }

      return false;
    }

    public function isValidCredentialsJson($credentialsJson = '') {
      if ($credentials = json_decode($credentialsJson, true)) {
        return $this->isValidCredentials($credentials);
      }

      return false;
    }

    public function refreshAccessToken() {
      $googleClient = $this->_getGoogleHelper()->getAuthorizedGoogleClient(array(), true);

      if ($accessToken = $googleClient->getAccessToken()) {
        if ($this->isValidAccessToken($accessToken)) {
          $this->setAccessToken($accessToken, true);
        }
      }

      return $this;
    }

    public function saveConfiguration() {
      $this->getConfiguration()->save();

      return $this;
    }

    public function setAccessToken($accessToken = NULL, $saveConfiguration = false) {
      if (!(is_null($accessToken) || is_string($accessToken))) {
        $accessToken = json_encode($accessToken);
      }

      $this->getConfiguration()->set(static::CONFIGURATION_KEY_TOKEN_ACCESS, $accessToken);

      if ($saveConfiguration) {
        $this->saveConfiguration();
      }

      return $this;
    }

    public function setCredentials($credentials = NULL, $saveConfiguration = false) {
      if (!(is_null($credentials) || is_string($credentials))) {
        $credentials = json_encode($credentials);
      }

      $this->getConfiguration()->set(static::CONFIGURATION_KEY_CREDENTIALS, $credentials);
      $this->clearAccessToken();

      if ($saveConfiguration) {
        $this->saveConfiguration();
      }

      return $this;
    }

    public function setCredentialsWithFilePath($credentialsPath = '', $saveConfiguration = false) {
      if ($this->isValidCredentialsFile($credentialsPath)) {
        $this->setCredentials(file_get_contents($credentialsPath), $saveConfiguration);
      }

      return $this;
    }

    protected function _getAccessToken() {
      return json_decode($this->getConfiguration()->get(static::CONFIGURATION_KEY_TOKEN_ACCESS), true);
    }

    protected function _getGoogleHelper() {
      if (!$this->_googleHelper) {
        $this->_googleHelper = new GoogleHelper();
      }

      return $this->_googleHelper;
    }

    protected function _redactArrayKeys(array $array, array $keys, $redactionString = 'REDACTED') {
      foreach($keys as $key) {
        if (isset($array[$key])) {
          $array[$key] = $redactionString;
        }
      }

      return $array;
    }

  }
