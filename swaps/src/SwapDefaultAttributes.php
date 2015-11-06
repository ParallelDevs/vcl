<?php


/**
 * @file
 * Process all default Attributes of all swaps.
 */

namespace Drupal\swaps;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Ajax\CloseModalDialogCommand;

class SwapDefaultAttributes {

  /**
   * Get all form elements for the default swap attributes.
   */
  public function getDefaultFormElements(&$form) {

    // Create the paddings tab ------------------------------------.
    $form['swaps_paddings'] = array(
      '#type' => 'details',
      '#title' => 'Paddings',
      '#group' => 'swaps_formTabs',
    );

    $form['swaps_paddings']['swaps_paddingLeft'] = array(
      '#type' => 'textfield',
      '#title' => 'Padding Left',
      '#size' => 30,
    );

    $form['swaps_paddings']['swaps_paddingRight'] = array(
      '#type' => 'textfield',
      '#title' => 'Padding Right',
      '#size' => 30,
    );

    $form['swaps_paddings']['swaps_paddingTop'] = array(
      '#type' => 'textfield',
      '#title' => 'Padding Top',
      '#size' => 30,
    );

    $form['swaps_paddings']['swaps_paddingBottom'] = array(
      '#type' => 'textfield',
      '#title' => 'Padding Bottom',
      '#size' => 30,
    );

    // Create the margins tab ------------------------------------.
    $form['swaps_margins'] = array(
      '#type' => 'details',
      '#title' => 'Margins',
      '#group' => 'swaps_formTabs',
    );

    $form['swaps_margins']['swaps_marginLeft'] = array(
      '#type' => 'textfield',
      '#title' => 'Margin Left',
      '#size' => 30,
    );

    $form['swaps_margins']['swaps_marginRight'] = array(
      '#type' => 'textfield',
      '#title' => 'Margin Right',
      '#size' => 30,
    );

    $form['swaps_margins']['swaps_marginTop'] = array(
      '#type' => 'textfield',
      '#title' => 'Margin Top',
      '#size' => 30,
    );

    $form['swaps_margins']['swaps_marginBottom'] = array(
      '#type' => 'textfield',
      '#title' => 'Margin Bottom',
      '#size' => 30,
    );

    // Create the Classes, & ID tab ------------------------------------.
    $form['swaps_classID'] = array(
      '#type' => 'details',
      '#title' => 'Classes, ID & Style',
      '#group' => 'swaps_formTabs',
    );

    $form['swaps_classID']['swaps_extraClass'] = array(
      '#type' => 'textfield',
      '#title' => 'Extra Class',
      '#size' => 30,
    );

    $form['swaps_classID']['swaps_id'] = array(
      '#type' => 'textfield',
      '#title' => 'ID',
      '#size' => 30,
    );

    $options = array(
      'default' => 'Default',
      'left' => 'Left',
      'center' => 'Center',
      'right' => 'Right',
    );

    $form['swaps_classID']['swaps_textAlign'] = array(
      '#type' => 'select',
      '#title' => 'Text Align',
      '#options' => $options,
      '#default_value' => 'default',
    );

    $form['swaps_classID']['swaps_cssStyles'] = array(
      '#type' => 'textarea',
      '#title' => 'Extra Class',
      '#description' => t('Wrong code here might cause problems in the style'),
    );

    // Create the Background tab ------------------------------------.
    $form['swaps_background'] = array(
      '#type' => 'details',
      '#title' => 'Background',
      '#group' => 'swaps_formTabs',
    );

    $form['swaps_background']['swaps_backgroundColor'] = array(
      '#type' => 'textfield',
      '#title' => 'Background Color',
      '#size' => 30,
      '#attributes' => array('class' => array('colorpicker_input')),
    );
  }

  /**
   * Get buttons for accept and cancel.
   */
  public function getButtonsElements(&$form) {

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
  }

  /**
   * Get all values set by the user in the default elements.
   */
  public function getDefaultFormElementsValues(&$settings, &$input, $swap_id) {

    // ---------------------------------------------------------------
    // Get the default attributes of the style.
    // ---------------------------------------------------------------

    ($input['swaps_paddingLeft'] != "") ?
      $settings['paddingLeft'] = $input['swaps_paddingLeft'] : "";

    ($input['swaps_paddingRight'] != "") ?
      $settings['paddingRight'] = $input['swaps_paddingRight'] : "";

    ($input['swaps_paddingTop'] != "") ?
      $settings['paddingTop'] = $input['swaps_paddingTop'] : "";

    ($input['swaps_paddingBottom'] != "") ?
      $settings['paddingBottom'] = $input['swaps_paddingBottom'] : "";

    ($input['swaps_marginLeft'] != "") ?
      $settings['marginLeft'] = $input['swaps_marginLeft'] : "";

    ($input['swaps_marginRight'] != "") ?
      $settings['marginRight'] = $input['swaps_marginRight'] : "";

    ($input['swaps_marginTop'] != "") ?
      $settings['marginTop'] = $input['swaps_marginTop'] : "";

    ($input['swaps_marginBottom'] != "") ?
      $settings['marginBottom'] = $input['swaps_marginBottom'] : "";

    ($input['swaps_textAlign'] != "default" && $input['swaps_textAlign'] != NULL) ?
      $settings['textAlign'] = $input['swaps_textAlign'] : "";

    ($input['swaps_cssStyles'] != "") ?
      $settings['css-styles'] = $input['swaps_cssStyles'] : "";

    ($input['swaps_extraClass'] != "") ?
      $settings['extraClass'] = $input['swaps_extraClass'] : "";

    ($input['swaps_id'] != "") ?
      $settings['id'] = $input['swaps_id'] : "";

    ($input['swaps_backgroundColor'] != "") ?
      $settings['backgroundColor'] = $input['swaps_backgroundColor'] : "";

    // Get all the swaps plugins.
    $manager = \Drupal::service('plugin.manager.swaps');
    $swaps = $manager->getDefinitions();
    $swap = $swaps[$swap_id];

    // ---------------------------------------------------------------
    // Get the default attributes values of the swap (required for visual help).
    // ---------------------------------------------------------------
    $settings['swapId'] = $swap['id'];
    $settings['swapName'] = $swap['name'];
    $settings['container'] = $swap['container'];
  }

  /**
   * Get all values set by the user in the default elements.
   */
  public function cancelAjaxResponse() {
    $response = new AjaxResponse();
    $title = t('Choose one swap');

    $form = \Drupal::formBuilder()
      ->getForm('Drupal\visual_content_layout\Form\VisualContentLayoutSelectForm');
    $form['#attached']['library'][] = 'core/drupal.dialog.ajax';

    $modal_options = array('width' => '50%', 'height' => 'auto');
    $response->addCommand(new CloseModalDialogCommand());
    $response->addCommand(new OpenModalDialogCommand($title, $form, $modal_options));
    return $response;
  }
}
