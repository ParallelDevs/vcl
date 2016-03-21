<?php

/**
 * @file
 * Contains \Drupal\vcl\Plugin\Swap\.
 */

namespace Drupal\swaps\Plugin\Swap;

use Drupal\swaps\SwapBase;

/**
 * Provides a 'Div' swap.
 *
 * @Swap(
 *   id = "swap_div",
 *   name = "Div",
 *   description = @Translation("Add div which you can add a bootstrap class."),
 *   container = true,
 *   tip = "[div class='row | container'] Content [/div]"
 * )
 */

class SwapDiv extends SwapBase {

  /**
   * Get all attributes of the swap and validate it.
   */
  public function processCallback($attrs, $text) {
    $attrs = $this->setAttrs(array(
      'type' => 'container',
    ),
      $attrs
    );

    $class_exist = array_key_exists('class', $attrs);

    // Validate the type of the div
    if ($class_exist) {
      $attrs['class'] = $this->validateClass($attrs['type'], $attrs['class']);
    }else{
      $attrs['class'] = $this->validateClass($attrs['type'], "");
    }

    // Concatenate the classes
    if (array_key_exists('extraclass', $attrs)) {
      if ($class_exist) {
        $attrs['class'] = $this->addClass($attrs['class'], $attrs['extraclass']);
      }else{
        $attrs['class'] = $attrs['extraclass'];
      }
    }

    $attrs['style'] = $this->getStyle($attrs);

    return $this->theme($attrs, $text);
  }

  /**
   * Validate the class.
   */
  public function validateClass($type, $class ) {
    switch ($type) {
      case "row":
        return $type;

      case "container":
        return $type;

      case "normal":
        return $class;
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
