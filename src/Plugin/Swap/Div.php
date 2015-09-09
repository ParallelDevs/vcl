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
 *   id = "div",
 *   name = @Translation("Div"),
 *   description = @Translation("Add div which you can add a bootstrap class."),
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

    return $this->theme($attrs,$text);
  }

  public function theme($attrs, $text) {
    return '<div class="' . $attrs['class'] . '">' . $text . '</div>';
  }

}
