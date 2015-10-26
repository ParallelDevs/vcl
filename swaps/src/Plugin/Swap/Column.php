<?php

/**
 * @file
 * Contains \Drupal\visual_content_layout\Plugin\Swap\.
 */

namespace Drupal\swaps\Plugin\Swap;

use Drupal\swaps\SwapBase;

/**
 * Provides a 'Column' swap.
 *
 * @Swap(
 *   id = "column",
 *   name = "Column",
 *   description = @Translation("Add div with the class column."),
 *   container = true,
 *   tip = "[column size='xs | sm | md | lg' number='[1-12]'
 *          class='extra class'] Content [/column]"
 * )
 */

class Column extends SwapBase {

  /**
   * Get all attributes of the swap and validate it.
   */
  public function processCallback($attrs, $text) {
    $attrs = $this->setAttrs(array(
      'size' => 'md',
      'number' => '4',
      'extraclass' => '',
    ),
      $attrs
    );

    $default_class = "col-" . $this->validateSize($attrs['size']) . "-"
                             . $this->validateNumber($attrs['number']);
    $attrs['class'] = $this->addClass($attrs['class'], $default_class);
    $attrs['style'] = $this->getStyle($attrs);

    return $this->theme($attrs, $text);
  }

  /**
   * Validate the size attribute.
   */
  public function validateSize($size) {
    switch ($size) {
      case 'xs':
        return $size;

      case 'sm':
        return $size;

      case 'md':
        return $size;

      case 'lg':
        return $size;

      default:
        return 'md';
    }
  }

  /**
   * Validate the number attribute.
   */
  public function validateNumber($number) {
    if (intval($number) > 0 && intval($number) < 13) {
      return $number;
    }
    else {
      return 4;
    }
  }

  /**
   * Create the string of the swap.
   */
  public function theme($attrs, $text) {
    return '<div class="' . $attrs['extraclass'] . '" ' . $attrs['style'] . ' >'
    . $text . '</div>';
  }

}
