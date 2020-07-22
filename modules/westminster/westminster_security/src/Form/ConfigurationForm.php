<?php

  namespace Drupal\westminster_security\Form;

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
    const CONFIGURATION_NAME = 'westminster_security.configuration';

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

      $form['fieldset_routing']['prevent_anonymous_taxonomy_term_access'] = array(
        '#type' => 'checkbox',
        '#title' => $this->t('Prevent Anonymous Taxonomy Term Access'),
        '#default_value' => !!$configuration->get('prevent_anonymous_taxonomy_term_access'),
        '#description' => 'Require an authenticated session to access canonical taxonomy term URLs (e.g. <code>/taxonomy/term/1</code>). Does not affect access via views or REST exports.'
      );

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
      return 'westminster_security_configuration';
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
      $configuration = $this->_getConfiguration();
      $shouldRebuildRoutes = $this->_shouldRebuildRoutes($configuration, $form_state);

      $configuration->set('prevent_anonymous_node_access', !!$form_state->getValue('prevent_anonymous_node_access'));
      $configuration->set('prevent_anonymous_taxonomy_term_access', !!$form_state->getValue('prevent_anonymous_taxonomy_term_access'));

      $configuration->save();

      if ($shouldRebuildRoutes) {
        $this->_rebuildRoutes();
      }

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

    /**
     * Commands the router service to rebuild its routes.
     */
    protected function _rebuildRoutes() {
      \Drupal::service('router.builder')->rebuild();
    }

    /**
     * Returns whether any changed values should trigger a route rebuild.
     * @param Config $config The current configuration values
     * @param FormStateInterface $form_state The newly submitted values
     * @return boolean
     */
    protected function _shouldRebuildRoutes(Config $configuration, FormStateInterface $form_state) {
      if ($configuration->get('prevent_anonymous_node_access') != $form_state->getValue('prevent_anonymous_node_access')) {
        return true;
      }

      if ($configuration->get('prevent_anonymous_taxonomy_term_access') != $form_state->getValue('prevent_anonymous_taxonomy_term_access')) {
        return true;
      }

      return false;
    }

  }
