<?php

  namespace Drupal\westminster_schedule\Form;

  use Drupal\Core\Form\ConfigFormBase;
  use Drupal\Core\Form\FormStateInterface;

  Class ConfigurationForm extends ConfigFormBase {


    public function buildForm(array $form, FormStateInterface $form_state) {
      $config = $this->config('westminster_schedule.configuration');

      $contentTypes = \Drupal::service('entity.manager')->getStorage('node_type')->loadMultiple();

      foreach($contentTypes as $contentType) {
        $id = $contentType->id();
        $label = $contentType->label();
        if ($id != 'scheduled_content_2') {
          $form[$id] = array(
            '#type' => 'checkbox',
            '#title' => $this->t($label),
            '#default_value' => $config->get($id)
          );
        }
      }

      return parent::buildForm($form, $form_state);
    }

    protected function getEditableConfigNames() {
      return [
        'westminster_schedule.configuration',
      ];
    }

    public function getFormId() {
      return 'westminster_schedule_configuration';
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
      $configFactory = $this->configFactory->getEditable('westminster_schedule.configuration');
      $contentTypes = \Drupal::service('entity.manager')->getStorage('node_type')->loadMultiple();

      foreach($contentTypes as $contentType) {
        $id = $contentType->id();
        $label = $contentType->label();
        if ($id != 'scheduled_content_2') {
          $configFactory->set($id, $form_state->getValue($id));
        }
      }
      $configFactory->save();

      parent::submitForm($form, $form_state);
    }

    public function validateForm(array &$form, FormStateInterface $form_state) {

    }

  }
