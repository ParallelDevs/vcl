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
    $attrs = $this->setAttrs(array(
      'file' => '',
      'width' => '',
      'height' => '',
    ),
      $attrs
    );

    $attrs['style'] = $this->getStyle($attrs);
    $attrs['width'] = $this->validateNumber($attrs['width']);
    $attrs['height'] = $this->validateNumber($attrs['height']);
    return $this->theme($attrs, $text);
  }

  /**
   * Validate the width and height is number.
   */
  public function validateNumber($number) {
    if (is_int($number)) {
      return $number;
    }
    else {
      return "";
    }
  }

  /**
   * Create the string of the swap.
   */
  public function theme($attrs, $text) {
    if ($attrs['width'] == '' || $attrs['height'] == '') {
      return '<img src="' . $attrs['file'] . '" />';
    }
    else {
      return '<img src="' . $attrs['file'] . '" height="' . $attrs['width']
      . '" width="' . $attrs['height'] . '"/>';
    }
  }

}
