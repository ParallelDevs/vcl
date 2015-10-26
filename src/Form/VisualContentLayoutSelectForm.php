<?php
/**
 * @file
 * Contains \Drupal\visual_content_layout\Form\VisualContentLayoutSelectForm.
 */

namespace Drupal\visual_content_layout\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 *  Form for select one swap for the visual help of the module.
 */
class VisualContentLayoutSelectForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'visual_content_layout_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    //get all the swaps definitions
    $manager = \Drupal::service('plugin.manager.swaps');
    $swaps = $manager->getDefinitions();
    //create a markup for each swap that display the form for the attributes
    foreach($swaps as $swap){
      $name = $swap['name'];
      //attributes for the url to the swap form
      $attributes = array(
        'attributes' => array(
          'class' => array('use-ajax btn btn-6 btn-6b'),
        ),
      );
      $url = Url::fromRoute('visual_content_layout.swap_attributes_form',
                            array('swap'=>$name), $attributes);
      $internal_link = \Drupal::l(t($name), $url);
      //form element of the swap
      $form[$name] = array(
        '#type' => 'markup',
        '#markup' => $internal_link,
      );
    }
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

}
