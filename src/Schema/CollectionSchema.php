<?php

namespace Drupal\digital\Schema;


class CollectionSchema {


private $entity;
private $url;
protected array $context;


public function __construct($entity,$url,array $context){

$this->entity=$entity;
$this->url=$url;
$this->context=$context;

}



public function build(){


return [

"@type"=>"CollectionPage",

"@id"=>$this->url."/#products",

"url"=>$this->url . "/",

"name"=>$this->entity->label()." Clothing Collection",

"description"=> $this->entity->label() . " products, browse clothing, and streetwear styles from trusted online retailers.",

"isPartOf" => [
  "@id" => $this->url . "/#webpage",
],

"mainEntity" => [
  "@id" => $this->url . "/#products",
],

];


}


}