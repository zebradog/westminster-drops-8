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

      $form['fieldset_manual'] = [
        '#type' => 'fieldset',
        '#title' => $this->t('User Manual'),
      ];

      $form['fieldset_manual']['manual_active'] = [
        '#attributes' => [
          'id' => 'westminster_support_manual_active',
        ],
        '#default_value' => !!$configuration->get('manual.active'),
        '#type' => 'checkbox',
        '#title' => $this->t('Display User Manual'),
      ];

      $visibleWhenManualActive = [
        'visible' => [
          '#westminster_support_manual_active' => [
            'checked' => true,
          ],
        ],
      ];

      $form['fieldset_manual']['manual_title'] = [
        '#default_value' => $configuration->get('manual.title'),
        '#states' => $visibleWhenManualActive,
        '#title' => 'Title',
        '#type' => 'textfield',
      ];

      $form['fieldset_manual']['manual_description'] = [
        '#default_value' => $configuration->get('manual.description'),
        '#states' => $visibleWhenManualActive,
        '#title' => 'Description',
        '#type' => 'textarea',
      ];

      $form['fieldset_manual']['manual_url'] = [
        '#default_value' => $configuration->get('manual.url'),
        '#states' => $visibleWhenManualActive,
        '#title' => 'URL',
        '#type' => 'url',
      ];

      $form['actions']['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('Save Configuration'),
      ];

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

      $configuration->set('manual.active', $form_state->getValue('manual_active'));
      $configuration->set('manual.description', $form_state->getValue('manual_description'));
      $configuration->set('manual.title', $form_state->getValue('manual_title'));
      $configuration->set('manual.url', $form_state->getValue('manual_url'));

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
