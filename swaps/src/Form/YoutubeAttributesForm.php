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
use Drupal\Core\Ajax\SettingsCommand;
use Drupal\Core\Ajax\AjaxResponse;
/**
 * Contribute form.
 */
class YoutubeAttributesForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'swap_youtube_attributes_form';
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
      '#title' => 'Youtube',
      '#group' => 'swaps_formTabs',
    );

    $form['swaps_attributes']['swaps_youtube_url'] = array(
      '#type' => 'textfield',
      '#title' => 'Youtube URL',
      '#size' => 40,
      '#attributes' => array('class' => array('swaps_youtube')),
      '#suffix' => '<img class="youtube_preview" src="http://img.youtube.com/vi/0.jpg;" height="150"/>',

    );

    $form['swaps_attributes']['swaps_youtube_height'] = array(
      '#type' => 'textfield',
      '#title' => 'Height',
      '#size' => 30,
    );

    $form['swaps_attributes']['swaps_youtube_width'] = array(
      '#type' => 'textfield',
      '#title' => 'Width',
      '#size' => 30,
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

    $input = $form_state->getUserInput();
    $settings = array();

    $settings['url'] = $input['swaps_youtube_url'];
    $settings['height'] = $input['swaps_youtube_height'];
    $settings['width'] = $input['swaps_youtube_width'];

    SwapDefaultAttributes::getDefaultFormElementsValues($settings, $input, 'swap_youtube');

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
