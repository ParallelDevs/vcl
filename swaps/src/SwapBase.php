<?php
/**
 * @file
 * Provides Drupal\visual_content_layout\SwapBase.
 */

namespace Drupal\swaps;

use Drupal\Component\Plugin\PluginBase;

class SwapBase extends PluginBase implements SwapInterface {

  /**
   * Get the annotations values defined in the plugin .
   *
   * @return array
   *  array of de main values in the annotations.
   */
  public function info() {
    return array(
      'id' => $this->pluginDefinition['id'],
      'title' => $this->pluginDefinition['title'],
      'description' => $this->pluginDefinition['description'],
      'tip' => $this->pluginDefinition['tip'],
    );
  }

  /**
   * Provide a processCallback method for the swap.
   * call theme method for create the code if it needed.
   *
   * @param $attrs
   *  array with all the attributes of the swap
   *
   * @return string
   *  string with all the code of the swap.
   */
  public function processCallback($attrs, $text){
    $text = "Create your own processCallback function.";
    return $text;
  }

  /**
   * Provide a theme method for the swap.
   * Place the attributes in the code structure.
   *
   * @param $attrs
   *  array with all the attributes of the swap
   *
   * @return string
   *  string with all the code of the swap.
   */
  public function theme($attrs, $text){
    $text = "Create your own theme function.";
    return $text;
  }


  /**
   * Combines user attributes with default attributes.
   *
   * The pairs should be considered to be all of the attributes which are
   * supported by the caller and given as a list. The returned attributes will
   * only contain the attributes in the $pairs list.
   *
   * If the $attrs list has unsupported attributes, then they will be ignored and
   * removed from the final return list.
   *
   * @param array $pairs
   *   Entire list of supported attributes and their defaults.
   *
   * @param array $attrs
   *   User defined attributes in Shortcode tag.
   *
   * @return array
   *   Combined and filtered attribute list.
   */
  function setAttrs($pairs, $attrs) {
    $attrs = (array) $attrs;
    foreach ($attrs as $name => $value) {
      if ($value == '' && array_key_exists($name, $pairs)) {
        $attrs[$name] = $pairs[$name];
      }
    }
    return $attrs;
  }

  /**
   * Provides a class parameter helper function.
   *
   * @param mixed $class
   *   The class string or array.
   *
   * @param string $default
   *   The default class value.
   *
   * @return string
   *   The proper classes string.
   */
  function addClass($class = '', $default = '') {
    if ($class) {
      if (!is_array($class)) {
        $class = explode(' ', $class);
      }
      array_unshift($class, $default);
      $class = array_unique($class);
    }
    else {
      $class[] = $default;
    }
    return implode(' ', $class);
  }

  /**
   * Process all default attributes that are used for styling.
   *
   * @param mixed $attributes
   *   The array with all attributes.
   *
   * @return string
   *   The style definition with all parameters.
   */
  function getStyle($attributes = array(), $extra = array()) {
    $style = ' style="';
    //process all attributes of the swap
    foreach ($attributes as $name => $attr) {
      //process only style attributes
      switch ($name) {
        //---------------------------------------------------------------
        //                   process Paddings attributes
        //---------------------------------------------------------------
        case 'paddingleft':
          $style.= 'padding-left:'.$attr.';';
          break;

        case 'paddingright':
          $style.= 'padding-right:'.$attr.';';
          break;

        case 'paddingtop':
          $style.= 'padding-top:'.$attr.';';
          break;

        case 'paddingbottom':
          $style.= 'padding-bottom:'.$attr.';';
          break;

        //---------------------------------------------------------------
        //                   process Margins attributes
        //---------------------------------------------------------------
        case 'marginleft':
          $style.= 'margin-left:'.$attr.';';
          break;

        case 'marginright':
          $style.= 'margin-right:'.$attr.';';
          break;

        case 'margintop':
          $style.= 'margin-top:'.$attr.';';
          break;

        case 'marginbottom':
          $style.= 'margin-bottom:'.$attr.';';
          break;

        //---------------------------------------------------------------
        //                   process Classes, & ID attributes
        //---------------------------------------------------------------
          case 'textalign':
            ($attr != 'default') ? $style.= 'text-align:'.$attr.';' : '';

          break;

        case 'cssstyles':
          $style.= $attr;
          break;

        //---------------------------------------------------------------
        //                   process Background  attributes
        //---------------------------------------------------------------
        case 'backgroundcolor':
          $style.= 'background-color:'.$attr.';';
          break;
      }
    }

    foreach ($extra as $name => $attr) {
      $style.= $name.':'.$attr.';';
    }

    return $style.='"';
  }

}
