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
   * Return the id of the swap.
   *
   * @return string
   */
  public function getId();
  /**
   * Return the Title.
   *
   * @return string
   */
  public function getTitle();
  /**
   * Return the Description.
   *
   * @return string
   */
  public function getDescription();

}