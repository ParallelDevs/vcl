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

    // Attributes and parameters for the link.
    $attributes = array(
      'attributes' => array(
        'class' => array('visual-content-layout-image-manager'),
      ),
    );

    $parameters = array(
      'fid' => 0
    );

    // Create link to image manager.
    $link = '<a class="visual-content-layout-image-manager" href="/VisualContentD8/visual_content_layout/swap_image_manager/0">Open Image Manager</a>';

    $form['swaps_attributes']['swaps_img_url'] = array(
      '#type' => 'textfield',
      '#group' => 'swaps_attributes',
      '#disabled' => TRUE,
      '#prefix' => $link,
      '#suffix' => '<img class="image_preview" src="" height="150">',
    );

    $form['swaps_attributes']['swaps_img_fid'] = array(
      '#type' => 'hidden',
      '#value' => 0,
      '#attributes' => array('id' => array('edit-swaps-img-fid')),
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

    $fid = $input['swaps_img_fid'];
    $file = \Drupal\file\Entity\File::load($fid);
    $file->setPermanent();
    $url = $file->url();

    $a = $file->getOriginalId();

    $settings['url'] = $url;
    $settings['height'] = $input['swaps_img_height'];
    $settings['width'] = $input['swaps_img_width'];
    $settings['fid'] = $fid;

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
