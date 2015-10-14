<?php
/**
 * @file
 * Contains \Drupal\visual_content_layout\Form\VisualContentLayoutForm.
 */

namespace Drupal\swaps\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\CloseModalDialogCommand;
use Drupal\Core\Ajax\SettingsCommand;
use Drupal\Core\Ajax\AjaxResponse;

/**
 * Contribute form.
 */
class SwapAttributesForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'swap_attributes_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $name = NULL) {

    //get all the swaps plugins
    $manager = \Drupal::service('plugin.manager.swaps');
    $swaps = $manager->getDefinitions();

    //search the swap that have the name of the variable $name
    foreach($swaps as $swap){
      if($swap['name'] == $name){
        break;
      }
    }

    //store the name of the swap
    $form['swap'] = array('#type' => 'hidden', '#value' => $name);

    //create the tab container
    $form['formTabs'] = array(
      '#type' => 'vertical_tabs',
      '#default_tab' => 'swapAttributes',
    );

    //create the swapAttributes tab ------------------------------------
    $form['swapAttributes'] = array(
      '#type' => 'details',
      '#title' => 'Swap',
      '#group' => 'formTabs',
    );

    //get the attributes annotation and split by the ","
    $attributes = $swap['attributes'];
    $attributesList = explode(',', trim($attributes));

    //process all the attributes of the swap
    foreach($attributesList as $attr){

      $attr = trim($attr);

      //get the name and type of the current attribute
      $attr = substr(trim($attr), 1, -1);
      $attr = explode('|', $attr);
      $name = trim($attr[1]);
      $type = trim($attr[2]);

      $title = str_replace( "[" , "" , trim($attr[0]));

      //process depending on the name
      switch ($type) {

        //---------------------------------------------------------------
        //                   processing text attributes
        //---------------------------------------------------------------
        case 'text':

          $id = $name.'_textfield';

          $form['swapAttributes'][$name] = array(
            '#type' => 'textfield',
            '#title' => $title,
            '#size' => 60,
            '#attributes' => array('id' => array($id)),
          );
          break;

        //---------------------------------------------------------------
        //                   processing boolean attributes
        //---------------------------------------------------------------
        case 'boolean':
          break;

        //---------------------------------------------------------------
        //                   processing color attributes
        //---------------------------------------------------------------
        case 'color':

          $id =  $name . '_colorpicker';

          $form['swapAttributes'][$name] = array(
            '#type' => 'textfield',
            '#title' => $title,
            '#size' => 60,
            '#default_value' => '#123456',
            '#attributes' => array('id' => array($id),
                                   'class' => array('colorpicker_input')),
          );
          break;

        //---------------------------------------------------------------
        //                   processing select attributes
        //---------------------------------------------------------------
        case 'select':

          $id = $name . '_select';

          //get the options
          $options = trim($attr[3]);
          //validate the separate symbol for int select o string select
          if(strpos($options, "-") === FALSE){
            $elements = explode(":" , $options);
            $options = array();
            foreach($elements as $element){
              $options[$element] = $element;
            }
          }else{
            //get the first and the last number of the sequence
            list($first, $last) = explode('-', $options);
            //validate are numbers
            if(!is_numeric($first) || !is_numeric($last)){
              break;
            }
            //validate the first is greater than the last
            if($first>$last){
              $aux = $first;
              $first = $last;
              $last = $aux;
            }
            $options = array();
            for($i = $first; $i<= $last; $i++){
              $options[$i] = $i;
            }
          }
          //create the form with the options
          $form['swapAttributes'][$name] = array(
            '#type' => 'select',
            '#title' => $title,
            '#options' => $options,
            '#attributes' => array('id' => array($id)),
          );
          break;

        //---------------------------------------------------------------
        //                    obviate others attributes
        //---------------------------------------------------------------
        default:
          break;
      }

    }

    //create the paddings tab ------------------------------------
    $form['paddings'] = array(
      '#type' => 'details',
      '#title' => 'Paddings',
      '#group' => 'formTabs',
    );

    $form['paddings']['padding-left'] = array(
      '#type' => 'textfield',
      '#title' => 'Padding Left',
      '#size' => 30,
    );

    $form['paddings']['padding-right'] = array(
      '#type' => 'textfield',
      '#title' => 'Padding Right',
      '#size' => 30,
    );

    $form['paddings']['padding-top'] = array(
      '#type' => 'textfield',
      '#title' => 'Padding Top',
      '#size' => 30,
    );

    $form['paddings']['padding-bottom'] = array(
      '#type' => 'textfield',
      '#title' => 'Padding Bottom',
      '#size' => 30,
    );

    //create the margins tab ------------------------------------
    $form['margins'] = array(
      '#type' => 'details',
      '#title' => 'Margins',
      '#group' => 'formTabs',
    );

    $form['margins']['margin-left'] = array(
      '#type' => 'textfield',
      '#title' => 'Margin Left',
      '#size' => 30,
    );

    $form['margins']['margin-right'] = array(
      '#type' => 'textfield',
      '#title' => 'Margin Right',
      '#size' => 30,
    );

    $form['margins']['margin-top'] = array(
      '#type' => 'textfield',
      '#title' => 'Margin Top',
      '#size' => 30,
    );

    $form['margins']['margin-bottom'] = array(
      '#type' => 'textfield',
      '#title' => 'Margin Bottom',
      '#size' => 30,
    );

    //create the Classes, & ID tab ------------------------------------
    $form['classID'] = array(
      '#type' => 'details',
      '#title' => 'Classes, ID & Style',
      '#group' => 'formTabs',
    );

    $form['classID']['extraClass'] = array(
      '#type' => 'textfield',
      '#title' => 'Extra Class',
      '#size' => 30,
    );

    $form['classID']['id'] = array(
      '#type' => 'textfield',
      '#title' => 'ID',
      '#size' => 30,
    );

    $options = array( 'default' => 'Default', 'left' => 'Left',
                      'center' => 'Center', 'right' => 'Right', );

    $form['classID']['text-align'] = array(
      '#type' => 'select',
      '#title' => 'Text Align',
      '#options' => $options,
      '#default_value' => 'default',
    );

    $form['classID']['css-styles'] = array(
      '#type' => 'textarea',
      '#title' => 'Extra Class',
      '#description' => t('Wrong code here might cause problems in the style'),
    );

    //create the Background tab ------------------------------------
    $form['background'] = array(
      '#type' => 'details',
      '#title' => 'Background',
      '#group' => 'formTabs',
    );

    $form['background']['background-color'] = array(
      '#type' => 'textfield',
      '#title' => 'Background Color',
      '#size' => 30,
      '#attributes' => array('id' => array('background-color_colorpicker'),
        'class' => array('colorpicker_input')),
    );

    //Accept button ------------------------------------
    $form['accept'] = array(
      '#type' => 'submit',
      '#value' => t('Accept'),
      '#group' => 'swapAttributes',
      '#ajax' => array(
        'callback' => '::ajaxSubmit',
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

  public function ajaxSubmit(array &$form, FormStateInterface $form_state){

    //---------------------------------------------------------------
    //            get the own attributes values of the swap
    //---------------------------------------------------------------

    //get all the swaps plugins
    $manager = \Drupal::service('plugin.manager.swaps');
    $swaps = $manager->getDefinitions();

    $input = $form_state->getUserInput();
    $settings = array();

    //search the swap that have the name of the variable $name
    foreach($swaps as $swap){
      if($swap['name'] == $input['swap']){
        break;
      }
    }

    //get the attributes annotation and split by the ","
    $attributes = $swap['attributes'];
    $attributesList = explode(',', $attributes);

    //process all the attributes of the swap
    foreach($attributesList as $attr) {

      //get the name of the current attribute
      $name = explode('|', $attr);
      $name = trim($name[1]);

      $settings[$name] = $input[$name];

    }

    //---------------------------------------------------------------
    //            get the default attributes values of the swap
    //---------------------------------------------------------------

    $settings['swapId'] = $swap['id'];
    $settings['swapName'] = $swap['name'];
    $settings['container'] = $swap['container'];


    //---------------------------------------------------------------
    //            get all de default attributes input
    //---------------------------------------------------------------

    ($input['padding-left'] != "") ?
      $settings['padding-left'] = $input['padding-left'] : "";

    ($input['padding-right'] != "") ?
      $settings['padding-right'] = $input['padding-right'] : "";

    ($input['padding-top'] != "") ?
      $settings['padding-top'] = $input['padding-top'] : "";

    ($input['padding-bottom'] != "") ?
      $settings['padding-bottom'] = $input['padding-bottom'] : "";

    ($input['margin-left'] != "") ?
      $settings['margin-left'] = $input['margin-left'] : "";

    ($input['margin-right'] != "") ?
      $settings['margin-right'] = $input['margin-right'] : "";

    ($input['margin-top'] != "") ?
      $settings['margin-top'] = $input['margin-top'] : "";

    ($input['margin-bottom'] != "") ?
      $settings['margin-bottom'] = $input['margin-bottom'] : "";

    ($input['text-align'] != "") ?
      $settings['text-align'] = $input['text-align'] : "";

    ($input['css-styles'] != "") ?
      $settings['css-styles'] = $input['css-styles'] : "";

    ($input['extraClass'] != "") ?
      $settings['extraClass'] = $input['extraClass'] : "";

    ($input['id'] != "") ?
      $settings['id'] = $input['id'] : "";

    ($input['background-color'] != "") ?
      $settings['background-color'] = $input['background-color'] : "";

    //---------------------------------------------------------------
    //            create the ajax response
    //---------------------------------------------------------------

    $visualSettings = array('visualContentLayout' => array(
                                'attributes' => $settings));
    $response = new AjaxResponse();
    $response->addCommand(new CloseModalDialogCommand());
    $response->addCommand(new SettingsCommand($visualSettings,FALSE));

    return $response;


  }
}
