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
class ColumnAttributesForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'swap_column_attributes_form';
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
      '#title' => 'Swap',
      '#group' => 'swaps_formTabs',
    );

    $options = array(
      'xs' => 'xs',
      'sm' => 'sm',
      'md' => 'md',
      'lg' => 'lg');

    $form['swaps_attributes']['swaps_column_size'] = array(
      '#type' => 'select',
      '#title' => 'Column Size',
      '#options' => $options,
      '#default_value' => 'xs',
    );

    $options = array();
    for ($i = 1; $i <= 12; $i++) {
      $options[$i] = $i;
    }

    $form['swaps_attributes']['swaps_column_number'] = array(
      '#type' => 'select',
      '#title' => 'Column Number',
      '#options' => $options,
      '#default_value' => '1',
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
   * Custom submit for ajax call.
   */
  public function ajaxSubmit(array &$form, FormStateInterface $form_state) {

    // ---------------------------------------------------------------.
    // Get the own attributes values of the swap.
    // ---------------------------------------------------------------.

    // Get all the swaps plugins.
    $manager = \Drupal::service('plugin.manager.swaps');
    $swaps = $manager->getDefinitions();
    $swap = $swaps['column'];

    $input = $form_state->getUserInput();
    $settings = array();

    $settings['size'] = $input['swaps_column_size'];
    $settings['number'] = $input['swaps_column_number'];

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
