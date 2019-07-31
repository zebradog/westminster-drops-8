<?php
namespace Drupal\westminster_import\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;

class ImportController extends ControllerBase {
  public function importPage() {
    $build['#theme'] = 'westminsterImport';
    return $build;
  }

  public function importSelectPage() {
    $build['#theme'] = 'westminsterImportSelect';
    return $build;
  }

}
