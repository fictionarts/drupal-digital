<?php

namespace Drupal\digital\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\views\Render\ViewsRenderPipelineMarkup;

/**
 * Provides a 'Page Title Header' Block.
 */

#[Block(
  id: "digital_title_block",
  admin_label: new TranslatableMarkup("Digital title block"),
  category: new TranslatableMarkup("Digital")
)]

class DigitalTitleBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

//    $request = \Drupal::request();
/***
$request = \Drupal::request();
$route_match = \Drupal::routeMatch();
$returned_title = \Drupal::service('title_resolver')->getTitle($request, $route_match->getRouteObject());

$custom_title = '';


switch(gettype($returned_title)) {
	case 'object':
		if(get_class($returned_title) == 'Drupal\views\Render\ViewsRenderPipelineMarkup') {
			$custom_title = ViewsRenderPipelineMarkup::create($returned_title);
		}
		break;
	case 'array':
		$custom_title = $returned_title['#markup'];
		break;
	case 'string':
		$custom_title = $returned_title;
		break;
}
***/

/***
if(TRUE) {
  dpm(gettype($returned_title));
  dpm(get_class($returned_title));
} else if(!empty($returned_title['#markup'])) {
  $custom_title = $returned_title['#markup'];
} else if(TRUE) {
  
}
***/


//dpm($custom_title);

//    dpm($request);

//    $node = $request->get('node');
//    $term = $request->get('taxonomy_term');

//    dpm($node);
//    dpm($term);

    //$request = \Drupal::request();

    //dpm($request);

    //dpm($request->get('node'));
    //dpm($request->get('entityTypeId'));

    //$foobar = \Drupal::routeMatch()->getRouteObject();

    $request = \Drupal::request();
    //dpm($request);

    $node = $request->get('node');

    $term = $request->get('taxonomy_term');

    $description = '';

    if(!empty($node)) {
      $foobar = $node->get('field_digital_page_description');
      dpm($foobar);

      $digital_page_description = $node->get('field_digital_page_description')->getValue();
      dpm($digital_page_description);
      if(!empty($digital_page_description[0]['value'])) {
        $description = $digital_page_description[0]['value'];
      }

    } else if(!empty($term)) {
	dpm($term);
    }

    return [
      '#theme' => 'digital_title_block',
      '#digital_description_string' => $description,
      '#custom_string' => 'Hello world block',
    ];
  }
}
