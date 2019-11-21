<?php

  namespace Drupal\westminster_support\Form;

  use \Drupal\Core\Form\FormBase;
  use \Drupal\Core\Form\FormStateInterface;

  Class ContactForm extends FormBase {

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
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
      // TODO: Send email
      // TODO: Send notification
    }

    public function validateForm(array &$form, FormStateInterface $form_state) {
      // TODO: Check required fields
    }

  }
