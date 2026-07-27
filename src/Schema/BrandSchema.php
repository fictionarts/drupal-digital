<?php

namespace Drupal\digital\Schema;

use Drupal\Core\Image\ImageFactoryInterface;

class BrandSchema {


private $term;
private $url;
private $fileUrl;


public function __construct($term,$url,$fileUrl){

$this->term=$term;
$this->url=$url;
$this->fileUrl=$fileUrl;

}



public function build(){


$image = null;
$width = '200';
$height = '200';

if(
$this->term->hasField('field_digital_image')
&& !$this->term->get('field_digital_image')->isEmpty()
){

$file =
$this->term
->get('field_digital_image')
->entity;

$file_image = \Drupal::service('image.factory')->get($file->getFileUri());

if ($file_image->isValid()) {
  $width = $file_image->getWidth();
  $height = $file_image->getHeight();
}


$image=[
"@type"=>"ImageObject",
"url"=>$this->fileUrl->generateAbsoluteString(
$file->getFileUri()
),
"width"=>$width,
"height"=>$height
];

}



return [

"@type"=>"Brand",

"@id"=>$this->url."/#brand",

"name"=>$this->term->label(),

"logo"=>$image,


"description"=>strip_tags(
//$this->term->getDescription()
$this->term->label() . ' and more on My Urban Clothing.'
)

];


}


}