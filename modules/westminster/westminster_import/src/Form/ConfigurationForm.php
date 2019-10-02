<?php
namespace Drupal\westminster_import\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

Class ConfigurationForm extends ConfigFormBase {
  protected $importableContentTypes = [];

  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('westminster_import.configuration');

    $importableService = \Drupal::service('westminster_import.importable');

    $this->importableContentTypes = $importableService->sortImportableContentTypes();
    $unimportableContentTypes = $this->importableContentTypes[1];
    $this->importableContentTypes = $this->importableContentTypes[0];

    $form['westminster_import_importable_header'] = array(
      '#markup' => '<h4>Available Options</h4>'
    );

    if (count($this->importableContentTypes) === 0) {
      $form['westminster_import_no_options'] = array(
        '#markup' => '<p>Sorry, no valid importable content types.</p>'
      );
    } else {
      foreach ($this->importableContentTypes as $ct) {
        $id = $ct->id();
        $label = $ct->label();

        $form[$id] = array(
          '#type' => 'checkbox',
          '#title' => $this->t($label),
          '#default_value' => $config->get($id)
        );
      }
    }

    if (count($unimportableContentTypes) > 0) {
      $form['westminster_import_unimportable_header'] = array(
        '#markup' => '<h4>Unavailable Content Types</h4>'
      );

      foreach ($unimportableContentTypes as $ct) {
        $id = $ct->id();
        $label = $ct->label();

        $form[$id] = array(
          '#markup' => '<p class="westminster-import--unimportable">&mdash;&nbsp;&nbsp;'.$this->t($label).'</p>',
        );
      }
    }

    return parent::buildForm($form, $form_state);
  }

  protected function getEditableConfigNames() {
    return [
      'westminster_import.configuration',
    ];
  }

  public function getFormId() {
    return 'westminster_import.configuration';
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $configFactory = $this->configFactory->getEditable($this->getEditableConfigNames()[0]);

    foreach($this->importableContentTypes as $ct) {
      $id = $ct->id();
      $label = $ct->label();

      $configFactory->set($id, $form_state->getValue($id));
    }
    $configFactory->save();

    parent::submitForm($form, $form_state);
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }
}
