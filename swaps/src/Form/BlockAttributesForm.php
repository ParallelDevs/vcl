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
use Drupal\block\Entity\Block as EntityBlock;
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
    );

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
    $swap = $swaps['swap_block'];

    $input = $form_state->getUserInput();
    $settings = array();

    $settings['blockid'] = $input['swaps_block_blockid'];

    // ---------------------------------------------------------------
    // Get the default attributes values of the swap (required for visual help).
    // ---------------------------------------------------------------

    $settings['swapId'] = $swap['id'];
    $settings['swapName'] = $swap['name'];
    $settings['container'] = $swap['container'];

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
