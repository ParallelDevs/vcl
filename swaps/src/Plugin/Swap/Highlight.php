<?php

/**
 * @file
 * Contains \Drupal\visual_content_layout\Plugin\Swap\.
 */

namespace Drupal\swaps\Plugin\Swap;

use Drupal\swaps\SwapBase;

/**
 * Provides a 'Highlight' swap.
 *
 * @Swap(
 *   id = "hgl",
 *   name = "Highlight",
 *   description = @Translation("Add a span with the class Highlight for put the style"),
 *   attributes = "color:color, text:text",
 *   container = false,
 *   tip = "[hgl] content [/hgl] -> is the Highlight "
 * )
 */

class Highlight extends SwapBase {

  function processCallback($attrs, $text) {
    $attrs = $this->setAttrs(array(
      'class' => '',
      'color' => ''
    ),
      $attrs
    );

    $attrs['class'] = $this->addClass($attrs['class'],'highlight');
    return $this->theme($attrs,$text);
  }

  public function theme($attrs, $text) {

    $style = 'style="color:'.$attrs['color'].';"';

    return '<span class="' . $attrs['class'] . '" ' . $style . '">' . $text . ' </span>';
  }

}
