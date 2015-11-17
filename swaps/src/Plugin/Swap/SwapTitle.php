<?php

/**
 * @file
 * Contains \Drupal\visual_content_layout\Plugin\Swap\.
 */

namespace Drupal\swaps\Plugin\Swap;

use Drupal\swaps\SwapBase;

/**
 * Provides a 'Title' swap.
 *
 * @Swap(
 *   id = "swap_title",
 *   name = "Title",
 *   description = @Translation("Insert a Title."),
 *   container = false,
 *   tip = "[title type='h1,h2,h3,h4,h5,h6' ] Title [/title]"
 * )
 */
class SwapTitle extends SwapBase {

  /**
   * Get all attributes of the swap and validate it.
   */
  public function processCallback($attrs, $text) {
    $attrs = $this->setAttrs(array(
      'type' => 'h1',
    ),
      $attrs
    );

    $attrs['class'] = $this->addClass($attrs['class'], $attrs['extraclass']);
    $attrs['style'] = $this->getStyle($attrs);
    return $this->theme($attrs, $text);
  }

  /**
   * Create the string of the swap.
   */
  public function theme($attrs, $text) {
    // Validate exists id.
    $id = ($attrs['id'] != '') ? ' id="' . $attrs['id'] . '"' : "";

    return '<' . $attrs['type'] . ' ' . $id . ' class="' .  $attrs['class'] . '" ' . $attrs['style'] . '>' . $text . '</' . $attrs['type'] . '>';
  }

}
