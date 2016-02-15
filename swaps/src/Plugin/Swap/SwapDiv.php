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

    $attrs['class'] = $this->validateClass($attrs['type'], $attrs['class']);
    $attrs['class'] = $this->addClass($attrs['class'], $attrs['extraclass']);
    $attrs['style'] = $this->getStyle($attrs);

    return $this->theme($attrs, $text);
  }

  /**
   * Validate the class.
   */
  public function validateClass($type, $class) {
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
    $id = ($attrs['id'] != '') ? ' id="' . $attrs['id'] . '"' : "";

    return '<div' . $id . ' class="' . $attrs['class'] . '" ' . $attrs['style'] . ' >' . $text . '</div>';
  }

}
