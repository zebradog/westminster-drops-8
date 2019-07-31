<?php
namespace Drupal\westminster_import\Services;

class WestminsterImportService {

  private $importableContentTypes = [];
  private $unimportableContentTypes = [];

  public function getFields($id, $ignore = TRUE) {
    $fieldEntityManager = \Drupal::service('entity_field.manager');
    $fields = $fieldEntityManager->getFieldDefinitions('node', $id);
    if (!$ignore) {
      return $fields;
    }
    $ignoreFields = $this->getIgnoreFields();
    $validFields = [];
    foreach($fields as $field) {
      if (!in_array($field->getName(), $ignoreFields)) {
        array_push($validFields, $field);
      }
    }
    return $validFields;
  }

  public function sortImportableContentTypes() {
    $contentTypes = \Drupal::service('entity.manager')->getStorage('node_type')->loadMultiple();
    $this->importableContentTypes = [];
    $unimportableContentTypes = [];

    foreach($contentTypes as $contentType) {
      if ($this->isImportableContentType($contentType)) {
        array_push($this->importableContentTypes, $contentType);
      } else {
        array_push($unimportableContentTypes, $contentType);
      }
    }

    return [$this->importableContentTypes, $unimportableContentTypes];
  }

  protected function isImportableContentType($contentType) {
    $id = $contentType->id();
    $validTypes = $this->getValidFields();
    $ignoreFields = $this->getIgnoreFields();
    //TODO this is hardcoded to 'node' - grab dynamically
    $fields = $this->getFields($id);
    foreach($fields as $field) {
      $fieldName = $field->getName();
      if (!in_array($fieldName, $ignoreFields)) {
        $fieldType = $field->getType();
        if (!in_array($fieldType, $validTypes)) {
          return false;
        }
      }
    }
    return true;
  }

  public function getIgnoreFields() {
    return [
      'nid', 'uuid', 'vid', 'langcode', 'type', 'revision_timestamp',
      'revision_uid', 'revision_log', 'status', 'uid', 'created', 'changed',
      'promote', 'sticky', 'default_langcode', 'revision_default',
      'revision_translation_affected', 'path'
    ];
  }

  protected function getValidFields() {
    return [
      'boolean', 'datetime', 'email', 'entity_reference', 'link', 'list_float', 'list_integer',
      'list_string', 'decimal', 'float', 'integer', 'text', 'text_long',
      'text_with_summary', 'string', 'string_long', 'timestamp'
    ];
  }
}
