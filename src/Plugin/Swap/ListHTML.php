<?php

/**
 * @file
 * Contains \Drupal\visual_content_layout\Plugin\Swap\.
 */

namespace Drupal\visual_content_layout\Plugin\Swap;

use Drupal\visual_content_layout\SwapBase;

/**
 * Provides a 'HTML List' swap.
 *
 * @Swap(
 *   id = "list",
 *   name = @Translation("List"),
 *   description = @Translation("Add an ordered or disordered list."),
 *   tip = "</br>[list type='ol | ul' class='class']
                      </br>&emsp;[li class='class'] one
                      </br>&emsp;[li class='class'] two</br>
                 [/list]"
 * )
 */

class ListHTML extends SwapBase {

  function processCallback($attrs, $text) {
    $attrs = $this->setAttrs(array(
      'class' => 'default',
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

    $text = $this->extractLi($text);

    return '<'.$attrs['type'].' class="' . $attrs['class'] . '">' . $text . '</'.$attrs['type'].'>';
  }

  public function extractLi($text){
    $chunks = preg_split('!(\[{1,2}.*?\]{1,2})!', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
    $nextIsText = FALSE;
    $haveClass = FALSE;
    $elementList = "";
    $class = "";

    foreach ($chunks as $c) {

      if ($c==" ") {
        continue;
      }

      if(substr($c, 0, 1)=="["){
        if($nextIsText){
          $elementList = $elementList . $this->createTag($class,"");
        }
        $c = explode("=", $c);
        $class = substr($c[1], 1, -2);
        if(!$class){
          $class = "";
        }
        $haveClass = TRUE;
        $nextIsText = TRUE;
      }else{
        if($haveClass){
          $elementList = $elementList . $this->createTag($class,$c);
          $haveClass = FALSE;
          $nextIsText = FALSE;
        }
      }

    }//end foreach

    return $elementList;

  }

  public function createTag($class, $text){
    if($class == ""){
      return '<li>' . trim($text) . '</li>';
    }else {
      return '<li class="' . $class . '">' . trim($text) . '</li>';
    }
  }

}
