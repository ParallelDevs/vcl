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

    // Accept button ------------------------------------.
    $form['swaps_accept'] = array(
      '#type' => 'submit',
      '#value' => t('Accept'),
      '#group' => 'swaps_attributes',
      '#ajax' => array(
        'callback' => '::ajaxSubmit',
      ),
    );

    // Cancel button ------------------------------------.
    $form['swaps_cancel'] = array(
      '#type' => 'submit',
      '#value' => t('Cancel'),
      '#group' => 'swaps_attributes',
      '#ajax' => array(
        'callback' => '::ajaxCancelSubmit',
      ),
    );

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

    $response = new AjaxResponse();
    $title = $this->t('Choose one swap');

    $form = \Drupal::formBuilder()->getForm('Drupal\visual_content_layout\Form\VisualContentLayoutSelectForm');
    $form['#attached']['library'][] = 'core/drupal.dialog.ajax';

    $modal_options = array('width' => '1200', 'height' => 'auto');
    $response->addCommand(new CloseModalDialogCommand());
    $response->addCommand(new OpenModalDialogCommand($title, $form, $modal_options));
    return $response;

  }

  /**
   * Custom submit for ajax call.
   */
  public function ajaxSubmit(array &$form, FormStateInterface $form_state) {

    // ---------------------------------------------------------------.
    // Get the own attributes values of the swap.
    // ---------------------------------------------------------------.

    // Get all the swaps plugins.
    $manager = \Drupal::service('plugin.manager.swaps');
    $swaps = $manager->getDefinitions();
    $swap = $swaps['swap_div'];

    $input = $form_state->getUserInput();
    $settings = array();

    $settings['type'] = $input['swaps_div_type'];

    // Get div_class input only in normal div type.
    if ($settings['type'] == 'normal') {
      $settings['class'] = $input['swaps_div_class'];
    }

    // ---------------------------------------------------------------.
    // Get the default attributes values of the swap (required for visual help).
    // ---------------------------------------------------------------.

    $settings['swapId'] = $swap['id'];
    $settings['swapName'] = $swap['name'];
    $settings['container'] = $swap['container'];

    SwapDefaultAttributes::getDefaultFormElementsValues($settings, $input);

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
