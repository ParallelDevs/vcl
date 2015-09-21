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
  public function displayModalForm() {
    $response = new AjaxResponse();
    $title = $this->t('Choose one swap');

    $form = \Drupal::formBuilder()->getForm('Drupal\visual_content_layout\Form\VisualContentLayoutForm');
    $form['#attached']['library'][] = 'core/drupal.dialog.ajax';

    $response->addCommand(new OpenModalDialogCommand($title, $form));
    return $response;
  }

}
