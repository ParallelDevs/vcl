<?php

/**
 * @file
 * Contains \Drupal\visual_content_layout\Plugin\Swap\.
 */

namespace Drupal\swaps\Plugin\Swap;

use Drupal\swaps\SwapBase;

/**
 * Provides a 'Button' swap.
 *
 * @Swap(
 *   id = "button",
 *   name = "Button",
 *   description = @Translation("Insert a link formatted as a button."),
 *   attributes = "[ URL | url | text ] , [ Title | text | text ]",
 *   container = false,
 *   tip = "[button url='url' class='class'] Button [/button]"
 * )
 */
class Button extends SwapBase {

  function processCallback($attrs, $text) {
    $attrs = $this->setAttrs(array(
      'extraclass' => '',
      'id' =>'',
      'url' => '#',
      'path' => '<front>',
    ),
      $attrs
    );

    $attrs['extraclass'] = $this->addClass($attrs['extraclass'], 'button');
    $attrs['style'] = $this->getStyle($attrs);
    if ($attrs['url']) {
      $attrs['path'] = $attrs['url'];
    }
    return $this->theme($attrs, $text);
  }

  public function theme($attrs, $text) {

    //process attributes that don't have default value
    $id = ($attrs['id'] != '') ? ' id="'.$attrs['id'].'"' : "";
    $class = ($attrs['extraclass'] != '') ? ' class="'.$attrs['extraclass'].'"' : "";
    ($text != '') ? '' : $text = 'title';

    return '<a href="' . $attrs['url'] . '" '. $id . $class . $attrs['style'] . '><span>' . $text . '</span></a>';
  }

}
