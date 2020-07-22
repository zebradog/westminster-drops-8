<?php

namespace Drupal\flysystem_s3\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'file_url_plain' formatter.
 *
 * @FieldFormatter(
 *   id = "file_url_plain",
 *   label = @Translation("URL to file"),
 *   field_types = {
 *     "file",
 *     "s3file"
 *   }
 * )
 */
class UrlPlainFormatter extends \Drupal\file\Plugin\Field\FieldFormatter\FileFormatterBase {

  /**
   * {@inheritdoc}
   * Copied from parent
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = array();

    foreach ($this->getEntitiesToView($items, $langcode) as $delta => $file) {
      $elements[$delta] = array(
        '#markup' => file_url_transform_relative(file_create_url($file->getFileUri())),
        '#cache' => array(
          'tags' => $file->getCacheTags(),
        ),
      );
    }

    return $elements;
  }

}
