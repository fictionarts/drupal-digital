<?php

namespace Drupal\digital\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Provides a 'Hello' Block.
 */

#[Block(
  id: "digital_copyright_block",
  admin_label: new TranslatableMarkup("Digital copyright block"),
  category: new TranslatableMarkup("Digital")
)]

class DigitalCopyrightBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = \Drupal::config('system.site');

    $site_name = $config->get('name');

    return [
      '#theme' => 'digital_copyright_block',
      '#sitename_string' => $site_name,
      '#date_string' => date("Y"),
    ];
  }
}
