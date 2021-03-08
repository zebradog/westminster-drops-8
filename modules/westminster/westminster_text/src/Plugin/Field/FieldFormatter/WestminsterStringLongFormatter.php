<?php

namespace Drupal\westminster_text\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\StringFormatter;

/**
 * Plugin implementation of the 'westminster_string_long_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "westminster_string_long_formatter",
 *   label = @Translation("Westminster Text (plain)"),
 *   field_types = {
 *     "westminster_string_long"
 *   }
 * )
 */
class WestminsterStringLongFormatter extends StringFormatter {

  /**
   * {@inheritdoc}
   */
   public function viewElements(FieldItemListInterface $items, $langcode) {
     $elements = [];

     $maxLength = $this->getFieldSetting('max_length');

     if ($maxLength > 0) {
       foreach ($items as $delta => $item) {
         // The text value has no text format assigned to it, so the user input
         // should equal the output, including newlines.
         $elements[$delta] = [
           '#type' => 'inline_template',
           '#template' => '{{ value|nl2br[:' . $maxLength . '] }}',
           '#context' => ['value' => $item->value],
         ];
       }
     } else {
       foreach ($items as $delta => $item) {
         // The text value has no text format assigned to it, so the user input
         // should equal the output, including newlines.
         $elements[$delta] = [
           '#type' => 'inline_template',
           '#template' => '{{ value|nl2br }}',
           '#context' => ['value' => $item->value],
         ];
       }
     }

     return $elements;
   }

}
