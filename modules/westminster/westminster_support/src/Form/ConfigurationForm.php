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
        '#title' => $this->t('Title'),
        '#type' => 'textfield',
      ];

      $form['fieldset_manual']['manual_description'] = [
        '#default_value' => $configuration->get('manual.description'),
        '#states' => $visibleWhenManualActive,
        '#title' => $this->t('Description'),
        '#type' => 'textarea',
      ];

      $form['fieldset_manual']['manual_url'] = [
        '#default_value' => $configuration->get('manual.url'),
        '#states' => $visibleWhenManualActive,
        '#title' => $this->t('URL'),
        '#type' => 'url',
      ];

      $form['fieldset_info'] = [
        '#type' => 'fieldset',
        '#title' => $this->t('Contact Information'),
      ];

      $form['fieldset_info']['info_active'] = [
        '#attributes' => [
          'id' => 'westminster_support_info_active',
        ],
        '#default_value' => !!$configuration->get('info.active'),
        '#type' => 'checkbox',
        '#title' => $this->t('Display Contact Information'),
      ];

      $visibleWhenInfoActive = [
        'visible' => [
          '#westminster_support_info_active' => [
            'checked' => true,
          ],
        ],
      ];

      $form['fieldset_info']['info_title'] = [
        '#default_value' => $configuration->get('info.title'),
        '#states' => $visibleWhenInfoActive,
        '#title' => $this->t('Title'),
        '#type' => 'textfield',
      ];

      $form['fieldset_info']['info_description'] = [
        '#default_value' => $configuration->get('info.description'),
        '#states' => $visibleWhenInfoActive,
        '#title' => $this->t('Description'),
        '#type' => 'textarea',
      ];

      $form['fieldset_info']['info_email'] = [
        '#default_value' => $configuration->get('info.email'),
        '#states' => $visibleWhenInfoActive,
        '#title' => $this->t('Email'),
        '#type' => 'email',
      ];

      $form['fieldset_info']['info_phone'] = [
        '#default_value' => $configuration->get('info.phone'),
        '#states' => $visibleWhenInfoActive,
        '#title' => $this->t('Phone'),
        '#type' => 'tel',
      ];

      $form['fieldset_form'] = [
        '#type' => 'fieldset',
        '#title' => $this->t('Contact Form'),
      ];

      $form['fieldset_form']['form_active'] = [
        '#attributes' => [
          'id' => 'westminster_support_form_active',
        ],
        '#default_value' => !!$configuration->get('form.active'),
        '#type' => 'checkbox',
        '#title' => $this->t('Display Contact Form'),
      ];

      $visibleWhenFormActive = [
        'visible' => [
          '#westminster_support_form_active' => [
            'checked' => true,
          ],
        ],
      ];

      $form['fieldset_form']['form_title'] = [
        '#default_value' => $configuration->get('form.title'),
        '#states' => $visibleWhenFormActive,
        '#title' => $this->t('Title'),
        '#type' => 'textfield',
      ];

      $form['fieldset_form']['form_description'] = [
        '#default_value' => $configuration->get('form.description'),
        '#states' => $visibleWhenFormActive,
        '#title' => $this->t('Description'),
        '#type' => 'textarea',
      ];

      $form['fieldset_form']['form_email_active'] = [
        '#attributes' => [
          'id' => 'westminster_support_form_email_active',
        ],
        '#default_value' => !!$configuration->get('form.email.active'),
        '#states' => $visibleWhenFormActive,
        '#type' => 'checkbox',
        '#title' => $this->t('Send Email'),
      ];

      $visibleWhenFormEmailActive = [
        'visible' => [
          '#westminster_support_form_email_active' => [
            'checked' => true,
          ],
          '#westminster_support_form_email_active' => [
            'checked' => true,
          ],
        ],
      ];

      $form['fieldset_form']['form_email_recipient'] = [
        '#default_value' => $configuration->get('form.email.recipient'),
        '#states' => $visibleWhenFormEmailActive,
        '#title' => $this->t('Email Recipient'),
        '#type' => 'email',
      ];

      $form['fieldset_form']['form_email_subjectPrefix'] = [
        '#default_value' => $configuration->get('form.email.subjectPrefix'),
        '#states' => $visibleWhenFormEmailActive,
        '#title' => $this->t('Subject Prefix'),
        '#type' => 'textfield',
      ];

      $form['fieldset_form']['form_success'] = [
        '#default_value' => $configuration->get('form.success'),
        '#states' => $visibleWhenFormActive,
        '#title' => $this->t('Success Message'),
        '#type' => 'textfield',
      ];

      $form['fieldset_form']['form_error'] = [
        '#default_value' => $configuration->get('form.error'),
        '#states' => $visibleWhenFormActive,
        '#title' => $this->t('Error Message'),
        '#type' => 'textfield',
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

      $configuration->set('form.active', $form_state->getValue('form_active'));
      $configuration->set('form.email.active', $form_state->getValue('form_email_active'));
      $configuration->set('form.email.recipient', $form_state->getValue('form_email_recipient'));
      $configuration->set('form.email.subjectPrefix', $form_state->getValue('form_email_subjectPrefix'));
      $configuration->set('form.description', $form_state->getValue('form_description'));
      $configuration->set('form.error', $form_state->getValue('form_error'));
      $configuration->set('form.success', $form_state->getValue('form_success'));
      $configuration->set('form.title', $form_state->getValue('form_title'));

      $configuration->set('info.active', $form_state->getValue('info_active'));
      $configuration->set('info.description', $form_state->getValue('info_description'));
      $configuration->set('info.email', $form_state->getValue('info_email'));
      $configuration->set('info.phone', $form_state->getValue('info_phone'));
      $configuration->set('info.title', $form_state->getValue('info_title'));

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
