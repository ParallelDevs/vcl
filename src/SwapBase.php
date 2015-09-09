<?php
/**
 * @file
 * Provides Drupal\visual_content_layout\SwapBase.
 */

namespace Drupal\visual_content_layout;

use Drupal\Component\Plugin\PluginBase;

class SwapBase extends PluginBase implements SwapInterface {

  public function info() {
    return array(
      'id' => $this->pluginDefinition['id'],
      'title' => $this->pluginDefinition['title'],
      'description' => $this->pluginDefinition['description'],
    );
  }

  /*
  * Provides process callback for button swap.
  */
  public function processCallback($attrs){
    $text = "Create your own processCallback function.";
    return $text;
  }

  /*
  * Place the attributes in the html code for swap.
  */
  public function theme($attrs){
    $text = "Create your own theme function.";
    return $text;
  }


  /**
   * Combines user attributes with known attributes.
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
  function set_attrs($pairs, $attrs) {
    $attrs = (array) $attrs;
    $out = array();
    foreach ($pairs as $name => $default) {
      if (array_key_exists($name, $attrs)) {
        $out[$name] = $attrs[$name];
      }
      else {
        $out[$name] = $default;
      }
    }
    return $out;
  }

  /**
   * Provides a class parameter helper function.
   *
   * @param mixed $class
   *   The class string or array
   *
   * @param string $default
   *   The default class value.
   *
   * @return string
   *   The proper classes string.
   */
  function add_class($class = '', $default = '') {
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
}