<?php

/**
 * @file
 * Contains Drupal\swaps\Form\SwapSettingsForm.
 */

namespace Drupal\swaps\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class SwapSettingsForm.
 *
 * @package Drupal\swaps\Form
 */
class SwapSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'swaps.swapsettings_config'
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'swap_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('swaps.swapsettings_config');
    $form['enable_bootstrap'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Enable Bootstrap'),
      '#description' => $this->t(''),
      '#default_value' => $config->get('enable_bootstrap'),
    );
    $form['enable_fontawesome'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Enable FontAwesome'),
      '#description' => $this->t(''),
      '#default_value' => $config->get('enable_fontawesome'),
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('swaps.swapsettings_config')
      ->set('enable_bootstrap', $form_state->getValue('enable_bootstrap'))
      ->set('enable_fontawesome', $form_state->getValue('enable_fontawesome'))
      ->save();
  }

}
