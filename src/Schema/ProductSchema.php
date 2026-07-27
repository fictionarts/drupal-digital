<?php

namespace Drupal\digital\Schema;

use Drupal\node\Entity\Node;
use Drupal\Core\Url;

class ProductSchema {

  protected Node $product;

  public function __construct(Node $product) {

    $this->product = $product;

  }


  public function getId(): string {

    return $this->productUrl();

  }


  public function build(): array {

    $product = $this->product;


    $price = NULL;

    if (
      !$product
        ->get('field_digital_sale_price')
        ->isEmpty()
    ) {

      $price =
        $product
        ->get('field_digital_sale_price')
        ->value;

    }
    elseif (
      !$product
        ->get('field_digital_full_price')
        ->isEmpty()
    ) {

      $price =
        $product
        ->get('field_digital_full_price')
        ->value;

    }



    $currency = "USD";


    if (
      !$product
        ->get('field_digital_currency')
        ->isEmpty()
    ) {

      $currency =
        $product
        ->get('field_digital_currency')
        ->value;

      $currency = str_replace(
        "$",
        "USD",
        $currency
      );

    }



    $schema = [

      "@type"=>"Product",

      "@id"=>$this->productUrl(),

      "name"=>$product->label(),


      "offers"=>[

        "@type"=>"Offer",

        "url"=>$this->buyUrl(),

        "price"=>$price,

        "priceCurrency"=>$currency,

        "availability"=>
          "https://schema.org/InStock"

      ]

    ];



    /*
     * Description
     */

    if (
      !$product
        ->get('field_digital_description')
        ->isEmpty()
    ){

      $schema["description"] =
        strip_tags(
          $product
          ->get('field_digital_description')
          ->value
        );

    }



    /*
     * Image
     */

    if (
      !$product
        ->get('field_digital_product_image_url')
        ->isEmpty()
    ){

      $schema["image"] =
        $product
        ->get('field_digital_product_image_url')
        ->uri;

    }



    /*
     * Brand reference
     */

    if (
      !$product
        ->get('field_digital_brand')
        ->isEmpty()
    ){

      $brand =
        $product
        ->get('field_digital_brand')
        ->entity;


      $schema["brand"] = [

        "@id" =>
          Url::fromRoute(
            'entity.taxonomy_term.canonical',
            [
              'taxonomy_term'=>$brand->id()
            ]
          )
          ->setAbsolute()
          ->toString()
          . "/#brand"

      ];

    }



    /*
     * Category
     */

    if (
      !$product
        ->get('field_digital_category')
        ->isEmpty()
    ){

      $category =
        $product
        ->get('field_digital_category')
        ->entity;


      $schema["category"] =
        $category->label();

    }



    /*
     * Gender
     */

    if (
      !$product
        ->get('field_digital_gender')
        ->isEmpty()
    ){

      $gender =
        $product
        ->get('field_digital_gender')
        ->entity;

      $gender_label = str_replace(
          ['Mens', 'Womens', 'Unisex'],
          ['Male', 'Female', 'Unisex'],
          $gender->label()
      );


      $schema["audience"]=[

        "@type"=>"PeopleAudience",

        "suggestedGender"=>
          $gender_label

      ];

    }


    return $schema;

  }



  private function productUrl(): string {

    return
      Url::fromRoute(
        'entity.node.canonical',
        [
          'node'=>$this->product->id()
        ]
      )
      ->setAbsolute()
      ->toString()
      .
      "#product";

  }



  private function buyUrl(): string {

    if (
      !$this->product
        ->get('field_digital_product_buy_url')
        ->isEmpty()
    ){

      return
        $this->product
        ->get('field_digital_product_buy_url')
        ->uri;

    }

    return '';

  }


}