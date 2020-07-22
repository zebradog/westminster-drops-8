<?php
namespace Drupal\westminster_preview\Services;

class WestminsterPreviewService {
  public function getAll() {
    $contentTypes = \Drupal::service('entity.manager')->getStorage('node_type')->loadMultiple();

    return $contentTypes;
  }

  public function isPreviewable($id) {
    $node = \Drupal::entityTypeManager()->getStorage('node')->load($id);
    if ($node != NULL) {
      $type_name = $node->type->entity->label();
      $config = \Drupal::config('westminster_preview.configuration');
      if ($config->get($type_name.'_previewable')) {
        return true;
      }
    }
    return false;
  }
}
