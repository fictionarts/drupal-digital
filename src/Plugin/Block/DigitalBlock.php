<?php

namespace Drupal\digital\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Provides a 'Hello' Block.
 */

#[Block(
  id: "digital_block",
  admin_label: new TranslatableMarkup("Digital block"),
  category: new TranslatableMarkup("Digital")
)]

class DigitalBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#theme' => 'digital_block',
      '#custom_data' => ['age' => '31', 'DOB' => '2 May 2000'],
      '#custom_string' => 'Hello Block!',
    ];
  }
}
