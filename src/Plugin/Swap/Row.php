<?php

/**
 * @file
 * Contains \Drupal\visual_content_layout\Plugin\Swap\.
 */

namespace Drupal\visual_content_layout\Plugin\Swap;

use Drupal\visual_content_layout\SwapBase;

/**
 * Provides a 'Row' swap.
 *
 * @Swap(
 *   id = "row",
 *   name = @Translation("Row"),
 *   description = @Translation("Add div with the class row.")
 * )
 */

class Row extends SwapBase {

  function processCallback($attrs, $text) {
    $attrs = $this->setAttrs(array(
      'class' => 'row',
    ),
      $attrs
    );

    $attrs['class'] = $this->addClass($attrs['class'], 'row');
    return $this->theme($attrs,$text);
  }

  public function theme($attrs, $text) {
    return '<div class="' . $attrs['class'] . '">' . $text . '</div>';
  }

}
