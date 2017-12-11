<?php

  namespace Drupal\westminster_debug\Form;

  use Drupal\Core\Form\ConfigFormBase;
  use Drupal\Core\Form\FormStateInterface;

  Class RoutingForm extends ConfigFormBase {

    public function buildForm(array $form, FormStateInterface $form_state) {
      $form['actions']['submit'] = array(
        '#type' => 'submit',
        '#value' => $this->t('Rebuild Routes'),
      );

      return $form;
    }

    protected function getEditableConfigNames() {
      return [];
    }

    public function getFormId() {
      return 'westminster_debug_routing';
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
      \Drupal::service('router.builder')->rebuild();

      drupal_set_message($this->t('Routes have been successfully rebuilt.'));
    }

    public function validateForm(array &$form, FormStateInterface $form_state) {

    }

  }
