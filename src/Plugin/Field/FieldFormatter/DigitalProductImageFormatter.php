<?php

namespace Drupal\digital\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'Random_default' formatter.
 *
 * @FieldFormatter(
 *   id = "digital_product_image",
 *   label = @Translation("Digital Product Image"),
 *   field_types = {
 *     "link"
 *   }
 * )
 */
class DigitalProductImageFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $summary[] = $this->t('Displays the random string.');
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];

    foreach ($items as $delta => $item) {

      //dpm($item);
      // Render each element as markup.

      $output = '<img src="' . $item->uri . '" class="img-fluid" />';

      $element[$delta] = ['#markup' => $output];
    }

    return $element;
  }

}
