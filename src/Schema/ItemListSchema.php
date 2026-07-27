<?php

namespace Drupal\digital\Schema;


class ItemListSchema {


  protected array $products;

  protected string $url;

  protected string $title;


  public function __construct(
    array $products,
    string $url,
    string $title
  ){

    $this->products=$products;

    $this->url=$url;

    $this->title=$title;

  }



  public function build(): array {


    $items=[];

    $position=1;


    foreach($this->products as $product){


      $schema =
        new ProductSchema($product);



      $items[]=[

        "@type"=>"ListItem",

        "position"=>$position++,

        "item"=>[

          "@id"=>$schema->getId()

        ]

      ];


    }



    return [

      "@type"=>"ItemList",

      "@id"=>$this->url."/#products",

      "name"=>"Products",

      "numberOfItems"=>count($items),

      "itemListOrder"=>
        "https://schema.org/ItemListOrderDescending",

      "itemListElement"=>$items

    ];


  }


}