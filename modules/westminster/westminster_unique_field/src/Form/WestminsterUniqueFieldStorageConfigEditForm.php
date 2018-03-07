<?php

namespace Drupal\westminster_unique_field\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\field\Entity\FieldConfig;
use Drupal\field_ui\FieldUI;
use Drupal\field_ui\Form\FieldStorageConfigEditForm;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WestminsterUniqueFieldStorageConfigEditForm extends FieldStorageConfigEditForm {

  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    $form['westminster_unique'] = $this->getWestminsterUniqueForm();
    return $form;
  }

  public function buildForm(array $form, FormStateInterface $form_state, $field_config = NULL) {
    if ($field_config) {
      $field = Fieldconfig::load($field_config);
      $form_state->set('field_config', $field);

      $form_state->set('entity_type_id', $field->getTargetEntityTypeId());
      $form_state->set('bundle', $field->getTargetBundle());
    }
    return parent::buildForm($form, $form_state);
  }

  protected function getWestminsterUniqueForm() {
    $form = [
      '#parents' => [],
      '#type' => 'boolean',
      '#title' => $this->t('Check if this field should be unique.'),
      '#element_validate' => ['::validateUnique'],
      '#default_value' => 'FALSE'
    ];

    return $form;
  }

  public function validateUnique(array &$element, FormStateInterface $form_state) {

  }

  public function buildEntity(array $form, FormStateInterface $form_state) {
    $form_state->setValue('westminster_unique', $form_state->getValue('westminster_unique'));

    return parent::buildEntity($form, $form_state);
  }
}
