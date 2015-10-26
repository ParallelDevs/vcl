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
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\SettingsCommand;
use Drupal\Core\Ajax\AjaxResponse;
/**
 * Contribute form.
 */
class DivAttributesForm extends FormBase{
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

    //create the tab container
    $form['swaps_formTabs'] = array(
      '#type' => 'vertical_tabs',
      '#default_tab' => 'swapAttributes',
    );

    //create the swapAttributes tab ------------------------------------
    $form['swaps_attributes'] = array(
      '#type' => 'details',
      '#title' => 'Swap',
      '#group' => 'swaps_formTabs',
    );

    $options = array( 'row' => 'Row',
                      'container' => 'Container',
                      'normal' => 'Normal');

    $form['swaps_attributes']['swaps_div_type'] = array(
      '#type' => 'select',
      '#title' => 'Div Type',
      '#options' => $options,
      '#default_value' => 'row',
      '#ajax' => array(
        'callback' => '::selectChange',
        'effect' => 'fade',
      ),
    );

    $form['swaps_attributes']['swaps_div_class'] = array(
      '#type' => 'textfield',
      '#title' => 'Div Class',
      '#size' => 30,
      '#prefix' => '<div id="swaps_div_class" class="hidden">',
      '#suffix' => '</div>',
    );

    SwapDefaultAttributes::getDefaultFormElements($form);

    //Accept button ------------------------------------
    $form['swaps_accept'] = array(
      '#type' => 'submit',
      '#value' => t('Accept'),
      '#group' => 'swaps_attributes',
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

  public function selectChange(array &$form, FormStateInterface $form_state){

    $input = $form_state->getUserInput();
    $div_type = $input['swaps_div_type'];
    $response = new AjaxResponse();

    if($div_type == 'normal'){
      $response->addCommand(new CssCommand('#swaps_div_class',
        array('display' => 'block')));
    }else{
      $response->addCommand(new CssCommand('#swaps_div_class',
        array('display' => 'none')));
    }

    return $response;

  }

  public function ajaxSubmit(array &$form, FormStateInterface $form_state){

    //---------------------------------------------------------------
    //            get the own attributes values of the swap
    //---------------------------------------------------------------

    //get all the swaps plugins
    $manager = \Drupal::service('plugin.manager.swaps');
    $swaps = $manager->getDefinitions();
    $swap = $swaps['column'];

    $input = $form_state->getUserInput();
    $settings = array();

    $settings['class'] = $input['swaps_div_class'];
    $settings['type'] = $input['swaps_div_type'];

    //---------------------------------------------------------------
    // get the default attributes values of the swap (required for visual help)
    //---------------------------------------------------------------

    $settings['swapId'] = $swap['id'];
    $settings['swapName'] = $swap['name'];
    $settings['container'] = $swap['container'];

    SwapDefaultAttributes::getDefaultFormElementsValues($settings, $input);

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
