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
 *   name = @Translation("Highlight"),
 *   description = @Translation("Add a span with the class Highlight for put the style"),
 *   tip = "[hgl] content [/hgl] -> is the Highlight "
 * )
 */

class Highlight extends SwapBase {

  function processCallback($attrs, $text) {
    $attrs = $this->setAttrs(array(
      'class' => '',
    ),
      $attrs
    );

    $attrs['class'] = 'highlight';
    return $this->theme($attrs,$text);
  }

  public function theme($attrs, $text) {
    return '<span class="' . $attrs['class'] . '">' . $text . ' </span>';
  }

}
