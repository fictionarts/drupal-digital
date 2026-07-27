<?php

namespace Drupal\digital\Product;

use Drupal\views\Views;

class ProductCollection {

  /**
   * Load products from an existing View.
   */
  public function getProducts(
    string $type = 'all',
    ?int $brandTid = NULL,
    int $limit = 12,
    int $page = 0
  ): array {

    $view = Views::getView('digital_products');

    if (!$view) {
      return [];
    }

    switch ($type) {

      case 'brand':
        $display = 'brand_products';
        $arguments = [$brandTid];
        break;

      case 'mens':
        $display = 'mens_products';
        $arguments = [];
        break;

      case 'womens':
        $display = 'womens_products';
        $arguments = [];
        break;

      default:
        $display = 'all_products';
        $arguments = [];

    }

    $view->setDisplay($display);

    $view->setArguments($arguments);

    $view->setItemsPerPage($limit);

    $view->setCurrentPage($page);

    $view->execute();

    $products = [];

    foreach ($view->result as $row) {

      if (empty($row->_entity)) {
        continue;
      }

      $products[] = $row->_entity;

    }

    return $products;

  }

}