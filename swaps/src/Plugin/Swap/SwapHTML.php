<?php

/**
 * @file
 * Contains \Drupal\vcl\Plugin\Swap\.
 */

namespace Drupal\swaps\Plugin\Swap;

use Drupal\swaps\SwapBase;

/**
 * Provides a 'HTML' swap.
 *
 * @Swap(
 *   id = "swap_html",
 *   name = "HTML",
 *   description = @Translation("Add div which you can add a bootstrap class."),
 *   container = false,
 *   tip = "[html] Content [/html]"
 * )
 */

class SwapHTML extends SwapBase {

  /**
   * Get all attributes of the swap and validate it.
   */
  public function processCallback($attrs, $text) {
    return $text;
  }

}
