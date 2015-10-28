<?php

/**
 * @file
 * Contains \Drupal\visual_content_layout\Plugin\Swap\.
 */

namespace Drupal\swaps\Plugin\Swap;

use Drupal\swaps\SwapBase;

/**
 * Provides a 'Block' swap.
 *
 * @Swap(
 *   id = "swap_block",
 *   name = "Block",
 *   description = @Translation("Insert a block."),
 *   container = false
 * )
 */
class Block extends SwapBase {

  /**
   * Get all attributes of the swap and validate it.
   */
  public function processCallback($attrs, $text) {
    $attrs = $this->setAttrs(array(
      'blockid' => '',
      'blocktype' => '',
    ),
      $attrs
    );

    $this->getBlockType($attrs);

    return $this->theme($attrs, $text);
  }

  /**
   * Validate if the block is view, custom or normal block.
   */
  public function getBlockType(&$attrs) {

    $block_attr = explode(":", $attrs['blockid']);

    if (sizeof($block_attr) > 1) {
      $attrs['blocktype'] = $block_attr[0];
      $attrs['blockid'] = $block_attr[1];
    }
    else {
      $attrs['blocktype'] = 'block';
    }

  }

  /**
   * Create the string of the swap.
   */
  public function theme($attrs, $text) {

    switch ($attrs['blocktype']) {
      case 'block':
        $block = \Drupal\block\Entity\Block::load($attrs['blockid']);
        $block_content = \Drupal::entityManager()
          ->getViewBuilder($attrs['blocktype'])->view($block);
        break;

      case 'block_content':
        $block = \Drupal::entityManager()
          ->loadEntityByUuid($attrs['blocktype'], $attrs['blockid']);
        $block_content = \Drupal::entityManager()
          ->getViewBuilder($attrs['blocktype'])->view($block);
        break;

      case 'views_block':
        $view_attr = explode("-", $attrs['blockid']);
        $view = \Drupal\views\Views::getView($view_attr[0]);

        $bol = $view->access($view_attr[1], \Drupal::currentUser());

        $block_content = $view->render($view_attr[1]);
        break;
    }

    return render($block_content);

  }

}
