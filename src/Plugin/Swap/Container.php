<?php

/**
 * @file
 * Contains \Drupal\visual_content_layout\Plugin\Swap\.
 */

namespace Drupal\visual_content_layout\Plugin\Swap;

use Drupal\visual_content_layout\SwapBase;

/**
 * Provides a 'Container' swap.
 *
 * @Swap(
 *   id = "container",
 *   name = @Translation("Container"),
 *   description = @Translation("Add div with the class container.")
 * )
 */

class Container extends SwapBase {

  function processCallback($attrs, $text) {
    $attrs = $this->setAttrs(array(
      'class' => 'container',
    ),
      $attrs
    );

    $attrs['class'] = $this->addClass($attrs['class'], 'container');
    return $this->theme($attrs,$text);
  }

  public function theme($attrs, $text) {
    return '<div class="' . $attrs['class'] . '">' . $text . '</div>';
  }

}
