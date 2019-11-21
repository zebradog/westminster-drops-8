<?php

  namespace Drupal\westminster_support\Form;

  use \Drupal\Core\Form\FormBase;
  use \Drupal\Core\Form\FormStateInterface;

  /**
   * Contact form that appears on the main page.
   */
  Class ContactForm extends FormBase {

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

      $form['subject'] = [
        '#title' => $this->t('Subject'),
        '#type' => 'textfield',
      ];

      $form['body'] = [
        '#title' => $this->t('Body'),
        '#type' => 'textarea',
      ];

      $form['actions']['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('Send'),
      ];

      return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
      return 'westminster_support_contact';
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
      $configuration = $this->_getConfiguration();

      // TODO: Send email
      // TODO: Send notification
    }

    public function validateForm(array &$form, FormStateInterface $form_state) {
      // TODO: Check required fields
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
