<?php

/**
 * @file
 * Contains \Drupal\visual_content_layout\Plugin\Swap\.
 */

namespace Drupal\visual_content_layout\Plugin\Swap;

use Drupal\visual_content_layout\SwapBase;

/**
 * Provides a 'Youtube' swap.
 *
 * @Swap(
 *   id = "youtube",
 *   name = @Translation("Youtube"),
 *   description = @Translation("Add div with the class Youtube."),
 *   tip = "[youtube width='width' height='height' url='url' /] -> case the blank space before the '/' "
 * )
 */

class Youtube extends SwapBase {

  function processCallback($attrs, $text) {
    $attrs = $this->setAttrs(array(
      'width' => '560',
      'height' => '315',
      'url' => '',
    ),
      $attrs
    );

    return $this->theme($attrs);
  }

  public function theme($attrs, $text) {
    return '<iframe src="'. $attrs['url'] .'" width="'. $attrs['width'] .'" height="'. $attrs['height'] .'" frameborder="0" allowfullscreen></iframe>';
  }

}
