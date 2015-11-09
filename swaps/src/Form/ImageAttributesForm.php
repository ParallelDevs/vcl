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
class ImageAttributesForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'swap_button_attributes_form';
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
      '#title' => 'Image',
      '#group' => 'swaps_formTabs',
    );

    $form['swaps_attributes']['swaps_img_file'] = array(
      '#type' => 'managed_file',
      '#title' => t('Upload Image'),
      '#size' => 40,
      '#default_value' => 'http://localhost/VisualContentD8/sites/default/files/vcl_images/footer-logo.png',
      '#description' => t("Accept JPG, PNG format."),
      '#upload_location' => 'public://vcl_images/'
    );

    $form['swaps_attributes']['swaps_img_height'] = array(
      '#type' => 'textfield',
      '#title' => 'Height',
      '#size' => 30,
    );

    $form['swaps_attributes']['swaps_img_width'] = array(
      '#type' => 'textfield',
      '#title' => 'Width',
      '#size' => 30,
    );

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

    // Delete the upload image if cancel.
    $fid = $form_state->getValue(array('swaps_img_file', 0));
    $file = \Drupal\file\Entity\File::load($fid);
    $file->delete();

    $file->

    $response = SwapDefaultAttributes::cancelAjaxResponse();
    return $response;

  }

  /**
   * Custom submit for ajax call.
   */
  public function ajaxSubmit(array &$form, FormStateInterface $form_state) {

    $input = $form_state->getUserInput();
    $settings = array();

    $fid = $form_state->getValue(array('swaps_img_file', 0));
    $file = \Drupal\file\Entity\File::load($fid);
    $file->setPermanent();
    $url = $file->url();

    $a = $file->getOriginalId();

    $settings['file'] = $url;
    $settings['height'] = $input['swaps_img_height'];
    $settings['width'] = $input['swaps_img_width'];

    SwapDefaultAttributes::getDefaultFormElementsValues($settings, $input, 'swap_img');
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
