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
                  if ($uri) {
                    $response = \Symfony\Component\HttpFoundation\RedirectResponse::create($uri);
                    $response->send();
                  }
                  return;
               }
            }
          }
        }
      }
    }
  }
}

/*
  When redirecting upon logging out, <front> incorrectly resolves to fasley due to /users (default path) requiring administrative permissions to view

  This emits the following fatal error:
  Uncaught PHP Exception InvalidArgumentException: "Cannot redirect to an empty URL." at /vendor/symfony/http-foundation/RedirectResponse.php line 76

  Currently the best solution (read: hackish, but with the fewest side-effects) is hooking into user_logout() here and creating our own redirect to base_path()
  Then we use hook_theme_suggestions_alter() to suggest the correct hook for the login page (so that it's displayed standalone rather than within page.html.twig)

  Ideally we could use the westminster_helper module to alter the <front> route (or override UserController::logout) but this created undesirable results:
  https://www.drupal.org/docs/8/api/routing-system/altering-existing-routes-and-adding-new-routes-based-on-dynamic-ones
*/

function westminster_user_logout() {
  $response = \Symfony\Component\HttpFoundation\RedirectResponse::create(base_path());
  $response->send();
}

function westminster_theme_suggestions_alter(&$suggestions, &$variables, $hook) {
  $currentPath = \Drupal::service('path.current')->getPath();

  if ($currentPath == '/') {
    $suggestions[] = $hook . '__user__login';
  }
}
