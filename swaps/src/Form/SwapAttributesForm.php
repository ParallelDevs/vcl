<?php
/**
 * @file
 * Contains \Drupal\visual_content_layout\Form\VisualContentLayoutForm.
 */

namespace Drupal\swaps\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\CloseModalDialogCommand;
use Drupal\Core\Ajax\OpenModalDialogCommand;
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

    //search the swap that hace the name of the variable $name
    foreach($swaps as $swap){
      if($swap['name'] == $name){
        break;
      }
    }

    //get the attributes annotation and split by the ","
    $attributes = $swap['attributes'];
    $attributesList = explode(',', $attributes);

    //process all the attributes of the swap
      foreach($attributesList as $attr){

      //get the name and type of the current attribute
      list($name, $type) = explode(':', $attr);

      //process depending on the name
      switch ($type) {

        //---------------------------------------------------------------
        //                   processing text attributes
        //---------------------------------------------------------------
        case 'text':
          $form[$name] = array(
            '#type' => 'textfield',
            '#title' => t($name),
            '#size' => 60,
          );
          break;

        //---------------------------------------------------------------
        //                   processing boolean attributes
        //---------------------------------------------------------------
        case 'boolean':
          break;

        //---------------------------------------------------------------
        //                   processing select attributes
        //---------------------------------------------------------------
        case 'select':
          //separate the name from the options
          $name = substr($name, 0, -1);
          list($name, $options) = explode('[', $name);
          //validate the separate symbol
          if(strpos($options, "-") === FALSE){
            $options = explode('|', $options);
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
          $form[$name] = array(
            '#type' => 'select',
            '#title' => t($name),
            '#options' => $options,
          );
          break;

        //---------------------------------------------------------------
        //                    obviate others attributes
        //---------------------------------------------------------------
        default:
          break;
      }

    }

    $form["accept"] = array(
      '#type' => 'submit',
      '#value' => t('accept'),
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
    // Display result.
    foreach ($form_state->getValues() as $key => $value) {
      drupal_set_message($key . ': ' . $value);
    }
  }

  public function ajaxSubmit(array &$form, FormStateInterface $form_state){


  }
}
