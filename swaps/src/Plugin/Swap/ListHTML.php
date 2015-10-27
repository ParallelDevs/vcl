<?php

/**
 * @file
 * Contains \Drupal\visual_content_layout\Plugin\Swap\.
 */

namespace Drupal\swaps\Plugin\Swap;

use Drupal\swaps\SwapBase;

/**
 * Provides a 'HTML List' swap.
 *
 * @Swap(
 *   id = "list",
 *   name = "List",
 *   description = @Translation("Add an ordered or disordered list."),
 *   attributes = "[ List Type | type | select | ol:ul ]",
 *   container = true,
 *   tip = "</br>[list type='ol | ul' class='class']
 *    </br>&emsp;[li class='class'] one [/li]
 *    </br>&emsp;[li] two [/li]
 *    </br>[/list]"
 * )
 */

class ListHTML extends SwapBase {

  /**
   * Get all attributes of the swap and validate it.
   */
  public function processCallback($attrs, $text) {
    $attrs = $this->setAttrs(array(
      'extraclass' => '',
      'type' => '',
    ),
      $attrs
    );

    $attrs['style'] = $this->getStyle($attrs);
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

    if ($attrs['extraclass'] == '') {
      return '<' . $attrs['type'] . $attrs['style'] . '>' . $text . '</'
      . $attrs['type'] . '>';
    }
    else {
      return '<' . $attrs['type'] . $attrs['style'] . ' class="'
      . $attrs['class'] . '">' . $text . '</' . $attrs['type'] . '>';
    }
  }

}
