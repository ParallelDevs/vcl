<?php

/**
 * @file
 * Contains \Drupal\vcl\Plugin\Swap\.
 */

namespace Drupal\swaps\Plugin\Swap;

use Drupal\swaps\SwapBase;

/**
 * Provides a 'Youtube' swap.
 *
 * @Swap(
 *   id = "swap_youtube",
 *   name = "Youtube",
 *   description = @Translation("Add div with the class Youtube."),
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
    ),
      $attrs
    );

    $attrs['style'] = $this->getStyle($attrs);
    $attrs['url'] = $this->createVideoUrl($attrs['url']);
    $attrs['width'] = $this->validateNumber($attrs['width']);
    $attrs['height'] = $this->validateNumber($attrs['height']);

    return $this->theme($attrs, $text);
  }

  /**
   * Create a valid url for display videos.
   */
  public function createVideoUrl($youtube_url) {
    return "https://www.youtube.com/embed/" . explode("=", $youtube_url)[1];
  }

  /**
   * Validate the width and height is number.
   */
  public function validateNumber($number) {
    if (is_numeric($number)) {
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
    $output = '<iframe src="' . $attrs['url'] . '" width="' . $attrs['width'] . '" height="' . $attrs['height'] . '" frameborder="0" allowfullscreen ' . $attrs['style'] . ' ></iframe>';
    return $output;
  }

}
