<?php

  namespace Drupal\westminster_schedule\Form;

  use Drupal\Core\Form\ConfigFormBase;
  use Drupal\Core\Form\FormStateInterface;
  use Drupal\westminster_schedule\ConfigurationHelper;

  Class ConfigurationForm extends ConfigFormBase {


    public function buildForm(array $form, FormStateInterface $form_state) {
      return $form;
    }

    protected function getEditableConfigNames() {
      return [
        ConfigurationHelper::CONFIGURATION_NAME,
      ];
    }

    public function getFormId() {
      return 'westminster_schedule_configuration';
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {

    }

    public function validateForm(array &$form, FormStateInterface $form_state) {

    }

  }
