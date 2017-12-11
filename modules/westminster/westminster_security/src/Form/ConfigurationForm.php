<?php

  namespace Drupal\westminster_security\Form;

  use Drupal\Core\Config\Config;
  use Drupal\Core\Form\ConfigFormBase;
  use Drupal\Core\Form\FormStateInterface;

  Class ConfigurationForm extends ConfigFormBase {

    const CONFIGURATION_NAME = 'westminster_security.configuration';

    protected $_configuration;

    public function buildForm(array $form, FormStateInterface $form_state) {
      $configuration = $this->_getConfiguration();

      $form['fieldset_routing'] = array(
        '#type' => 'fieldset',
        '#title' => $this->t('Routing'),
      );

      $form['fieldset_routing']['description'] = array(
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#value' => 'Improve the default security of core dynamic routes.',
      );

      $form['fieldset_routing']['prevent_anonymous_node_access'] = array(
        '#type' => 'checkbox',
        '#title' => $this->t('Prevent Anonymous Node Access'),
        '#default_value' => !!$configuration->get('prevent_anonymous_node_access'),
        '#description' => 'Require an authenticated session to access canonical node URLs (e.g. <code>/node/1</code>). Does not affect access via views or REST exports.'
      );

      $form['actions']['submit'] = array(
        '#type' => 'submit',
        '#value' => $this->t('Save Configuration'),
      );

      return $form;
    }

    protected function getEditableConfigNames() {
      return [
        static::CONFIGURATION_NAME,
      ];
    }

    public function getFormId() {
      return 'westminster_security_configuration';
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
      $configuration = $this->_getConfiguration();
      $shouldRebuildRoutes = $this->_shouldRebuildRoutes($configuration, $form_state);

      $configuration->set('prevent_anonymous_node_access', !!$form_state->getValue('prevent_anonymous_node_access'));

      $configuration->save();

      if ($shouldRebuildRoutes) {
        \Drupal::service('router.builder')->rebuild();
      }

      drupal_set_message($this->t('The configuration has been successfully saved.'));
    }

    public function validateForm(array &$form, FormStateInterface $form_state) {

    }

    protected function _getConfiguration() {
      if (!$this->_configuration) {
        $this->_configuration = $this->config(static::CONFIGURATION_NAME);
      }

      return $this->_configuration;
    }

    protected function _shouldRebuildRoutes(Config $configuration, FormStateInterface $form_state) {
      if ($configuration->get('prevent_anonymous_node_access') != $form_state->getValue('prevent_anonymous_node_access')) {
        return true;
      }

      return false;
    }

  }
