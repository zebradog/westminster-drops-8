<?php

  namespace Drupal\westminster_support\Form;

  use Drupal\Core\Config\Config;
  use Drupal\Core\Form\ConfigFormBase;
  use Drupal\Core\Form\FormStateInterface;

  /**
   * Configuration form for the module.
   */
  Class ConfigurationForm extends ConfigFormBase {

    /**
     * Name of the module configuration object.
     */
    const CONFIGURATION_NAME = 'westminster_support.configuration';

    /**
     * Instance of the module configuration object.
     * @see _getConfiguration()
     * @var Config
     */
    protected $_configuration;

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
      $configuration = $this->_getConfiguration();

      // TODO: Form fields

      $form['actions']['submit'] = array(
        '#type' => 'submit',
        '#value' => $this->t('Save Configuration'),
      );

      return $form;
    }

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames() {
      return [
        static::CONFIGURATION_NAME,
      ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
      return 'westminster_support_configuration';
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
      $configuration = $this->_getConfiguration();

      $configuration->save();

      $messenger = \Drupal::messenger();
      $messenger->addStatus($this->t('The configuration has been successfully saved.'));
    }

    /**
     * Returns the module configuration object.
     * @return Config
     */
    protected function _getConfiguration() {
      if (!$this->_configuration) {
        $this->_configuration = $this->config(static::CONFIGURATION_NAME);
      }

      return $this->_configuration;
    }

  }
