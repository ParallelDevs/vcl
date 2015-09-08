<?php
/**
 * @file
 * Provides Drupal\visual_content_layout\SwapInterface
 */

namespace Drupal\visual_content_layout;

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
   * Process all the atributtes in the swap.
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

}