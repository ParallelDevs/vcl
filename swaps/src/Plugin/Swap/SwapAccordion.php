<?php

/**
 * @file
 * Contains \Drupal\vcl\Plugin\Swap\.
 */

namespace Drupal\swaps\Plugin\Swap;

use Drupal\swaps\SwapBase;

/**
 * Provides a 'Accordion' swap.
 *
 * @Swap(
 *   id = "swap_accordion",
 *   name = "Accordion",
 *   description = @Translation("Add the Accordion container."),
 *   container = true,
 *   children = "swap_accritem",
 *   tip = "</br>[accordion]
 *            </br>&emsp;[accritem title='title] Content [/accritem]
 *          </br>[/accordion]"
 * )
 */

class SwapAccordion extends SwapBase {

  /**
   * Get all attributes of the swap and validate it.
   */
  public function processCallback($attrs, $text) {

    $attrs = $this->setAttrs(array(
      'id' => 'accordion',
    ),
      $attrs
    );

    // Concatenate the classes
    if (array_key_exists('extraclass', $attrs)) {
      if (array_key_exists('class', $attrs)) {
        $attrs['class'] = $this->addClass($attrs['class'], $attrs['extraclass']);
      }else{
        $attrs['class'] = $attrs['extraclass'];
      }
    }

    $attrs['style'] = $this->getStyle($attrs);

    return $this->theme($attrs, $text);
  }

  /**
   * Create the string of the swap.
   */
  public function theme($attrs, $text) {
    // Define variables with HTML code of the attributes
    $id = (array_key_exists('id', $attrs)) ? ' id="' . $attrs['id'] . '" ' : "";
    $class = (array_key_exists('class', $attrs)) ? $attrs['class'] : "";

    return '<div class="panel-group ' . $class . ' "' . $id . '">' . $text . '</div>';
  }

}
