<?php

/**
 * @file
 * Contains \Drupal\vcl\Plugin\Swap\.
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

    // Concatenate the classes
    if (array_key_exists('extraclass', $attrs)) {
      if (array_key_exists('class', $attrs)) {
        $attrs['class'] = $this->addClass($attrs['class'], $attrs['extraclass']);
      }else{
        $attrs['class'] = $attrs['extraclass'];
      }
    }
    $attrs['style'] = $this->getStyle($attrs);

    return $this->theme($attrs, $text);
  }

  /**
   * Create the string of the swap.
   */
  public function theme($attrs, $text) {

    // Define variables with HTML code of the attributes
    $id = (array_key_exists('id', $attrs)) ? ' id="' . $attrs['id'] . '" ' : "";
    $class = (array_key_exists('class', $attrs)) ? ' class="' . $attrs['class'] . '" ' : "";

    return '<li ' . $id . $class . $attrs['style'] . ' >' . $text . '</li>';
  }
}
