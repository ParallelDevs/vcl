<?php

/**
 * @file
 * Contains \Drupal\visual_content_layout\Plugin\Swap\.
 */

namespace Drupal\swaps\Plugin\Swap;

use Drupal\swaps\SwapBase;

/**
 * Provides a 'Column' swap.
 *
 * @Swap(
 *   id = "column",
 *   name = "Column",
 *   description = @Translation("Add div with the class column."),
 *   attributes = "size[ xs | sm | md | lg ]:select, number[12-1]:select",
 *   container = true,
 *   tip = "[column size='xs | sm | md | lg' number='[1-12]' class='extra class'] Content [/column]"
 * )
 */

class Column extends SwapBase {

  function processCallback($attrs, $text) {
    $attrs = $this->setAttrs(array(
      'size' => 'md',
      'number' => '4',
      'class' => '',
    ),
      $attrs
    );

    $defaultClass = "col-" . $this->validateSize($attrs['size']) . "-"
                             . $this->validateNumber($attrs['number']);

    $attrs['class'] = $this->addClass($attrs['class'],$defaultClass);

    return $this->theme($attrs,$text);
  }

  public function validateSize($size){
    switch ($size) {
      case 'xs':
        return $size;
      case 'sm':
        return $size;
      case 'md':
        return $size;
      case 'lg':
        return $size;
      default:
        return 'md';
    }
  }

  public function validateNumber($number){
    if(intval($number)>0 && intval($number)<13){
      return $number;
    }else{
      return 4;
    }
  }

  public function theme($attrs, $text) {
    return '<div class="' . $attrs['class'] . '">' . $text . '</div>';
  }

}
