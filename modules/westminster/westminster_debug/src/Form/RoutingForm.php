<?php

  namespace Drupal\westminster_debug\Form;

  use Drupal\Core\Form\FormBase;
  use Drupal\Core\Form\FormStateInterface;

  /**
   * Provides actions for debugging routes.
   */
  Class RoutingForm extends FormBase {

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
      $form['actions']['submit'] = array(
        '#type' => 'submit',
        '#value' => $this->t('Rebuild Routes'),
      );

      return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
      return 'westminster_debug_routing';
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
      $this->_rebuildRoutes();

      drupal_set_message($this->t('Routes have been successfully rebuilt.'));
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {

    }

    /**
     * Commands the router service to rebuild its routes.
     */
    protected function _rebuildRoutes() {
      \Drupal::service('router.builder')->rebuild();
    }

  }
