<?php
/**
 * @file
 * Contains \Drupal\visual_content_layout\Form\VisualContentLayoutForm.
 */

namespace Drupal\swaps\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\swaps\SwapDefaultAttributes;
use Drupal\Core\Ajax\CloseModalDialogCommand;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Ajax\SettingsCommand;
use Drupal\Core\Ajax\AjaxResponse;

/**
 * Contribute form.
 */
class DivAttributesForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'swap_div_attributes_form';
  }
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    // Create the tab container.
    $form['swaps_formTabs'] = array(
      '#type' => 'vertical_tabs',
      '#default_tab' => 'swapAttributes',
    );

    // Create the swapAttributes tab ------------------------------------.
    $form['swaps_attributes'] = array(
      '#type' => 'details',
      '#title' => 'Div',
      '#group' => 'swaps_formTabs',
    );

    $options = array(
      'row' => 'Row',
      'container' => 'Container',
      'normal' => 'Normal');

    $form['swaps_attributes']['swaps_div_type'] = array(
      '#type' => 'select',
      '#title' => 'Div Type',
      '#options' => $options,
      '#default_value' => 'row',
    );

    $form['swaps_attributes']['swaps_div_class'] = array(
      '#type' => 'textfield',
      '#title' => 'Div Class',
      '#size' => 30,
      '#description' => 'Only works if you select the type normal, otherwise It will be discarded',
    );

    SwapDefaultAttributes::getDefaultFormElements($form);
    SwapDefaultAttributes::getButtonsElements($form);

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

  /**
   * Custom ajax submit for cancel button.
   */
  public function ajaxCancelSubmit(array &$form, FormStateInterface $form_state) {

    $response = SwapDefaultAttributes::cancelAjaxResponse();
    return $response;

  }

  /**
   * Custom submit for ajax call.
   */
  public function ajaxSubmit(array &$form, FormStateInterface $form_state) {

    // ---------------------------------------------------------------.
    // Get the own attributes values of the swap.
    // ---------------------------------------------------------------.

    $input = $form_state->getUserInput();
    $settings = array();

    $settings['type'] = $input['swaps_div_type'];

    // Get div_class input only in normal div type.
    if ($settings['type'] == 'normal') {
      $settings['class'] = $input['swaps_div_class'];
    }

    SwapDefaultAttributes::getDefaultFormElementsValues($settings, $input, 'swap_div');

    // ---------------------------------------------------------------.
    // Create the ajax response.
    // ---------------------------------------------------------------.

    $visual_settings = array(
      'visualContentLayout' => array('attributes' => $settings));
    $response = new AjaxResponse();
    $response->addCommand(new CloseModalDialogCommand());
    $response->addCommand(new SettingsCommand($visual_settings, FALSE));

    return $response;

  }
}
