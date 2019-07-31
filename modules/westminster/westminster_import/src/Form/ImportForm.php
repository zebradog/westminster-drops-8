<?php
namespace Drupal\westminster_import\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Drupal\node\Entity\Node;

class ImportForm extends FormBase {
  public function buildForm(array $form, FormStateInterface $form_state, $type = NULL) {
    $this->type = $type;

    $form['csv'] = array(
      '#type' => 'managed_file',
      '#title' => $this->t('CSV File'),
      '#upload_location' => 'public://temp',
      '#upload_validators' => [
        'file_validate_extensions' => ['csv'],
      ],
      '#multiple' => FALSE,
      '#required' => TRUE,
    );
    $form['flush_existing'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Delete all existing content of type: '.$this->type),
    );
    $form['actions_download_current'] = array(
      '#type' => 'actions',
    );
    $form['actions_download_current']['download'] = array(
      '#type' => 'link',
      '#title' => $this->t('Download current CSV file.'),
      '#url' => \Drupal\Core\Url::fromRoute('westminster_import.import_download.type', ['type' => $this->type]),
    );
    $form['actions_download_template'] = array(
      '#type' => 'actions',
    );
    $form['actions_download_template']['download_template'] = array(
      '#type' => 'link',
      '#title' => $this->t('Download template CSV file.'),
      '#url' => \Drupal\Core\Url::fromRoute('westminster_import.import_download_template.type', ['type' => $this->type]),
    );
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Import'),
      '#button_type' => 'primary',
    );

    return $form;
  }

  public function getEditableConfigNames() {
    return [];
  }

  public function getFormId() {
    return 'westminster_import.import_form';
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $messenger = \Drupal::messenger();
    $csvId = $form_state->getValue('csv')[0];
    $flushExisting = $form_state->getValue('flush_existing');

    //TODO if delete checked, delete all existing nodes
    //TODO add merge mode
    if ($flushExisting) {
      $nids = \Drupal::entityQuery('node')->condition('type', $this->type)->execute();
      $nodes = \Drupal\node\Entity\Node::loadMultiple($nids);
      foreach($nodes as $n) {
        $n->delete();
      }
    }
    if (isset($csvId)) {
      //TODO lots of error handling, validation, reporting, etc.
      $csv = File::load($csvId);
      $csvUri = $csv->getFileUri();
      $csvPath = file_create_url($csvUri);
      $row = 0;
      $csvHeaders = [];
      if (($handle = fopen($csvPath, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
          $r = '';
          $num = count($data);
          $node = Node::create(['type' => $this->type]);
          for ($c = 0; $c < $num; $c++) {
            $r .= $data[$c] . ', ';
            if ($row === 0) {
              array_push($csvHeaders, $data[$c]);
            } else {
              if (isset($csvHeaders[$c]) && isset($data[$c]) && !empty($data[$c])) {
                $node->set($csvHeaders[$c], $data[$c]);
              }
            }
          }

          //TODO check each node's field for requirements?
          if ($row != 0) {
            $node->status = 1;
            $node->enforceIsNew();
            $node->save();
            //$messenger->addMessage("Node with nid " . $node->id() . " saved!\n", $messenger::TYPE_STATUS);
          }

          $row++;
        }
        fclose($handle);
      } else {
        $messenger->addMessage('Failed to load CSV.', $messenger::TYPE_ERROR);
      }
    } else {
      $messenger->addMessage("Couldn't import file. Unable to access public file storage.", $messenger::TYPE_ERROR);
    }
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {

  }
}
