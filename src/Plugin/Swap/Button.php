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

  function processCallback($attrs) {
    $attrs = $this->set_attrs(array(
      'title' => 'title default',
      'class' => 'button',
      'url' => '',
      'path' => '<front>',
      'content' => 'button',
    ),
      $attrs
    );

    $attrs['class'] = $this->add_class($attrs['class'], 'button');
    if ($attrs['url']) {
      $attrs['path'] = $attrs['url'];
    }
    $attrs['path'] = '';
    return $this->theme($attrs);
  }

  public function theme($attrs) {
    return '<a href="' . $attrs['path'] . '" class="' . $attrs['class'] . '"><span>' . $attrs['content'] . '</span></a>';
  }

}
