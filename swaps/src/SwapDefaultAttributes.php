<?php


/**
 * @file
 * Process all default Attributes of all swaps
 */

namespace Drupal\swaps;

class SwapDefaultAttributes {

  /**
   * Get all form elements for the default swap attributes.
   */
  public function getDefaultFormElements(&$form) {

    //create the paddings tab ------------------------------------
    $form['swaps_paddings'] = array(
      '#type' => 'details',
      '#title' => 'Paddings',
      '#group' => 'swaps_formTabs',
    );

    $form['swaps_paddings']['swaps_padding-left'] = array(
      '#type' => 'textfield',
      '#title' => 'Padding Left',
      '#size' => 30,
    );

    $form['swaps_paddings']['swaps_padding-right'] = array(
      '#type' => 'textfield',
      '#title' => 'Padding Right',
      '#size' => 30,
    );

    $form['swaps_paddings']['swaps_padding-top'] = array(
      '#type' => 'textfield',
      '#title' => 'Padding Top',
      '#size' => 30,
    );

    $form['swaps_paddings']['swaps_padding-bottom'] = array(
      '#type' => 'textfield',
      '#title' => 'Padding Bottom',
      '#size' => 30,
    );

    //create the margins tab ------------------------------------
    $form['swaps_margins'] = array(
      '#type' => 'details',
      '#title' => 'Margins',
      '#group' => 'swaps_formTabs',
    );

    $form['swaps_margins']['swaps_margin-left'] = array(
      '#type' => 'textfield',
      '#title' => 'Margin Left',
      '#size' => 30,
    );

    $form['swaps_margins']['swaps_margin-right'] = array(
      '#type' => 'textfield',
      '#title' => 'Margin Right',
      '#size' => 30,
    );

    $form['swaps_margins']['swaps_margin-top'] = array(
      '#type' => 'textfield',
      '#title' => 'Margin Top',
      '#size' => 30,
    );

    $form['swaps_margins']['swaps_margin-bottom'] = array(
      '#type' => 'textfield',
      '#title' => 'Margin Bottom',
      '#size' => 30,
    );

    //create the Classes, & ID tab ------------------------------------
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

    $options = array( 'default' => 'Default', 'left' => 'Left',
      'center' => 'Center', 'right' => 'Right', );

    $form['swaps_classID']['swaps_text-align'] = array(
      '#type' => 'select',
      '#title' => 'Text Align',
      '#options' => $options,
      '#default_value' => 'default',
    );

    $form['swaps_classID']['swaps_css-styles'] = array(
      '#type' => 'textarea',
      '#title' => 'Extra Class',
      '#description' => t('Wrong code here might cause problems in the style'),
    );

    //create the Background tab ------------------------------------
    $form['swaps_background'] = array(
      '#type' => 'details',
      '#title' => 'Background',
      '#group' => 'swaps_formTabs',
    );

    $form['swaps_background']['swaps_background-color'] = array(
      '#type' => 'textfield',
      '#title' => 'Background Color',
      '#size' => 30,
      '#attributes' => array('id' => array('background-color_colorpicker'),
        'class' => array('colorpicker_input')),
    );
  }

  /**
   * Get all values set by the user in the default elements.
   */
  public function getDefaultFormElementsValues(&$settings, &$input){

    ($input['swaps_padding-left'] != "") ?
      $settings['padding-left'] = $input['swaps_padding-left'] : "";

    ($input['swaps_padding-right'] != "") ?
      $settings['padding-right'] = $input['swaps_padding-right'] : "";

    ($input['swaps_padding-top'] != "") ?
      $settings['padding-top'] = $input['swaps_padding-top'] : "";

    ($input['swaps_padding-bottom'] != "") ?
      $settings['padding-bottom'] = $input['swaps_padding-bottom'] : "";

    ($input['swaps_margin-left'] != "") ?
      $settings['margin-left'] = $input['swaps_margin-left'] : "";

    ($input['swaps_margin-right'] != "") ?
      $settings['margin-right'] = $input['swaps_margin-right'] : "";

    ($input['swaps_margin-top'] != "") ?
      $settings['margin-top'] = $input['swaps_margin-top'] : "";

    ($input['swaps_margin-bottom'] != "") ?
      $settings['margin-bottom'] = $input['swaps_margin-bottom'] : "";

    ($input['swaps_text-align'] != "default") ?
      $settings['text-align'] = $input['swaps_text-align'] : "";

    ($input['swaps_css-styles'] != "") ?
      $settings['css-styles'] = $input['swaps_css-styles'] : "";

    ($input['swaps_extraClass'] != "") ?
      $settings['extraClass'] = $input['swaps_extraClass'] : "";

    ($input['swaps_id'] != "") ?
      $settings['id'] = $input['swaps_id'] : "";

    ($input['swaps_background-color'] != "") ?
      $settings['background-color'] = $input['swaps_background-color'] : "";

  }
}
