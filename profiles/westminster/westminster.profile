<?php

 function westminster_user_login($account) {
  // We want to redirect user on login.
  $roles = $account->getRoles();
  $numRoles = sizeof($roles);
  if($numRoles){
    $query = \Drupal::entityQuery('node')
        ->condition('status', 1)
        ->condition('type','login_redirect');   
    $nids = $query->execute();
    $pages = \Drupal\node\Entity\Node::loadMultiple($nids);
    if(sizeof($pages)){
      for($i = 0; $i < $numRoles; $i++){
        foreach($pages as $page){
          for($j = 0; $j < $page->field_role->count(); $j++){
            if($page->field_role->target_id == $roles[$i]){
               $url = $page->field_url->getValue();
               if(sizeof($url)){
                  $uri = $url[0]['uri'];
                  $uri = str_replace('internal:/','',$uri);
                  $response = \Symfony\Component\HttpFoundation\RedirectResponse::create($uri);
                  $response->send();
                  return;
               }
            }
          }
        }
      }
    }
  }
}
