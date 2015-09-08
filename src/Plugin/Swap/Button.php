<?php

/**
 * @file
 * Contains \Drupal\visual_content_layout\Plugin\Swap\.
 */

namespace Drupal\visual_content_layout\Plugin\Swap;

use Drupal\visual_content_layout\SwapBase;

/**
 * Provides a 'Button' swap.
 *
 * @Swap(
 *   id = "button",
 *   name = @Translation("Button"),
 *   description = @Translation("Insert a link formatted as a button.")
 * )
 */
class Button extends SwapBase {

  public function process($text, $langcode) {

    return $this;
  }

}
