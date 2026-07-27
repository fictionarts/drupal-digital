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

    $view = Views::getView('digital_product_index');

    if (!$view) {
      return [];
    }

    switch ($type) {

      case 'brand':
        $display = 'block_2';
        $arguments = [$brandTid];
        break;

      case 'mens':
        $display = 'block_3';
        $arguments = [];
        break;

      case 'womens':
        $display = 'block_4';
        $arguments = [];
        break;

      default:
        $display = 'block_1';
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