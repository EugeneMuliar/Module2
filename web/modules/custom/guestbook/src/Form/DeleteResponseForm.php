<?php

namespace Drupal\guestbook\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

class DeleteResponseForm extends ConfirmFormBase{

  public function getQuestion()
  {
    return $this->t('Do you want to delete this response?');
  }

  public function getCancelUrl()
  {
    return new Url('guestbook.content');
  }
  public function getDescription() {
    return $this->t('Do you want to delete ?');
  }
  public function getConfirmText() {
    return $this->t('Delete');
  }
  public function getCancelText() {
    return t('Cancel');
  }

  public function getFormId()
  {
    return 'Delete Response';
  }
  public function buildForm(array $form, FormStateInterface $form_state, $responseID = NULL){
    $this->id = $responseID;
    return parent::buildForm($form, $form_state);
  }
  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    parent::validateForm($form, $form_state);
  }
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $query = \Drupal::database();
    $query->delete('responses')
          ->condition('id', $this->id)
          ->execute();
    \Drupal::messenger()->addStatus('You deleted the response');
    $form_state->setRedirect('guestbook.content');
  }
}
