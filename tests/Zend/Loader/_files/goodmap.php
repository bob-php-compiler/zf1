<?php
$ds       = DIRECTORY_SEPARATOR;
$basePath = dirname(__FILE__) . "$ds..";
return array(
    'Zend_Loader_StandardAutoloaderTest' => $basePath . $ds . 'StandardAutoloaderTest.php',
    'Zend_Loader_ClassMapAutoloaderTest' => $basePath . $ds . 'ClassMapAutoloaderTest.php',
);
