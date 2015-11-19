<?php
/**
 * @file
 * Provides Drupal\vcl\SwapInterface
 */

namespace Drupal\swaps;

use Drupal\Component\Plugin\PluginInspectionInterface;

/**
 * Defines an interface for swaps plugins.
 */

interface SwapInterface extends PluginInspectionInterface  {

  /**
   * Get swap plugin info.
   *
   * @return array
   *   All the information of the swap plugin.
   */
  public function info();

  /**
   * Process all the attributes in the swap.
   *
   * @return string
   *   HTML structure of the swap
   */
  public function processCallback($attrs, $text);

  /**
   * Process all attributes with the html code.
   *
   * @return string
   *   HTML code with the attributes corresponding
   */
  public function theme($attrs, $text);

  /**
   * Validate if there is all the attributes.
   *
   * @return array()
   *   The user attributes or the default
   */
  public function setAttrs($pairs, $attrs);

  /**
   * Validate if there is an extra class.
   *
   * @return array()
   *   Default class and the user class
   */
  public function addClass($class, $default);

  /**
   * Concatenate all style attributes.
   *
   * @return string
   *   All style attributes
   */
  public function getStyle($attributes);

}
