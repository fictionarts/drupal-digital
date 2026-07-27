<?php

/**
 * @file
 * Provides functionality for the Digital module.
 *
 * @copyright Copyright (C) 2026 Adrian Zalewski
 * @license GPL-2.0-or-later
 */

namespace Drupal\digital\Schema;


use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Path\CurrentPathStack;
use Drupal\Core\Url;
use Drupal\path_alias\AliasManagerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\File\FileUrlGeneratorInterface;
use Drupal\Core\Theme\ThemeManagerInterface;
//use Drupal\Core\Breadcrumb\BreadcrumbManager;
use Drupal\Core\Breadcrumb\BreadcrumbManagerInterface;
//use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\digital\Product\ProductCollection;
use Drupal\digital\Product\CollectionContext;

class SchemaGenerator {


  protected $routeMatch;
  protected $pathCurrent;
  protected $aliasManager;
  protected $entityManager;
  protected $fileUrl;
  protected $themeManager;
  protected $request;
  protected ProductCollection $productCollection;
  protected CollectionContext $collectionContext;
  //protected $breadcrumbManager;
  //protected BreadcrumbManager $breadcrumbManager;
//  protected ConfigFactoryInterface $configFactory;
  protected $current_url;

  public function __construct(
    RouteMatchInterface $routeMatch,
    CurrentPathStack $pathCurrent,
    AliasManagerInterface $aliasManager,
    EntityTypeManagerInterface $entityManager,
    FileUrlGeneratorInterface $fileUrl,
    ThemeManagerInterface $themeManager,
    RequestStack $request,
    ProductCollection $productCollection,
    CollectionContext $collectionContext
    //BreadcrumbManager $breadcrumbManager
    //ConfigFactoryInterface $config_factory
  ) {

    $this->routeMatch = $routeMatch;
    $this->pathCurrent = $pathCurrent;
    $this->aliasManager = $aliasManager;
    $this->entityManager = $entityManager;
    $this->fileUrl = $fileUrl;
    $this->themeManager = $themeManager;
    $this->request = $request;
    //$this->breadcrumbManager = $breadcrumbManager;
    //$this->configFactory = $config_factory;

    $this->productCollection = $productCollection;
    $this->collectionContext = $collectionContext;

    $this->setCurrentUrl();
  }



  public function build(): array {

    //$site_name = $this->getSiteName();
    $site_name = "My Urban Clothing";


    $graph = [];


    $url = $this->current_url;


    /*
     * Core Organization
     */
    $graph[] = $this->organization();

    /*
     * Website
     */
    $graph[] = [
      "@type"=> "WebSite",
      "@id"=> $this->baseUrl()."/#website",
      "url"=> $this->baseUrl() . "/",
      "name"=> $site_name,
      "publisher"=>[
        "@id"=>$this->baseUrl()."/#organization"
      ],
      "inLanguage"=> "en-US"
    ];



    $entity = $this->getCurrentEntity();

    $productCollection =
    $this->collectionContext
        ->getContext($entity);

    if (!empty($productCollection)) {

      $products =
      $this->productCollection->getProducts(
          $productCollection['type'],
          $productCollection['brand'],
          12
      );

      if (!empty($products)) {

        $itemListProducts = [];

        foreach($products as $product){

            $productSchema =
                new ProductSchema($product);

            /*
            * Add Product entity
            */
            $graph[] =
                $productSchema->build();


            $itemListProducts[] = $product;

        }

        $itemListProducts = [];

        foreach($products as $product){

            $productSchema =
                new ProductSchema($product);


            /*
            * Add Product entity
            */
            $graph[] =
                $productSchema->build();


            $itemListProducts[] = $product;

        }

        $graph[] =
        (new ItemListSchema(
            $itemListProducts,
            $url,
            $productCollection['title']
        ))->build();

      }

    }

    if ($entity) {

      /*
       * Webpage
       */
      $graph[] = $this->webpage($url, $entity);

      /*
       * Brand Taxonomy Page
       */
      if ($entity->getEntityTypeId()=="taxonomy_term") {

        $graph[] =
          (new BrandSchema(
            $entity,
            $url,
            $this->fileUrl
          ))->build();


        $graph[] =
          (new CollectionSchema(
            $entity,
            $url,
            $productCollection
          ))->build();

      }



      /*
       * Landing Page Node
       */
      if ($entity->getEntityTypeId()=="node") {


        // $graph[] =
        //   (new PageSchema(
        //     $entity,
        //     $url
        //   ))->build();


        $graph[] =
          (new CollectionSchema(
            $entity,
            $url,
            $productCollection
          ))->build();

      }


      /*
       * FAQ
       */
      if (
        $entity->hasField('field_faqs') &&
        !$entity->get('field_faqs')->isEmpty()
      ){

        // $graph[] =
        //   [
        //     "@type"=>"WebPageElement",
        //     "@id"=>$url."/#faq",
        //     "name"=>"Frequently Asked Questions",
        //     "isPartOf"=>[
        //       "@id"=>$url . "/"
        //     ]
        //   ];

        $graph[] =
          (new FaqSchema($entity,$url))->build();

      }


    }

    /**
     * Breadcrumb Schema
     */
    $breadcrumb = \Drupal::service('breadcrumb')
      ->build($this->routeMatch);

    $graph[] = (new BreadcrumbSchema(
      $breadcrumb,
      $this->current_url
    ))->build();

    return [
      "@context"=>"https://schema.org",
      "@graph"=>$graph
    ];


  }

  /**
   * Get Drupal site name.
   */
  // protected function getSiteName(): string {
  //   return $this->configFactory
  //     ->get('system.site')
  //     ->get('name');
  // }

  private function webpage($url, $entity) {

    //$site_name = $this->getSiteName();
    $site_name = "My Urban Clothing";
    $page_name = $entity->label();

    return [
      "@type"=>"WebPage",
      "@id"=>$url."/#webpage",
      "url"=>$url . "/",
      "name"=>$page_name,
      "description"=>$site_name . " is an online urban clothing discovery platform helping shoppers explore streetwear, apparel, and fashion products from popular brands and trusted retailers.",
      "inLanguage"=> "en-US"
    ];

  }


  private function organization() {

    //$site_name = $this->getSiteName();
    $site_name = "My Urban Clothing";

    return [

      "@type"=>"Organization",
      "additionalType"=>"https://www.productontology.org/id/Clothing",

      "@id"=>$this->baseUrl()."/#organization",

      "name"=>$site_name,

      "url"=>$this->baseUrl()."/",

      "logo"=>[
        "@type"=>"ImageObject",
        "@id"=>$this->baseUrl()."/#logo",
        "url"=>$this->baseUrl().
          "/sites/default/files/my-urban-clothing-logo.png",
        "width"=>1288,
        "height"=>408
      ],

      "description"=> $site_name . " is a United States based urban clothing discovery platform helping shoppers discover streetwear, apparel, and fashion products from popular brands and trusted retailers.",
      "areaServed"=> [
        "@type" => "Country",
        "name" => "United States",
      ],
      "location"=> [
        "@type" => "Place",
        "name" => "Cumberland, Rhode Island, United States",
      ]
    ];

  }




  private function getCurrentEntity(){


    $node =
      $this->routeMatch->getParameter('node');


    if($node){
      return $node;
    }


    $term =
      $this->routeMatch->getParameter('taxonomy_term');


    if($term){
      return $term;
    }


    return NULL;

  }

  private function setCurrentUrl() {

    $internal_path = $this->pathCurrent->getPath();

    $alias = $this->aliasManager->getAliasByPath($internal_path);

    $this->current_url = str_replace('/home','/',$this->baseUrl() . $alias);
  }


  private function baseUrl(): string {

    return $this->request
        ->getCurrentRequest()
        ->getSchemeAndHttpHost();
  }


}

  