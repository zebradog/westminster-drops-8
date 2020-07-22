<?php

namespace Drupal\flysystem_s3\Plugin\Field\FieldWidget;

/**
 * Plugin implementation of the 'file_generic' widget.
 *
 * @FieldWidget(
 *   id = "file_generic",
 *   label = @Translation("S3File"),
 *   field_types = {
 *     "s3file"
 *   }
 * )
 */
class S3FileWidget extends \Drupal\file\Plugin\Field\FieldWidget\FileWidget {

}
