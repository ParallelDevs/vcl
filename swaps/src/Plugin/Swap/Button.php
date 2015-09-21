<?php

/**
 * @file
 * Contains \Drupal\visual_content_layout\Plugin\Swap\.
 */

namespace Drupal\swaps\Plugin\Swap;

use Drupal\swaps\SwapBase;
use Drupal\Core\Routing\UrlGeneratorInterface;

/**
 * Provides a 'Button' swap.
 *
 * @Swap(
 *   id = "button",
 *   name = "Button",
 *   description = @Translation("Insert a link formatted as a button."),
 *   attributes = "url:text",
 *   tip = "[button url='url' class='class'] Button [/button]"
 * )
 */
class Button extends SwapBase {

  function processCallback($attrs, $text) {
    $attrs = $this->setAttrs(array(
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
