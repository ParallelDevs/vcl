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
 *   id = "swap_img",
 *   name = "Image",
 *   description = @Translation("Add an image."),
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

    $attrs['style'] = $this->getStyle($attrs);
    return $this->theme($attrs, $text);
  }


  /**
   * Create the string of the swap.
   */
  public function theme($attrs, $text) {

    if ($attrs['width'] == '' || $attrs['height'] == '') {
      $img = '<img src="' . $attrs['url'] . '" style="' . $attrs['style'] . '" />';
    }
    else {
      $img = '<img src="' . $attrs['url'] . '" height="' . $attrs['width']
      . '" width="' . $attrs['height'] . '" style="' . $attrs['style'] . '" />';
    }

    return $img;

  }

}
