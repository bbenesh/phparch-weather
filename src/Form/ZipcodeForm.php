<?php
/**
 * @file
 * Contains \Drupal\example\Form\ExampleForm.
 * Created by PhpStorm.
 * User: Bree
 * Date: 3/25/17
 * Time: 11:07 AM
 */
namespace Drupal\phparch\Form;

use \Drupal\Core\Form\FormBase;
use \Drupal\Core\Form\FormStateInterface;

class ZipcodeForm extends FormBase
{

  public function getFormId () {
    return 'zipcode_form';
  }

  public function buildForm (array $form, FormStateInterface $form_state) {
    $form['zipcode'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Your zip code')
    );
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Check Conditions'),
      '#button_type' => 'primary',
    );
    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    //parent::validateForm($form, $form_state);
    $value = $form_state->getValue('zipcode');
    if (false == ctype_digit($value) || strlen($value) !== 5) {
      $form_state->setErrorByName(
        'zipcode',
        $this->t('The value you entered is not a valid 5 digit zipcode, please enter it again.'));
    }
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $form_state->setRedirect('phparch.zip',
      ['zipcode' => $form_state->getValue('zipcode')]);
  }

}

