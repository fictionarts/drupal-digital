<?php

namespace Drupal\digital\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'Random_default' formatter.
 *
 * @FieldFormatter(
 *   id = "digital_product_image_card",
 *   label = @Translation("Digital Product Image Card"),
 *   field_types = {
 *     "link"
 *   }
 * )
 */
class DigitalProductImageCardFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $summary[] = $this->t('Display a image as a card top image');
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];

    $parentEntity = $items->getParent()->getEntity();
    $parent_title = $parentEntity->getTitle();;

    foreach ($items as $delta => $item) {

      //dpm($item);
      // Render each element as markup.

      $output = '<img src="' . $item->uri . '" loading="lazy" class="card-img-top" alt="' . $parent_title . '" />';

      $element[$delta] = ['#markup' => $output];
    }

    return $element;
  }

}
