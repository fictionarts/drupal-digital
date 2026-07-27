<?php

namespace Drupal\digital\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\views\Views;
use Symfony\Component\HttpFoundation\Response;

class CatalogController extends ControllerBase {

  public function load() {

    $view = Views::getView('digital_product_index');

    $view->setDisplay('block_1');

    //$view->execute();

    //$output = $view->render();

    $build = $view->buildRenderable();

    $build['#attached']['library'][] = 'views/views.ajax';
    $build['#attached']['library'][] = 'core/drupal.ajax';

    return $build;

    //return new Response(
    //  \Drupal::service('renderer')->render($output)
    //);

  }

}