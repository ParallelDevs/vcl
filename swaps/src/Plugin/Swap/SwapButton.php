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
 *   id = "swap_button",
 *   name = "Button",
 *   description = @Translation("Insert a link formatted as a button."),
 *   container = false,
 *   tip = "[button url='url' class='class'] Button [/button]"
 * )
 */
class SwapButton extends SwapBase {

  /**
   * Get all attributes of the swap and validate it.
   */
  public function processCallback($attrs, $text) {
    $attrs = $this->setAttrs(array(
      'url' => '<front>',
    ),
      $attrs
    );

    $attrs['class'] = $this->addClass($attrs['class'], 'button');
    $attrs['class'] = $this->addClass($attrs['class'], $attrs['extraclass']);
    $attrs['style'] = $this->getStyle($attrs);
    if ($attrs['url']) {
      $attrs['path'] = $attrs['url'];
    }
    return $this->theme($attrs, $text);
  }

  /**
   * Create the string of the swap.
   */
  public function theme($attrs, $text) {
    // Validate exists id.
    $id = ($attrs['id'] != '') ? ' id="' . $attrs['id'] . '"' : "";
    // Validate exists title.
    ($text != '') ? '' : $text = 'title';

    return '<a href="' . $attrs['url'] . '" ' . $id
    . ' class="' .  $attrs['class'] . '" ' . $attrs['style']
    . '><span>' . $text . '</span></a>';
  }

}
