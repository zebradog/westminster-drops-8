<?php
namespace Drupal\westminster_schedule\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;

class ScheduleController extends ControllerBase {
  public function schedulePage() {
    $build['#theme'] = 'scheduling';
    return $build;
  }
  public function scheduleSelectPage() {
    $build['#theme'] = 'schedulingSelect';
    return $build;
  }
  public function ajax(Request $request) {
    if ($request->request->get('action') == 'create') {
      $node;
      if ($request->request->get('nid') <= 0) {
        $node = Node::create([
          'type' => 'scheduled_content_2'
        ]);
      } else {
        $node = Node::load($request->request->get('nid'));
      }
      $node->setTitle($request->request->get('title'));
      $node->set('field_scheduled_item', $request->request->get('target_id'));
      $node->set('field_schedule_end_time', $request->request->get('end'));
      $node->set('field_schedule_start_time', $request->request->get('start'));
      $node->save();
      return new JsonResponse(['status' => 'created']);
    }
    else if ($request->request->get('action') == 'delete') {
      return new JsonResponse(['status' => 'deleted']);
    }
    return new JsonResponse(['status' => 'nothing']);
  }
}
