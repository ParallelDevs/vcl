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
 *   name = "Highlight",
 *   description = @Translation("Add a span with the class Highlight for put the style"),
 *   attributes = "[Font Color | font_color | color ] ,
 *                 [Text | text | text ]",
 *   container = false,
 *   tip = "[hgl] content [/hgl] -> is the Highlight "
 * )
 */

class Highlight extends SwapBase {

  function processCallback($attrs, $text) {
    $attrs = $this->setAttrs(array(
      'extraclass' => '',
      'font_color' => ''
    ),
      $attrs
    );

    $extra = array('font_color' => $attrs['font_color']);
    $attrs['style'] = $this->getStyle($attrs,$extra);

    return $this->theme($attrs,$text);
  }

  public function theme($attrs, $text) {

    return '<span class="' . $attrs['extraclass'] . '"' . $attrs['style'] . '">' . $text . ' </span>';
  }

}
