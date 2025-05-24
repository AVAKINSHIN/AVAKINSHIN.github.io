<?php
$errors = array();
$values = array();
foreach (array('fio', 'phone', 'email', 'year', 'gender', 'language', 'biography', 'accept') as $v)           
{                                                                                                                                 
  $errors[$v] = !empty($_COOKIE[$v.'_error']);                                                                                           
  if ($errors[$v])                                                                                                                                  
  {                                  
    $messages[] = sprintf('<div class="error"><strong>%s</strong></div>', strip_tags($_COOKIE[$v.'_error']));                                   
    setcookie($v.'_error', '', 100000);                                                                                                                         
    setcookie($v.'_value', '', 100000);                                                                                                                 
  }                                                                                                                                                  
}
?>
