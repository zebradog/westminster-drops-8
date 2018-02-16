<?php
namespace Drupal\westminster_schedule\Controller;

use Drupal\Core\Controller\ControllerBase;

class ScheduleController extends ControllerBase {
  public function schedulePage() {
    $build['#theme'] = 'scheduling';
    return $build;
  }
  public function scheduleSelectPage() {
    $build['#theme'] = 'schedulingSelect';
    return $build;
  }
}
