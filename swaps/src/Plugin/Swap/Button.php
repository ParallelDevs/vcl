<?php

/**
 * @file
 * Contains \Drupal\visual_content_layout\Plugin\Swap\.
 */

namespace Drupal\swaps\Plugin\Swap;

use Drupal\swaps\SwapBase;

/**
 * Provides a 'Button' swap.
 *
 * @Swap(
 *   id = "button",
 *   name = "Button",
 *   description = @Translation("Insert a link formatted as a button."),
 *   attributes = "[ URL | url | text ] , [ Title | text | text ]",
 *   container = false,
 *   tip = "[button url='url' class='class'] Button [/button]"
 * )
 */
class Button extends SwapBase {

  function processCallback($attrs, $text) {
    $attrs = $this->setAttrs(array(
      'class' => '',
      'url' => '',
      'path' => '<front>',
    ),
      $attrs
    );

    $attrs['class'] = $this->addClass($attrs['class']);
    $attrs['text'] = $text;
    if ($attrs['url']) {
      $attrs['path'] = $attrs['url'];
    }
    return $this->theme($attrs);
  }

  public function theme($attrs, $text) {
    return '<a href="' . $attrs['path'] . '" class="' . $attrs['class'] . ' button"><span>' . $attrs['text'] . '</span></a>';
  }

}
