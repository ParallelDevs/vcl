<?php
/**
 * @file
 * Contains
 *      \Drupal\visual_content_layout\Controller\VisualContentLayoutController.
 */

namespace Drupal\visual_content_layout\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Ajax\OpenDialogCommand;

/**
 * Class VisualContentLayoutController.
 *
 * @package Drupal\visual_content_layout\Controller
 */
class VisualContentLayoutController extends ControllerBase {

  /**
   * Choose form for select all swaps.
   */
  public function chooseAllSwapsSelectForm() {
    $form = \Drupal::formBuilder()->getForm('Drupal\visual_content_layout\Form\VisualContentLayoutSelectForm');
    return $this->displayModalSelectForm($form);
  }

  /**
   * Choose form for select specific swap.
   */
  public function chooseSwapSelectForm($swap) {
    $form = \Drupal::formBuilder()->getForm('Drupal\visual_content_layout\Form\VisualContentLayoutSelectForm', $swap);
    return $this->displayModalSelectForm($form);
  }

  /**
   * Return modal with select form.
   */
  public function displayModalSelectForm($form) {
    $response = new AjaxResponse();
    $title = $this->t('Choose one swap');
    $form['#attached']['library'][] = 'core/drupal.dialog.ajax';
    $modal_options = array('width' => '50%', 'height' => 'auto');
    $response->addCommand(new OpenModalDialogCommand($title, $form, $modal_options));
    return $response;
  }

  /**
   * Displays modal form for input the attributes of the swap.
   */
  public function displayModalAttributesForm($swap) {
    $swap = str_replace(" ", "", $swap);
    // Get the list of all modules.
    $modules = \Drupal::moduleHandler()->getModuleList();
    // Search the swap form in all modules.
    foreach (array_keys($modules) as $name) {
      $namespace = "Drupal\\" . $name . "\\Form\\" . $swap .  "AttributesForm";
      // Validate the namespace have the form.
      if (class_exists($namespace)) {
        $form = \Drupal::formBuilder()->getForm($namespace);
        break;
      }
    }
    $form['#attached']['library'][] = 'core/drupal.dialog.ajax';
    // Create the ajax response.
    $response = new AjaxResponse();
    $modal_options = array('width' => '60%', 'height' => 'auto');
    $response->addCommand(new OpenModalDialogCommand("Swap Settings", $form, $modal_options));
    return $response;
  }

  /**
   * Displays modal form for input the attributes of the swap.
   */
  public function getUpdateFormSwap($swap) {
    // Get the list of all modules.
    $modules = \Drupal::moduleHandler()->getModuleList();
    // Search the swap form in all modules.
    foreach (array_keys($modules) as $name) {
      $namespace = "Drupal\\" . $name . "\\Form\\" . $swap . "AttributesForm";
      // Validate the namespace have the form.
      if (class_exists($namespace)) {
        $form = \Drupal::formBuilder()->getForm($namespace);
        break;
      }
    }
    $response = new AjaxResponse(render($form));
    return $response;
  }

  /**
   * Displays modal form for manage images.
   */
  public function displayImageManager($fid) {

    $form = \Drupal::formBuilder()->getForm('Drupal\swaps\Form\ImageManagerForm', $fid);

    $form['#attached']['library'][] = 'core/drupal.dialog.ajax';
    // Create the ajax response.
    $response = new AjaxResponse();
    $modal_options = array(
      'width' => '50%',
      'height' => 'auto',
      'modal' => 'true',
    );
    $response->addCommand(new OpenDialogCommand("#dialog", "Image Manager", $form, $modal_options));
    return $response;

  }
}
