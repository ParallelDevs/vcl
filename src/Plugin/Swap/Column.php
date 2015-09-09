<?php

/**
 * @file
 * Contains \Drupal\visual_content_layout\Plugin\Swap\.
 */

namespace Drupal\visual_content_layout\Plugin\Swap;

use Drupal\visual_content_layout\SwapBase;

/**
 * Provides a 'Column' swap.
 *
 * @Swap(
 *   id = "column",
 *   name = @Translation("Column"),
 *   description = @Translation("Add div with the class column."),
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

    if ($this->validateSize($attrs['size']) && $this->validateNumber($attrs['number'])) {
      $bootstrap_class = "col-" . $attrs['size'] . "-" . $attrs['number'];
      $attrs['class'] = $this->addClass($attrs['class'], $bootstrap_class);
    }else{
      $attrs['class'] = "col-md-4";
    }

    return $this->theme($attrs,$text);
  }

  public function validateSize($size){
    switch ($size) {
      case 'xs':
        return TRUE;
      case 'sm':
        return TRUE;
      case 'md':
        return TRUE;
      case 'lg':
        return TRUE;
      default:
        return FALSE;
    }
  }

  public function validateNumber($number){
    if(intval($number)>0 && intval($number)<13){
      return TRUE;
    }else{
      return FALSE;
    }
  }

  public function theme($attrs, $text) {
    return '<div class="' . $attrs['class'] . '">' . $text . '</div>';
  }

}
