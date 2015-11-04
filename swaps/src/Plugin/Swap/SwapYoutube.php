<?php

/**
 * @file
 * Contains \Drupal\visual_content_layout\Plugin\Swap\.
 */

namespace Drupal\swaps\Plugin\Swap;

use Drupal\swaps\SwapBase;

/**
 * Provides a 'Youtube' swap.
 *
 * @Swap(
 *   id = "youtube",
 *   name = "Youtube",
 *   description = @Translation("Add div with the class Youtube."),
 *   attributes = "[ URL | url | text ] , [ Width | width | text ],
 *                 [ Height | height | text ]",
 *   container = false,
 *   tip = "[youtube width='width' height='height' url='url' /]
 *   -> case the blank space before the '/' "
 * )
 */

class SwapYoutube extends SwapBase {

  /**
   * Get all attributes of the swap and validate it.
   */
  public function processCallback($attrs, $text) {
    $attrs = $this->setAttrs(array(
      'width' => '560',
      'height' => '315',
      'url' => '',
    ),
      $attrs
    );

    $attrs['style'] = $this->getStyle($attrs);
    $attrs['width'] = $this->validateNumber($attrs['width']);
    $attrs['height'] = $this->validateNumber($attrs['height']);

    return $this->theme($attrs);
  }

  /**
   * Validate the width and height is number.
   */
  public function validateNumber($number) {
    if (is_int($number)) {
      return $number;
    }
    else {
      return 500;
    }
  }

  /**
   * Create the string of the swap.
   */
  public function theme($attrs, $text) {
    return '<iframe src="' . $attrs['url'] . '" width="' . $attrs['width']
    . '" height="' . $attrs['height'] . '" frameborder="0" allowfullscreen '
    . $attrs['style'] . '></iframe>';
  }

}
