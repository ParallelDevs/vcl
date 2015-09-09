<?php

/**
 * @file
 * Contains \Drupal\visual_content_layout\Plugin\Swap\.
 */

namespace Drupal\visual_content_layout\Plugin\Swap;

use Drupal\visual_content_layout\SwapBase;

/**
 * Provides a 'Column' swap.
 *
 * @Swap(
 *   id = "column",
 *   name = @Translation("Column"),
 *   description = @Translation("Add div with the class column.")
 * )
 */

class Column extends SwapBase {

  function processCallback($attrs, $text) {
    $attrs = $this->setAttrs(array(
      'size' => 'md',
      'number' => '4',
      'class' => '',
    ),
      $attrs
    );

    $bootstrap_class = "col-" . $attrs['size'] . "-" . $attrs['number'];
    $attrs['class'] = $this->addClass($attrs['class'], $bootstrap_class);
    return $this->theme($attrs,$text);
  }

  public function theme($attrs, $text) {
    return '<div class="' . $attrs['class'] . '">' . $text . '</div>';
  }

}
