<?php

/**
 * @file
 * Contains \Drupal\visual_content_layout\Plugin\Swap\.
 */

namespace Drupal\visual_content_layout\Plugin\Swap;

use Drupal\visual_content_layout\SwapBase;
use Drupal\Core\Routing\UrlGeneratorInterface;

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

  function processCallback($attrs, $text) {
    $attrs = $this->setAttrs(array(
      'title' => 'title default',
      'class' => 'button',
      'url' => '',
      'path' => '<front>',
      'text' => '',
    ),
      $attrs
    );

    $attrs['class'] = $this->addClass($attrs['class'], 'button');
    $attrs['text'] = $text;
    if ($attrs['url']) {
      $attrs['path'] = $attrs['url'];
    }
    return $this->theme($attrs);
  }

  public function theme($attrs, $text) {
    return '<a href="' . $attrs['path'] . '" class="' . $attrs['class'] . '"><span>' . $attrs['text'] . '</span></a>';
  }

}
