<?php

  namespace Drupal\westminster_analytics;

  use Drupal\westminster_analytics\GoogleHelper;

  /**
   *
   * @todo Consider converting this to a service.
   */
  Class ConfigurationHelper {

    /**
     * Key for the stored access token.
     */
    const CONFIG_KEY_ACCESSTOKEN = 'access_token';

    /**
     * Key for the stored credentials.
     */
    const CONFIG_KEY_CREDENTIALS = 'credentials';

    /**
     * Name of the module configuration object.
     */
    const CONFIG_NAME = 'westminster_analytics.configuration';

    /**
     * Editable configuration instance.
     * @see _getConfiguration()
     * @var Config
     */
    static protected $_configuration;

    /**
     * Cached instance of GoogleHelper.
     * @see _getGoogleHelper()
     * @var GoogleHelper
     */
    static protected $_googleHelper;

    /**
     * Clears the access token.
     * Shortcut for `setAccessToken(NULL, $saveConfiguration)`.
     * @param boolean $saveConfiguration
     */
    public function clearAccessToken($saveConfiguration = false) {
      return $this->setAccessToken(NULL, $saveConfiguration);
    }

    /**
     * Clears the credentials.
     * Shortcut for `setCredentials(NULL, $saveConfiguration)`.
     * @param boolean $saveConfiguration
     */
    public function clearCredentials($saveConfiguration = false) {
      return $this->setCredentials(NULL, $saveConfiguration);
    }

    /**
     * Returns the stored access token.
     * If the token is expired, then it will be refreshed via {@link refreshAccessToken()}.
     * @param boolean $forceRefresh
     *   Forces the token to refresh regardless of its expiry
     * @return mixed[]
     * @see _getAccessToken()
     */
    public function getAccessToken($forceRefresh = false) {
      if ($forceRefresh || $this->isExpiredAccessToken($this->_getAccessToken())) {
        $this->refreshAccessToken();
      }

      return $this->_getAccessToken();
    }

    /**
     * Returns the stored credentials from the configuration object.
     * @return mixed[]
     */
    public function getCredentials() {
      return $this->_getConfiguration()->get(static::CONFIG_KEY_CREDENTIALS);
    }

    /**
     * Returns a redacted access token for human eyes.
     * Never output the raw contents of {@link getAccessToken()}.
     * @return mixed[]
     */
    public function getRedactedAccessToken($redactionString = 'REDACTED') {
      return $this->_redactArrayKeys($this->getAccessToken(), [
        'access_token',
        'refresh_token',
      ]);
    }

    /**
     * Returns redacted credentials for human eyes.
     * Never output the raw contents of {@link getCredentials()}.
     * @return mixed[]
     */
    public function getRedactedCredentials($redactionString = 'REDACTED') {
      return $this->_redactArrayKeys($this->getCredentials(), [
        'client_secret',
        'private_key',
      ]);
    }

    /**
     * Returns whether the provided access token has expired.
     * @param mixed[] $accessToken
     * @param number $threshold
     *   Time in seconds to offset the expiry to prevent stale tokens on the frontend.
     * @return boolean
     */
    public function isExpiredAccessToken($accessToken = [], $threshold = 60) {
      if (!is_array($accessToken) || !isset($accessToken['created']) || !isset($accessToken['expires_in'])) {
        return true;
      }

      $tokenExpiry = $accessToken['created'];
      $tokenExpiry += $accessToken['expires_in'];
      $tokenExpiry -= $threshold;

      return $tokenExpiry < time();
    }

    /**
     * Returns whether the provided access token is valid (enough).
     * @param mixed[] $accessToken
     * @return boolean
     */
    public function isValidAccessToken($accessToken = []) {
      return is_array($accessToken) && isset($accessToken['access_token']);
    }

    /**
     * Returns whether the provided credentials are valid.
     * @param mixed[] $credentials
     * @return boolean
     */
    public function isValidCredentials($credentials = []) {
      return is_array($credentials) && isset($credentials['client_id']) && isset($credentials['type']);
    }

    /**
     * Returns whether the stored access token is set and valid.
     * @return boolean
     */
    public function hasValidAccessToken() {
      return $this->isValidAccessToken($this->_getAccessToken());
    }

    /**
     * Returns whether the stored credentials are set and valid.
     * @return boolean
     */
    public function hasValidCredentials($credentials = []) {
      return $this->isValidCredentials($this->getCredentials());
    }

    /**
     * Refreshes the stored access token using the stored credentials.
     * @return ConfigurationHelper $this
     */
    public function refreshAccessToken() {
      $accessToken = $this->_getGoogleHelper()->fetchAccessToken($this->getCredentials());

      if ($this->isValidAccessToken($accessToken)) {
        $this->setAccessToken($accessToken, true);
      } else {
        $this->clearAccessToken(true);
      }

      return $this;
    }

    /**
     * Saves all changes to the configuration object.
     * @return ConfigurationHelper $this
     */
    public function saveConfiguration() {
      $this->_getConfiguration()->save();

      return $this;
    }

    /**
     * Sets the stored access token.
     * @param mixed[] $accessToken
     *   Pass NULL to clear the stored access token.
     * @param boolean $saveConfiguration
     *   Call {@link saveConfiguration()} after setting.
     * @return ConfigurationHelper $this
     */
    public function setAccessToken($accessToken = NULL, $saveConfiguration = false) {
      $this->_getConfiguration()->set(static::CONFIG_KEY_ACCESSTOKEN, $accessToken);

      if ($saveConfiguration) {
        $this->saveConfiguration();
      }

      return $this;
    }

    /**
     * Sets the stored credentials.
     * @param mixed[] $credentials
     *   Pass NULL to clear the stored credentials.
     * @param boolean $saveConfiguration
     *   Call {@link saveConfiguration()} after setting.
     * @return ConfigurationHelper $this
     */
    public function setCredentials($credentials = NULL, $saveConfiguration = false) {
      $this->_getConfiguration()->set(static::CONFIG_KEY_CREDENTIALS, $credentials);
      $this->clearAccessToken();

      if ($saveConfiguration) {
        $this->saveConfiguration();
      }

      return $this;
    }

    /**
     * Returns the stored access token from the configuration object.
     * @return mixed[]
     */
    protected function _getAccessToken() {
      return $this->_getConfiguration()->get(static::CONFIG_KEY_ACCESSTOKEN);
    }

    /**
     * Returns an editable configuration instance.
     * @return Config
     */
    protected function _getConfiguration() {
      if (!static::$_configuration) {
        static::$_configuration = \Drupal::configFactory()->getEditable(static::CONFIG_NAME);
      }

      return static::$_configuration;
    }

    /**
     * Returns a cached instance of GoogleHelper.
     * @return GoogleHelper
     */
    protected function _getGoogleHelper() {
      if (!static::$_googleHelper) {
        static::$_googleHelper = new GoogleHelper();
      }

      return static::$_googleHelper;
    }

    /**
     * Replaces values with keys `$keys` in `$array` with `$redactionString` if they exist.
     * @param mixed[] $array
     * @param mixed[] $keys
     * @param string $redactionString
     * @return mixed[]
     */
    protected function _redactArrayKeys(array $array, array $keys, $redactionString = 'REDACTED') {
      foreach($keys as $key) {
        if (isset($array[$key])) {
          $array[$key] = $redactionString;
        }
      }

      return $array;
    }

  }
