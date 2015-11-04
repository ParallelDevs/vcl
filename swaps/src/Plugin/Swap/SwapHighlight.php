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
 *   id = "swap_hgl",
 *   name = "Highlight",
 *   description = @Translation("Add a span with the class Highlight"),
 *   attributes = "[Font Color | font_color | color ] ,
 *                 [Text | text | text ]",
 *   container = false,
 *   tip = "[hgl] content [/hgl] -> is the Highlight "
 * )
 */

class SwapHighlight extends SwapBase {

  /**
   * Get all attributes of the swap and validate it.
   */
  public function processCallback($attrs, $text) {
    $extra = array('color' => $attrs['fontcolor']);
    $attrs['style'] = $this->getStyle($attrs, $extra);
    $attrs['class'] = $this->addClass($attrs['class'], $attrs['extraclass']);

    return $this->theme($attrs, $text);
  }

  /**
   * Create the string of the swap.
   */
  public function theme($attrs, $text) {

    return '<span class="' . $attrs['class'] . '"'
    . $attrs['style'] . '">' . $text . ' </span>';
  }

}
