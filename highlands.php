<?php

// Highlands autoloader
require_once realpath(dirname(__FILE__) . '/core/Autoloader.php');
Autoloader::register();

// Smarty autoloader
require_once realpath(dirname(__FILE__) . '/libs/smarty/Autoloader.php');
Smarty_Autoloader::register();


class Highlands extends Core{}