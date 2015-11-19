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
class AccordionElementAttributesForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'swap_accordion_element_attributes_form';
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
      '#title' => 'Accordion Element',
      '#group' => 'swaps_formTabs',
    );

    $form['swaps_attributes']['swaps_accritem_parentid'] = array(
      '#type' => 'textfield',
      '#title' => 'Id of the Accordion Container',
      '#size' => 30,
      '#description' => t('Necessary for the proper functioning of the accordion.'),
    );

    $form['swaps_attributes']['swaps_accritem_title'] = array(
      '#type' => 'textfield',
      '#title' => 'Title',
      '#size' => 30,
    );

    $form['swaps_attributes']['swaps_accritem_id'] = array(
      '#type' => 'textfield',
      '#title' => 'Accordion Element ID',
      '#size' => 30,
    );

    $form['swaps_attributes']['swaps_accritem_collapse'] = array(
      '#type' => 'checkbox',
      '#title' => t('Collapse'),
    );

    $form['swaps_attributes']['swaps_accritem_content'] = array(
      '#type' => 'textarea',
      '#title' => 'Content',
    );

    SwapDefaultAttributes::getDefaultFormElements($form);
    SwapDefaultAttributes::getButtonsElements($form);

    // Delete the default id control.
    unset($form['swaps_classID']['swaps_id']);

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

    $settings['title'] = $input['swaps_accritem_title'];
    $settings['content'] = $input['swaps_accritem_content'];
    $settings['collapse'] = $input['swaps_accritem_collapse'] == NULL ? '0' : '1';
    $settings['id'] = $input['swaps_accritem_id'];
    $settings['parentid'] = $input['swaps_accritem_parentid'];

    SwapDefaultAttributes::getDefaultFormElementsValues($settings, $input, 'swap_accritem');

    // ---------------------------------------------------------------.
    // Create the ajax response.
    // ---------------------------------------------------------------.

    $visual_settings = array(
      'visualContentLayout' => array('attributes' => $settings));
    $response = new AjaxResponse();
    $response->addCommand(new CloseModalDialogCommand());
    $response->addCommand(new SettingsCommand($visual_settings, TRUE));

    return $response;

  }
}
