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
  public function processCallback($attrs, $text){
    return $text;
  }

  /*
  * Place the attributes in the html code for swap.
  */
  public function theme($attrs, $text){
    return $text;
  }
}