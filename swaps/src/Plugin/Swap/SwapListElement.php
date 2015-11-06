<?php

/**
 * @file
 * Contains \Drupal\visual_content_layout\Plugin\Swap\.
 */

namespace Drupal\swaps\Plugin\Swap;

use Drupal\swaps\SwapBase;

/**
 * Provides a 'List Element' swap.
 *
 * @Swap(
 *   id = "swap_li",
 *   name = "List Element",
 *   container = true,
 *   description = @Translation("Add a element for the list."),
 * )
 */

class SwapListElement extends SwapBase {

  /**
   * Get all attributes of the swap and validate it.
   */
  public function processCallback($attrs, $text) {

    $attrs['class'] = $this->addClass($attrs['class'], $attrs['extraclass']);
    $attrs['style'] = $this->getStyle($attrs);

    return $this->theme($attrs, $text);
  }

  /**
   * Create the string of the swap.
   */
  public function theme($attrs, $text) {
    return '<li class="' . $attrs['class'] . '"' . $attrs['style']
    . '>' . $text . '</li>';
  }
}
