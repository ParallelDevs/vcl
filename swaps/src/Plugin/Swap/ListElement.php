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

  function processCallback($attrs, $text) {
    $attrs = $this->setAttrs(array(
      'class' => '',
    ),
      $attrs
    );

    return $this->theme($attrs, $text);
  }

  public function theme($attrs, $text) {

    if ($attrs['class'] = '') {
      return '<li>' . $text . '</li>';
    }
    else {
      return '<li class="' . $attrs['class'] . '">' . $text . '</li>';
    }
  }
}
