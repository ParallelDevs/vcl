<?php
/**
 * @file
 * Contains \Drupal\visual_content_layout\Annotation\Swap.
 */

namespace Drupal\swaps\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a swap item annotation object.
 *
 * Plugin Namespace: Plugin\swaps\Swap
 *
 * @see \Drupal\swaps\SwapManager
 * @see plugin_api
 *
 * @Annotation
 */
class Swap extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;
  /**
   * The plugin Title.
   *
   * @var string
   */
  public $title;
  /**
   * The plugin Description.
   *
   * @var string
   */
  public $description;
  /**
   * The plugin Own Attributes.
   * This need to be in the format "[ title | attribute | type | options]"
   * Use only the specific attributes for this swaps
   * If swap don't have options, leave it blank
   * The available types are : "text", "boolean", "select", "color"
   *    For "selects" separate the option with ":"
   *    If you need a number sequence can use "-"
   *       Examples = [ Item State | state | select | regular:good:excellent ]
   *                  [ Age | age | select | 1-12 ]
   * @var string
   */
  public $attributes;
  /**
   *
   * Indicates if the swap can contain others swaps.
   *
   * @var boolean
   */
  public $container;
  /**
   * The plugin Tip.
   *
   * @var string
   */
  public $tip;
}
