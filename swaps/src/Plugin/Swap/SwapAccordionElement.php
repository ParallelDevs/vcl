<?php

/**
 * @file
 * Contains \Drupal\vcl\Plugin\Swap\.
 */

namespace Drupal\swaps\Plugin\Swap;

use Drupal\swaps\SwapBase;

/**
 * Provides a 'Accordion Element' swap.
 *
 * @Swap(
 *   id = "swap_accritem",
 *   name = "Accordion Element",
 *   container = true,
 *   description = @Translation("Add a element for the list."),
 * )
 */

class SwapAccordionElement extends SwapBase {

  /**
   * Get all attributes of the swap and validate it.
   */
  public function processCallback($attrs, $text) {
    $attrs = $this->setAttrs(array(
      'title' => 'title',
      'collapse' => '0',
      'id' => 'accritem',
      'parentid' => 'accordion',
    ),
      $attrs
    );

    // Concatenate the classes
    if (array_key_exists('extraclass', $attrs)) {
      if (array_key_exists('class', $attrs)) {
        $attrs['class'] = $this->addClass($attrs['class'], $attrs['extraclass']);
      }else{
        $attrs['class'] = $attrs['extraclass'];
      }
    }
    $attrs['style'] = $this->getStyle($attrs);

    return $this->theme($attrs, $text);
  }

  /**
   * Create the string of the swap.
   */
  public function theme($attrs, $text) {

    // Get parent id and own id for the accordion.
    $href = '#' . $attrs['id'];
    $data = '#' . $attrs['parentid'];

    // Validate is collapsed.
    if ($attrs['collapse'] == '1') {
      $collapsed_class = 'collapsed';
      $expanded = 'false';
      $in = '';
    }
    else {
      $collapsed_class = '';
      $expanded = 'false';
      $in = 'in';
    }

    $accordion_element = '<div class="panel panel-default">'
      . '<div class="panel-heading"><h4 class="panel-title"><a class="'
      . $collapsed_class . '" aria-expanded="' . $expanded . '"'
      . 'data-toggle="collapse" data-parent="' . $data . '" href="' . $href . '">'
      . $attrs['title'] . '</a></h4></div><div id="' . $attrs['id'] . '" class="panel-collapse collapse ' . $in . '">'
      . '<div class="panel-body"><p>' . $attrs['content'] . '</p> ' . $text . '</div>'
      . '</div></div>';

    return $accordion_element;
  }
}
