<?php
/**
 * @file
 * Contains \Drupal\visual_content_layout\Form\VisualContentLayoutForm.
 */

namespace Drupal\swaps\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\CloseModalDialogCommand;
use Drupal\swaps\SwapDefaultAttributes;
use Drupal\Core\Ajax\SettingsCommand;
use Drupal\Core\Ajax\AjaxResponse;
/**
 * Contribute form.
 */
class BlockAttributesForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'swap_block_attributes_form';
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
      '#title' => 'Block',
      '#group' => 'swaps_formTabs',
    );

    // Create the options with all blocks.
    $options = array();
    $manager = \Drupal::service('plugin.manager.block');
    $blocks = $manager->getDefinitionsForContexts();

    // Iterate all block searching for custom and view block.
    foreach ($blocks as $plugin_id => $plugin_definition) {
      // Get plugin type.
      $plugin_type = explode(":", $plugin_id)[0];
      if ($plugin_type == "block_content" || $plugin_type == "views_block") {
        $options[$plugin_id] = $plugin_definition['admin_label'];
      }
    }

    $blocks = \Drupal::entityManager()->getListBuilder("block")->load();

    // Iterate all system blocks.
    foreach ($blocks as $plugin_id => $plugin_definition) {
      $options[$plugin_id] = $plugin_definition->label();
    }

    $form['swaps_attributes']['swaps_block_blockid'] = array(
      '#type' => 'select',
      '#title' => 'Block ID',
      '#options' => $options,
      '#default_value' => $plugin_id,
      '#size' => 15,
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

    $response = SwapDefaultAttributes::cancelAjaxResponse();
    return $response;

  }

  /**
   * Custom submit for ajax call.
   */
  public function ajaxSubmit(array &$form, FormStateInterface $form_state) {

    // ---------------------------------------------------------------.
    // Get the own attributes values of the swap.
    // ---------------------------------------------------------------.

    $input = $form_state->getUserInput();
    $settings = array();

    $settings['blockid'] = $input['swaps_block_blockid'];

    SwapDefaultAttributes::getDefaultFormElementsValues($settings, $input, 'swap_block');

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
