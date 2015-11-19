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
    $attrs['class'] = $this->addClass($attrs['class'], $attrs['extraclass']);
    return $this->theme($attrs, $text);
  }


  /**
   * Create the string of the swap.
   */
  public function theme($attrs, $text) {

    // Validate exists id.
    $id = ($attrs['id'] != '') ? ' id="' . $attrs['id'] . '"' : "";

    $img = '<img' . $id . ' class="' . ['class'] . '" src="' . $attrs['url'] . '" height="' . $attrs['height'] . '" width="' . $attrs['width'] . '" ' . $attrs['style'] . ' />';

    return $img;

  }

}
