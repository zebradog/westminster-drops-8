<?php
namespace Drupal\westminster_preview\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

Class ConfigurationForm extends ConfigFormBase {
  protected $previewableContentTypes = [];

  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('westminster_preview.configuration');

    $previewableService = \Drupal::service('westminster_preview.previewable');
    $this->previewableContentTypes = $previewableService->getAll();

    $form['westminster_preview_base_url'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Default Url'),
      '#default_value' => $config->get('westminster_preview_base_url'),
      '#required' => true,
    );

    $form['westminster_preview_configuration'] = array(
      '#type' => 'vertical_tabs',
      '#title' => $this->t('Settings'),
    );

    foreach ($this->previewableContentTypes as $ct) {
      $id = $ct->id();
      $label = $ct->label();

      $form[$id] = array(
        '#type' => 'details',
        '#title' => $this->t($label),
        '#group' => 'westminster_preview_configuration',
      );
      $form[$id]['label'] = array(
        '#markup' => '<h4>' . $this->t($label) . ' Settings</h4>',
      );
      $form[$id][$id.'_previewable'] = array(
        '#type' => 'checkbox',
        '#title' => $this->t('Previewable'),
        '#default_value' => $config->get($id.'_previewable'),
      );
      $form[$id][$id.'_override_url'] = array(
        '#type' => 'textfield',
        '#title' => $this->t('Override Url'),
        '#default_value' => $config->get($id.'_override_url'),
      );
      $form[$id][$id.'_query_string'] = array(
        '#type' => 'textfield',
        '#title' => $this->t('Query String'),
        '#default_value' => $config->get($id.'_query_string'),
      );
    }

    return parent::buildForm($form, $form_state);
  }

  protected function getEditableConfigNames() {
    return [
      'westminster_preview.configuration',
    ];
  }

  public function getFormId() {
    return 'westminster_preview.configuration';
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $configFactory = $this->configFactory->getEditable($this->getEditableConfigNames()[0]);

    $configFactory->set('westminster_preview_base_url', $form_state->getValue('westminster_preview_base_url'));
    foreach ($this->previewableContentTypes as $ct) {
      $id = $ct->id();
      $label = $ct->label();
      $queryString = $form_state->getValue($id.'_query_string');

      $configFactory->set($id.'_previewable', $form_state->getValue($id.'_previewable'));
      $configFactory->set($id.'_override_url', $form_state->getValue($id.'_override_url'));
      $configFactory->set($id.'_query_string', $queryString);
    }

    $configFactory->save();

    parent::submitForm($form, $form_state);
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }
}
