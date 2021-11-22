<?php

namespace Drupal\guestbook\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\file\Entity\File;

class GuestbookController extends ControllerBase{
  public function content(){
    $guestbook['response_form'] = \Drupal::formBuilder()->getForm('Drupal\guestbook\Form\MakeResponseForm');

    $query = \Drupal::database()->select('responses', 'r');
    $query->fields('r', ['id', 'author_name', 'email', 'phone', 'avatar', 'image,', 'message', 'timestamp'])->orderBy('timestamp', 'desc');
    $data = $query->execute()->fetchAll();
    $responses = [];

    foreach ($data as $field){
      if(!$field->avatar == 0){
        $file_avatar = File::load($field->avatar);
        $avatar_uri = $file_avatar->getFileUri();
      }
      else{
        $avatar_uri = '/modules/custom/guestbook/images/default_user.png';
      }
      if(!$field->image == 0){
        $file_image = File::load($field->image);
        $image_uri = $file_image->getFileUri();
      }

      $avatar_img = [
        '#theme'=> 'image_style',
        '#style_name' => 'wide',
        '#uri' => $avatar_uri,
        '#title' => 'avatar',
        '#width' => 50,
        '#height' => 50,
      ];
      $image_img = [
        '#theme'=> 'image_style',
        '#style_name' => 'wide',
        '#uri' => $image_uri,
        '#title' => 'avatar',
        '#width' => 100,
        '#height' => 100,
      ];
      $responses[] = [
        'id'=>$field->id,
        'name'=>$field->author_name,
        'email'=>$field->email,
        'phone'=>$field->phone,
        'avatar'=>$avatar_img,
        'image'=>$image_img,
        'created_time'=>$field->timestamp
      ];
    }

    $build = [
      '#theme' => 'response-page-template',
      '#responses' => $responses,
      '#form' => $guestbook
    ];
    return $build;
  }
}
