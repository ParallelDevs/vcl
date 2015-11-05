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

class HighlightAttributesForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'swap_hgl_attributes_form';
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
      '#title' => 'Highligth',
      '#group' => 'swaps_formTabs',
    );

    $form['swaps_attributes']['swaps_hgl_text'] = array(
      '#type' => 'textfield',
      '#title' => 'Text',
      '#size' => 30,
    );

    $form['swaps_attributes']['swaps_hgl_fontcolor'] = array(
      '#type' => 'textfield',
      '#title' => 'Font Color',
      '#size' => 30,
      '#attributes' => array('class' => array('colorpicker_input')),
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

    $settings['text'] = $input['swaps_hgl_text'];
    $settings['fontcolor'] = $input['swaps_hgl_fontcolor'];

    SwapDefaultAttributes::getDefaultFormElementsValues($settings, $input, 'swap_hgl');

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
