<?php

  namespace Drupal\westminster_analytics\Form;

  use Drupal\Core\Form\ConfigFormBase;
  use Drupal\Core\Form\FormStateInterface;
  use Drupal\westminster_analytics\ConfigurationHelper;
  use Drupal\westminster_analytics\GoogleHelper;

  Class ConfigurationForm extends ConfigFormBase {

    protected $_configurationHelper;
    protected $_googleHelper;

    public function buildForm(array $form, FormStateInterface $form_state) {
      $googleHelper = $this->_getGoogleHelper();
      $googleHelper->getAuthorizedGoogleClient();

      $configurationHelper = $this->_getConfigurationHelper();
      $hasCredentials = $configurationHelper->hasCredentials();

      if ($configurationHelper->hasAccessToken()) {
        $form['fieldset_access_token'] = array(
          '#type' => 'fieldset',
          '#title' => $this->t('Access Token'),
        );

        $form['fieldset_access_token']['redacted_access_token'] = array(
          '#type' => 'html_tag',
          '#tag' => 'pre',
          '#value' => json_encode($configurationHelper->getRedactedAccessToken(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
        );
      }

      $form['fieldset_credentials'] = array(
        '#type' => 'fieldset',
        '#title' => $this->t('Credentials'),
      );

      if ($hasCredentials) {
        $form['fieldset_credentials']['redacted_credentials'] = array(
          '#type' => 'html_tag',
          '#tag' => 'pre',
          '#value' => json_encode($configurationHelper->getRedactedCredentials(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
        );
      }

      $form['fieldset_credentials']['credentials_file'] = array(
        '#type' => 'file',
        '#title' => $hasCredentials ? $this->t('Overwrite Credentials') : $this->t('Upload Credentials'),
        '#description' => 'Please select the JSON-formatted credentials file for the Google service account associated with this site.',
      );

      if ($hasCredentials) {
        $form['fieldset_credentials']['clear_credentials'] = array(
          '#type' => 'checkbox',
          '#title' => $this->t('Clear Credentials'),
        );
      }

      $form['actions']['submit'] = array(
        '#type' => 'submit',
        '#value' => $this->t('Save Configuration'),
      );

      return $form;
    }

    protected function getEditableConfigNames() {
      return [
        ConfigurationHelper::CONFIGURATION_NAME,
      ];
    }

    public function getFormId() {
      return 'westminster_analytics_configuration';
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
      $configurationHelper = $this->_getConfigurationHelper();

      if (!!$form_state->getValue('clear_credentials')) {
        $configurationHelper->clearCredentials();
      }

      if ($credentialsPath = $form_state->getValue('credentials_file')) {
        $configurationHelper->setCredentialsWithFilePath($credentialsPath);
      }

      $configurationHelper->saveConfiguration();

      drupal_set_message($this->t('The configuration has been successfully saved.'));
    }

    public function validateForm(array &$form, FormStateInterface $form_state) {
      $configurationHelper = $this->_getConfigurationHelper();

      $clearCredentials = !!$form_state->getValue('clear_credentials');
      $uploadedFiles = $this->getRequest()->files->get('files', []);

      if (!empty($uploadedFiles['credentials_file'])) {
        $credentialsFile = $uploadedFiles['credentials_file'];

        if ($credentialsFile->isValid()) {
          $credentialsPath = $credentialsFile->getRealPath();
          $decodedCredentials = json_decode(file_get_contents($credentialsPath), true);

          if ($decodedCredentials) {
            if ($configurationHelper->isValidCredentials($decodedCredentials)) {
              $form_state->setValue('credentials_file', $credentialsPath);
            } else {
              $form_state->setErrorByName('credentials_file', $this->t('Please upload a valid credentials file.'));
            }
          } else {
            $form_state->setErrorByName('credentials_file', $this->t('Could not decode credentials. Please upload a valid JSON file.'));
          }

        } else {
          $form_state->setErrorByName('credentials_file', $this->t('Please upload a valid file.'));
        }

      } elseif (!$clearCredentials) {
        $form_state->setErrorByName('credentials_file', $this->t('Please select a file.'));
      }
    }

    protected function _getConfigurationHelper() {
      if (!$this->_configurationHelper) {
        $this->_configurationHelper = new ConfigurationHelper();
      }

      return $this->_configurationHelper;
    }

    protected function _getGoogleHelper() {
      if (!$this->_googleHelper) {
        $this->_googleHelper = new GoogleHelper();
      }

      return $this->_googleHelper;
    }

  }
