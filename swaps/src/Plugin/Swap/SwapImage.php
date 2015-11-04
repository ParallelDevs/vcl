<?php

/**
 * @file
 * Contains \Drupal\visual_content_layout\Plugin\Swap\.
 */

namespace Drupal\swaps\Plugin\Swap;

use Drupal\swaps\SwapBase;

/**
 * Provides a 'Image' swap.
 *
 * @Swap(
 *   id = "img",
 *   name = "Image",
 *   description = @Translation("Add an image."),
 *   attributes = "url:text, height:text, width:text",
 *   container = false,
 *   tip = "[img url='url' WIDTH='width' HEIGHT='height' /]
 *         -> width and height optional. "
 * )
 */

class SwapImage extends SwapBase {

  /**
   * Get all attributes of the swap and validate it.
   */
  public function processCallback($attrs, $text) {
    $attrs = $this->setAttrs(array(
      'url' => '',
      'width' => '',
      'height' => '',
    ),
      $attrs
    );

    return $this->theme($attrs, $text);
  }

  /**
   * Create the string of the swap.
   */
  public function theme($attrs, $text) {
    if ($attrs['width'] == '' || $attrs['height'] == '') {
      return '<img src="' . $attrs['url'] . '" />';
    }
    else {
      return '<img src="' . $attrs['url'] . '" height="' . $attrs['width']
      . '" width="' . $attrs['height'] . '"/>';
    }
  }

}
