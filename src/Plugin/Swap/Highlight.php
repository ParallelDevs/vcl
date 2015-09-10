<?php

/**
 * @file
 * Contains \Drupal\visual_content_layout\Plugin\Swap\.
 */

namespace Drupal\visual_content_layout\Plugin\Swap;

use Drupal\visual_content_layout\SwapBase;

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

class Image extends SwapBase {

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
