<?php
/**
 * @file
 * Contains \Drupal\visual_content_layout\Controller\VisualContentLayoutController.
 */


namespace Drupal\visual_content_layout\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;

/**
 * Class VisualContentLayoutController.
 *
 * @package Drupal\visual_content_layout\Controller
 */
class VisualContentLayoutController extends ControllerBase {

  /**
   * Displays modal form for select swap.
   */
  public function displayModalSelectForm() {
    $response = new AjaxResponse();
    $title = $this->t('Choose one swap');

    $form = \Drupal::formBuilder()->getForm('Drupal\visual_content_layout\Form\VisualContentLayoutSelectForm');
    $form['#attached']['library'][] = 'core/drupal.dialog.ajax';

    $modalOptions = array('width' => '1200', 'height' => 'auto');
    $response->addCommand(new OpenModalDialogCommand($title, $form, $modalOptions));
    return $response;
  }

  /**
   * Displays modal form for input the attributes of the swap
   */
  public function displayModalAttributesForm() {
    $response = new AjaxResponse();
    $swap = $_GET[swap];

    $form = \Drupal::formBuilder()->getForm('Drupal\swaps\Form\SwapAttributesForm', $swap);
    $form['#attached']['library'][] = 'core/drupal.dialog.ajax';

    $modalOptions = array('width' => '1428', 'height' => 'auto');
    $response->addCommand(new OpenModalDialogCommand("Swap Settings", $form, $modalOptions));
    return $response;
  }

}
