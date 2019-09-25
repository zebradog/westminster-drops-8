<?php
namespace Drupal\westminster_preview\Controller;

use Drupal\Core\Controller\ControllerBase;

class WestminsterPreviewController extends ControllerBase {
  public function previewPage() {
    $build['#theme'] = 'westminsterPreview';
    return $build;
  }
}
