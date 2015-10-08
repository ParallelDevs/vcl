<?php

/**
 * @file
 * Contains \Drupal\visual_content_layout\Plugin\Swap\.
 */

namespace Drupal\swaps\Plugin\Swap;

use Drupal\swaps\SwapBase;

/**
 * Provides a 'Div' swap.
 *
 * @Swap(
 *   id = "div",
 *   name = "Div",
 *   description = @Translation("Add div which you can add a bootstrap class."),
 *   attributes = "class[ row | container ]:select",
 *   container = true,
 *   tip = "[div class='row | container'] Content [/div]"
 * )
 */

class Div extends SwapBase {

  function processCallback($attrs, $text) {
    $attrs = $this->setAttrs(array(
      'class' => 'default',
    ),
      $attrs
    );

    $attrs['class'] = $this->validateClass($attrs['class']);

    return $this->theme($attrs,$text);
  }

  public function validateClass($class){
    switch ($class) {
      case "row":
        return $class;
      case "container":
        return $class;
      default:
        return 'default';
    }
  }

  public function theme($attrs, $text) {
    return '<div class="' . $attrs['class'] . '">' . $text . '</div>';
  }

}
