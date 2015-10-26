<?php
/**
 * @file
 * Provides Drupal\visual_content_layout\SwapInterface
 */

namespace Drupal\swaps;

use Drupal\Component\Plugin\PluginInspectionInterface;

/**
 * Defines an interface for swaps plugins.
 */

interface SwapInterface extends PluginInspectionInterface  {

  /**
   * Return all the information of the swap.
   *
   * @return array
   */
  public function info();
  /**
   * Process all the attributes in the swap.
   *
   * @return string
   */
  public function processCallback($attrs, $text);
  /**
   * Return the html code with the attributes corresponding.
   *
   * @return string
   */
  public function theme($attrs, $text);
  /**
   * Return an array with the user attributes or the default.
   *
   * @return array()
   */
  public function setAttrs($pairs, $attrs);
  /**
   * Return an array with the default class and the user class.
   *
   * @return array()
   */
  public function addClass($class, $default);
  /**
   * Return a string with the all style attributes.
   *
   * @return string
   */
  public function getStyle($attributes);

}
