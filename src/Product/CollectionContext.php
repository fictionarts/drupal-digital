<?php

namespace Drupal\digital\Product;


use Drupal\Core\Path\CurrentPathStack;
use Drupal\path_alias\AliasManagerInterface;


class CollectionContext {


  protected CurrentPathStack $currentPath;

  protected AliasManagerInterface $aliasManager;



  public function __construct(
    CurrentPathStack $currentPath,
    AliasManagerInterface $aliasManager
  ){

    $this->currentPath = $currentPath;

    $this->aliasManager = $aliasManager;

  }




  public function getContext($entity = NULL): array {


    /*
     * Brand taxonomy page
     */

    if(
      $entity &&
      $entity->getEntityTypeId() === 'taxonomy_term'
    ){

      return [

        "type"=>"brand",

        "brand"=>$entity->id(),

        "title" =>
          $entity->label() .
          " Clothing Collection",

        "description" =>
          "Browse " .
          $entity->label() .
          " clothing, apparel, and streetwear styles."

      ];

    }



    $alias =
      trim(
        $this->aliasManager
          ->getAliasByPath(
            $this->currentPath->getPath()
          ),
        '/'
      );



    switch($alias){

      case "home":

        return [

          "type"=>"all",

          "brand"=>NULL,

          "title"=>
            "Urban Clothing Collection",

          "description"=>
            "Browse popular urban clothing, streetwear, and apparel."

        ];

      case "shop":

        return [

          "type"=>"all",

          "brand"=>NULL,

          "title"=>
            "Urban Clothing Collection",

          "description"=>
            "Browse the latest urban clothing, streetwear, and apparel."

        ];



      case "mens":

        return [

          "type"=>"mens",

          "brand"=>NULL,

          "title"=>
            "Men's Urban Clothing Collection",

          "description"=>
            "Shop men's urban clothing, streetwear, and fashion apparel."

        ];



      case "womens":

        return [

          "type"=>"womens",

          "brand"=>NULL,

          "title"=>
            "Women's Urban Clothing Collection",

          "description"=>
            "Shop women's urban clothing, streetwear, and fashion apparel."

        ];


    }



    return [];

  }


}