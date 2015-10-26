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
 *   id = "li",
 *   name = "List Element",
 *   container = true,
 *   description = @Translation("Add a element for the list."),
 * )
 */

class ListElement extends SwapBase {

  /**
   * Get all attributes of the swap and validate it.
   */
  public function processCallback($attrs, $text) {
    $attrs = $this->setAttrs(array(
      'extraclass' => '',
    ),
      $attrs
    );

    $attrs['style'] = $this->getStyle($attrs);

    return $this->theme($attrs, $text);
  }

  /**
   * Create the string of the swap.
   */
  public function theme($attrs, $text) {

    if ($attrs['extraclass'] = '') {
      return '<li' . $attrs['style'] . '>' . $text . '</li>';
    }
    else {
      return '<li class="' . $attrs['extraclass'] . '"' . $attrs['style']
      . '>' . $text . '</li>';
    }
  }
}
