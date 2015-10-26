<?php
/**
 * @file
 * Contains \Drupal\visual_content_layout\Controller\VisualContentLayoutController.
 */


namespace Drupal\visual_content_layout\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Component\Serialization\Json;

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
  public function displayModalAttributesForm($swap) {
    //get the list of all modules
    $modules = \Drupal::moduleHandler()->getModuleList();
    //search the swap form in all modules
    foreach (array_keys($modules) as $name) {
      $namespace = "Drupal\\".$name."\\Form\\".$swap."AttributesForm";
      //validate the namespace have the form
      if(class_exists($namespace)){
        $form = \Drupal::formBuilder()->getForm($namespace);
        break;
      }
    }
    $form['#attached']['library'][] = 'core/drupal.dialog.ajax';
    //create the ajax response
    $response = new AjaxResponse();
    $modalOptions = array('width' => '1428', 'height' => 'auto');
    $response->addCommand(new OpenModalDialogCommand("Swap Settings", $form, $modalOptions));
    return $response;
  }

  /**
   * Displays modal form for input the attributes of the swap
   */
  public function getUpdateFormSwap($swap) {

    //get the list of all modules
    $modules = \Drupal::moduleHandler()->getModuleList();
    //search the swap form in all modules
    foreach (array_keys($modules) as $name) {
      $namespace = "Drupal\\".$name."\\Form\\".$swap."AttributesForm";
      //validate the namespace have the form
      if(class_exists($namespace)){
        $form = \Drupal::formBuilder()->getForm($namespace);
        break;
      }
    }

    $response = new AjaxResponse(render($form));
    return $response;
  }


}
