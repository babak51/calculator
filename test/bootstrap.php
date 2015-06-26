<?php

spl_autoload_register(function ($className) {
  $classPath = str_replace(
    array('_', '\\'), 
    DIRECTORY_SEPARATOR, 
    $className
  ) . '.php';
  require $classPath;
});
?>
