<?php
namespace Drupal\westminster_import\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\Response;

class DownloadController extends ControllerBase {

  public function downloadCSV($type = NULL) {
    if ($type != NULL) {
      //TODO create download file here
      $importService = \Drupal::service('westminster_import.importable');
      $fields = $importService->getFields($type);
      $csvHeaders = [];
      foreach($fields as $f) {
        $fieldName = $f->getName();
        array_push($csvHeaders, $this->t($fieldName));
      }

      $csv = implode(',', $csvHeaders);
      $csvRows = [$csv];

      $nids = \Drupal::entityQuery('node')->condition('type', $type)->execute();
      $nodes = \Drupal\node\Entity\Node::loadMultiple($nids);
      foreach($nodes as $n) {
        $a = [];
        foreach($fields as $f) {
          $item = $n->get($f->getName())->getValue();
          $v = '';
          if (!empty($item)) {

            //TODO entity references use different scheme
            $v = $item['0']['value'];
            if (empty($v)) {
              $v = $n->get($f->getName())->getString();
            }
            $v = str_replace('"', '\"', $v);
            if (strpos($v, ',') !== false) {
              $v = '"' . $v . '"';
            }
          }
          array_push($a, $v);
        }
        array_push($csvRows, implode(',', $a));
      }

      $csv = implode("\n", $csvRows);

      $response = new Response($csv);
      $response->headers->set('Content-Type', 'text/csv');
      $response->headers->set('Content-Disposition', 'attachment; filename="'.$type.'.csv";');

      return $response;
    }

    return [];
  }

  public function downloadCSVTemplate($type = NULL) {
    if ($type != NULL) {
      $importService = \Drupal::service('westminster_import.importable');
      $fields = $importService->getFields($type);
      $csvHeaders = [];
      foreach($fields as $f) {
        $fieldName = $f->getName();
        array_push($csvHeaders, $this->t($fieldName));
      }

      $csv = implode(',', $csvHeaders);

      $response = new Response($csv);
      $response->headers->set('Content-Type', 'text/csv');
      $response->headers->set('Content-Disposition', 'attachment; filename="'.$type.'_template.csv";');

      return $response;
    }

    return [];
  }

}
