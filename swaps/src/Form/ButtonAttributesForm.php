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
class ButtonAttributesForm extends FormBase{
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

    $form['swaps_attributes']['swaps_button_text'] = array(
      '#type' => 'textfield',
      '#title' => 'Title',
      '#size' => 30,
    );

    $form['swaps_attributes']['swaps_button_url'] = array(
      '#type' => 'textfield',
      '#title' => 'URL',
      '#size' => 30,
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

  public function ajaxSubmit(array &$form, FormStateInterface $form_state){

    //---------------------------------------------------------------
    //            get the own attributes values of the swap
    //---------------------------------------------------------------

    //get all the swaps plugins
    $manager = \Drupal::service('plugin.manager.swaps');
    $swaps = $manager->getDefinitions();
    $swap = $swaps['button'];

    $input = $form_state->getUserInput();
    $settings = array();

    $settings['text'] = $input['swaps_button_text'];
    $settings['url'] = $input['swaps_button_url'];


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