<?php

/**
 * @file
 * Contains \Drupal\vcl\Plugin\Swap\.
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

    // Validate exists id.
    $id = ($attrs['id'] != '') ? ' id="' . $attrs['id'] . '"' : "";

    return '<span' . $id . ' class="' . $attrs['class'] . '" ' . $attrs['style'] . '>' . $text . ' </span>';
  }

}
