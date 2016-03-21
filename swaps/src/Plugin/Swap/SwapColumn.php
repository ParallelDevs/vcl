<?php

/**
 * @file
 * Contains \Drupal\vcl\Plugin\Swap\.
 */

namespace Drupal\swaps\Plugin\Swap;

use Drupal\swaps\SwapBase;

/**
 * Provides a 'Column' swap.
 *
 * @Swap(
 *   id = "swap_column",
 *   name = "Column",
 *   description = @Translation("Add div with the class column."),
 *   container = true,
 *   tip = "[column size='xs | sm | md | lg' number='[1-12]'
 *          class='extra class'] Content [/column]"
 * )
 */

class SwapColumn extends SwapBase {

  /**
   * Get all attributes of the swap and validate it.
   */
  public function processCallback($attrs, $text) {
    $attrs = $this->setAttrs(array(
      'size' => 'md',
      'number' => '4',
    ),
      $attrs
    );

    $default_class = "col-" . $this->validateSize($attrs['size']) . "-"
                             . $this->validateNumber($attrs['number']);

    // Concatenate the classes
    if (array_key_exists('class', $attrs)) {
        $attrs['class'] = $this->addClass($attrs['class'], $default_class);
    }else{
      $attrs['class'] = $default_class;
    }

    // Concatenate the classes
    if (array_key_exists('extraclass', $attrs)) {
      $attrs['class'] = $this->addClass($attrs['class'], $attrs['extraclass']);
    }

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

    // Validate exists id.
    $id = (array_key_exists('id', $attrs)) ? ' id="' . $attrs['id'] . '" ' : "";
    $class = (array_key_exists('class', $attrs)) ? ' class="' . $attrs['class'] . '" ' : "";

    return '<div ' . $id . $class . $attrs['style'] . ' >' . $text . '</div>';
  }

}
