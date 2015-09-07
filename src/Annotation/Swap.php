<?php
/**
 * @file
 * Contains \Drupal\visual_content_layout\Annotation\Swap.
 */

namespace Drupal\visual_content_layout\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a swap item annotation object.
 *
 * Plugin Namespace: Plugin\visual_content_layout\Swap
 *
 * @see \Drupal\visual_content_layout\VisualContentLayoutManager
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
}