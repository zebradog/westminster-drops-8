<?php

namespace Drupal\westminster_text\Plugin\Field\FieldWidget;

use Drupal\Core\Field\Plugin\Field\FieldWidget\StringTextfieldWidget;

/**
 * Plugin implementation of the 'westminster_string_long_widget' widget.
 *
 * @FieldWidget(
 *   id = "westminster_string_long_widget",
 *   label = @Translation("Westminster Text (plain)"),
 *   field_types = {
 *     "westminster_string_long"
 *   }
 * )
 */

class WestminsterStringLongWidget extends StringTextfieldWidget {

}
