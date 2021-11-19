<?php

namespace Drupal\guestbook\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class MakeResponseForm extends FormBase{

  public function getFormId()
  {
    return 'response_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Your Name:'),
      '#description' => $this->t('Enter your name. Note that name must be longer than 2 characters and shorter than 100 characters'),
      '#maxlength' => 100,
      '#required' => TRUE,
      '#ajax' => [
        'callback' => '::AJAXvalidateNameLength',
        'event' => 'change',
      ],
      '#suffix' => '<span class="name-valid-message valid-message"></span>'
    ];
    $form['mail'] = [
      '#type' => 'email',
      '#title' => $this->t('Your Email:'),
      '#description' => $this->t('Email must looks like \'text@text.text \'  '),
      '#required' => TRUE,
      '#ajax' => [
        'callback' => '::AJAXvalidateEmailFormat',
        'event' => 'change',
      ],
      '#suffix' => '<span class="email-valid-message valid-message"></span>'
    ];
    $form['phone'] = [
      '#type' => 'tel',
      '#title' => $this->t('Your Phone:'),
      '#description' => $this->t('Phone must be \'+380XXXXXXXXX\' format'),
      '#required' => true,
      '#placeholder' => $this->t('+380XXXXXXXXX'),
      '#default_value' => ' ',
      '#maxlength' => 13,
      '#ajax' => [
        'callback' => '::AJAXvalidatePhoneFormat',
        'event' => 'change',
      ],
    ];
    $form['message'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Your Message:'),
      '#rows' => 5,
      '#cols' => 15,
      '#description' => $this->t(''),
      '#required' => true,
      '#maxlength' => 255,
    ];
    $form['avatar'] = [
      '#type' => 'managed_file',
      '#name' => 'avatar',
      '#title'=>$this->t('Your Avatar Image:'),
      '#upload_location' => 'public://avatar',
      '#description' => $this->t('The file must be .jpeg, .jpg or .png format and less than 2MB.'),
      '#upload_validators' => [
        'file_validate_extensions' => ['png jpg jpeg'],
        'file_validate_size' => [2*1024*1024],
      ],
    ];
    $form['image'] = [
      '#type' => 'managed_file',
      '#name' => 'image',
      '#title'=>$this->t('Message Image:'),
      '#upload_location' => 'public://image',
      '#description' => $this->t('The file must be .jpeg, .jpg or .png format and less than 5MB.'),
      '#upload_validators' => [
        'file_validate_extensions' => ['png jpg jpeg'],
        'file_validate_size' => [5*1024*1024],
      ],
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#name' => 'submit',
      '#value' => $this->t('ADD CAT'),
      '#ajax' => [
        'callback' => '::submitAjaxMessage',
        'event' => 'click',
      ],
    ];

    return $form;
  }
  //Function that validate Name field on its length
  public function validateNameLength(array &$form, FormStateInterface $form_state){
    $name_len = strlen($form_state->getValue('name'));
    if ($name_len <= 2) {
      return FALSE;
    }
    return TRUE;
  }
  //Function that validate Name field on its length with Ajax
  public function AJAXvalidateNameLength(array &$form, FormStateInterface $form_state){
    $isNameValid = $this->validateNameLength($form, $form_state);
    $response = new AjaxResponse();

    if ($isNameValid){
      $name_css = ['border' => '1px solid green'];
    }
    else{
      $name_css = ['border' => '1px solid red'];
    }
    $response->addCommand(new CssCommand('#edit-name', $name_css));

    return $response;
  }

  public function validateEmailFormat(array &$form, FormStateInterface $form_state){
    if (filter_var($form_state->getValue('mail'), FILTER_VALIDATE_EMAIL)) {
      return TRUE;
    }
    return FALSE;
  }
  //Function that validate Email field with AJAX
  public function AJAXvalidateEmailFormat(array &$form, FormStateInterface $form_state){
    $valid = $this->validateEmailFormat($form, $form_state);
    $response = new AjaxResponse();

    if ($valid) {
      $css = ['border' => '1px solid green'];
    } else {
      $css = ['border' => '1px solid red'];
    }
    $response->addCommand(new CssCommand('#edit-mail', $css));

    return $response;
  }

  public function validatePhoneFormat(array &$form, FormStateInterface $form_state){
    $phone = $form_state->getValue('phone');
    if (preg_match("/[+]380[0-9]{7}/", $phone)) {
      return TRUE;
    }
    return FALSE;
  }

  public function AJAXvalidatePhoneFormat(array &$form, FormStateInterface $form_state){
    $valid = $this->validatePhoneFormat($form, $form_state);
    $response = new AjaxResponse();

    if($valid){
      $css = ['border' => '1px solid green'];
    } else {
      $css = ['border' => '1px solid red'];
    }
    $response->addCommand(new CssCommand('#edit-phone', $css));

    return $response;
  }

  public function submitForm(array &$form, FormStateInterface $form_state)
  {

  }
}
