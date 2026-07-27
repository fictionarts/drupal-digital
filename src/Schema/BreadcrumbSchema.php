<?php

namespace Drupal\digital\Schema;


use Drupal\Core\Breadcrumb\Breadcrumb;


class BreadcrumbSchema {


protected $breadcrumb;
protected $url;


public function __construct(
    Breadcrumb $breadcrumb,
    $url
){

    $this->breadcrumb = $breadcrumb;
    $this->url = $url;

}



public function build(){


    $items = [];

    $position = 1;


    foreach($this->breadcrumb->getLinks() as $link){


        $item = [

            "@type"=>"ListItem",

            "position"=>$position++,

            "name"=>$link->getText()

        ];


        if($link->getUrl()){

            $item["item"] =
                $link->getUrl()
                ->setAbsolute()
                ->toString();

        }


        $items[] = $item;


    }



    return [

        "@type"=>"BreadcrumbList",

        "@id"=>$this->url."#breadcrumb",

        "name"=>"Breadcrumb",

        "itemListElement"=>$items

    ];


}


}