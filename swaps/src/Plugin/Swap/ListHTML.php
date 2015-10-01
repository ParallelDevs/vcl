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
 *   tip = "</br>[list type='ol | ul' class='class']
                      </br>&emsp;[li class='class'] one [/li]
                      </br>&emsp;[li] two [/li]
            </br>[/list]"
 * )
 */

class ListHTML extends SwapBase {

  function processCallback($attrs, $text) {
    $attrs = $this->setAttrs(array(
      'class' => '',
      'type' => ''
    ),
      $attrs
    );

    $attrs['type'] = $this->validateType($attrs['type']);

    return $this->theme($attrs,$text);
  }

  public function validateType($type){
    switch ($type) {
      case 'ul':
        return $type;
      case 'ol':
        return $type;
      default:
        return 'ul';
    }
  }

  public function theme($attrs, $text) {

    if ($attrs['class'] == '') {
      return '<' . $attrs['type'] . '>' . $text . '</' . $attrs['type'] . '>';
    }else{
      return '<' . $attrs['type'] . ' class="' . $attrs['class'] . '">' . $text . '</' . $attrs['type'] . '>';
    }
  }

}
