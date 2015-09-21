<?php
/**
 * @file
 * Contains \Drupal\visual_content_layout\Form\VisualContentLayoutForm.
 */

namespace Drupal\visual_content_layout\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\CloseModalDialogCommand;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Ajax\AjaxResponse;

/**
 * Contribute form.
 */
class VisualContentLayoutForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'visual_content_layout_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $manager = \Drupal::service('plugin.manager.swaps');
    $swaps = $manager->getDefinitions();

    foreach($swaps as $swap){
      $name = $swap['name'];
      $form[$name] = array(
        '#type' => 'submit',
        '#value' => t($name),
        '#ajax' => array(
          'callback' => '::ajaxSubmit',
        ),
      );
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }

  public function ajaxSubmit(array &$form, FormStateInterface $form_state){

    $triggeringElement = $form_state->getTriggeringElement();
    $name = $triggeringElement['#value'];

    $response = new AjaxResponse();
    $title = $this->t($name . 'Attributes');

    $form = \Drupal::formBuilder()->getForm('Drupal\swaps\Form\SwapAttributesForm', $name);
    $form['#attached']['library'][] = 'core/drupal.dialog.ajax';

    $response->addCommand(new OpenModalDialogCommand($title, $form));
    return $response;

  }
}
