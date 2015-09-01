<?php

/**
 * @file
 * Filter that enables to use patterns for create visual content more easy.
 * Drupal\visual_content_layout\Plugin\Filter\FilterVisualContent.
 *
 */

namespace Drupal\visual_content_layout\Plugin\Filter;

use Drupal\filter\FilterProcessResult;
use Drupal\filter\Plugin\FilterBase;


/**
 * Provides a filter to use shortdoces.
 *.
 *
 * @Filter(
 *   id = "filter_visualcontent",
 *   title = @Translation("Filtrate Visual Content"),
 *   description = @Translation("Use shortcodes to generate a easy content."),
 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_TRANSFORM_REVERSIBLE
 * )
 */
class FilterVisualContent extends FilterBase{

  /**
   * {@inheritdoc}
   */
  public function process($text, $langcode) {

    return $text;
  }

  /**
   * {@inheritdoc}
   */
  public function tips($long = FALSE) {


  }

}

