<?php

/**
 * @file
 * Contains \Drupal\vcl\Plugin\Swap\.
 */

namespace Drupal\swaps\Plugin\Swap;

use Drupal\swaps\SwapBase;

/**
 * Provides a 'HTML List' swap.
 *
 * @Swap(
 *   id = "swap_list",
 *   name = "List",
 *   description = @Translation("Add an ordered or disordered list."),
 *   container = true,
 *   children = "swap_li",
 *   tip = "</br>[list type='ol | ul' class='class']
 *    </br>&emsp;[li class='class'] one [/li]
 *    </br>&emsp;[li] two [/li]
 *    </br>[/list]"
 * )
 */

class SwapListHTML extends SwapBase {

  /**
   * Get all attributes of the swap and validate it.
   */
  public function processCallback($attrs, $text) {
    $attrs = $this->setAttrs(array(
      'type' => 'ul',
    ),
      $attrs
    );

    $attrs['style'] = $this->getStyle($attrs);
    // Concatenate the classes
    if (array_key_exists('extraclass', $attrs)) {
      if (array_key_exists('class', $attrs)) {
        $attrs['class'] = $this->addClass($attrs['class'], $attrs['extraclass']);
      }else{
        $attrs['class'] = $attrs['extraclass'];
      }
    }
    $attrs['type'] = $this->validateType($attrs['type']);

    return $this->theme($attrs, $text);
  }

  /**
   * Validate list type attribute.
   */
  public function validateType($type) {
    switch ($type) {
      case 'ul':
        return $type;

      case 'ol':
        return $type;

      default:
        return 'ul';
    }
  }

  /**
   * Create the string of the swap.
   */
  public function theme($attrs, $text) {

    // Define variables with HTML code of the attributes
    $id = (array_key_exists('id', $attrs)) ? ' id="' . $attrs['id'] . '" ' : "";
    $class = (array_key_exists('class', $attrs)) ? ' class="' . $attrs['class'] . '" ' : "";

    return '<' . $attrs['type'] . ' ' . $id . $class . $attrs['style'] . ' >' . $text . '</' . $attrs['type'] . '>';
  }

}
