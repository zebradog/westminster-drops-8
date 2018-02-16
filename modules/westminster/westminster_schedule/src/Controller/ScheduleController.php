<?php
namespace Drupal\westminster_schedule\Controller;

use Drupal\Core\Controller\ControllerBase;

class ScheduleController extends ControllerBase {
  public function schedulePage() {
    die('DURPAL TOST');
    $element = array(
      '#markup' => t('Hello, world'),
    );
    return $element;
  }
}
