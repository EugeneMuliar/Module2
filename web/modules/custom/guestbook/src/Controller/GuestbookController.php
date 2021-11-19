<?php

namespace Drupal\guestbook\Controller;

use Drupal\Core\Controller\ControllerBase;

class GuestbookController extends ControllerBase{
  public function content(){
    $guestbook['response_form'] = \Drupal::formBuilder()->getForm('Drupal\guestbook\Form\MakeResponseForm');

    $build = [
      '#theme' => 'response-page-template',
      '#form' => $guestbook
    ];
    return $build;
  }
}
