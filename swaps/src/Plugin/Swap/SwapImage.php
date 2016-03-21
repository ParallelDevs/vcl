<?php

/**
 * @file
 * Contains \Drupal\vcl\Plugin\Swap\.
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
    // Concatenate the classes
    if (array_key_exists('extraclass', $attrs)) {
      if (array_key_exists('class', $attrs)) {
        $attrs['class'] = $this->addClass($attrs['class'], $attrs['extraclass']);
      }else{
        $attrs['class'] = $attrs['extraclass'];
      }
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
    $alt = (array_key_exists('alt', $attrs)) ? ' alt="' . $attrs['alt'] . '" ' : "";

    $img = '<img ' . $id . $class . $alt . '" src="' . $attrs['url'] . '" height="' . $attrs['height'] . '" width="' . $attrs['width'] . '" ' . $attrs['style'] . ' />';

    return $img;

  }

}
