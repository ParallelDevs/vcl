<?php

/**
 * @file
 * Contains \Drupal\vcl\Plugin\Swap\.
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
 *   tip = "[button url='url'] Button [/button]"
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

    // Concatenate the classes
    if (array_key_exists('class', $attrs)) {
      $attrs['class'] = $this->addClass($attrs['class'], 'button');
    }else{
      $attrs['class'] = 'button';
    }

    // Concatenate the classes
    if (array_key_exists('extraclass', $attrs)) {
      $attrs['class'] = $this->addClass($attrs['class'], $attrs['extraclass']);
    }

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
    // Define variables with HTML code of the attributes
    $id = (array_key_exists('id', $attrs)) ? ' id="' . $attrs['id'] . '" ' : "";
    $class = (array_key_exists('class', $attrs)) ? ' class="' . $attrs['class'] . '" ' : "";
    // Validate exists title.
    ($text != '') ? '' : $text = 'title';

    return '<a href="' . $attrs['url'] . '" ' . $id . $class . $attrs['style'] . ' ><span>' . $text . '</span></a>';
  }

}
