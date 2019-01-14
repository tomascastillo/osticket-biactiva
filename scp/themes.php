<?php
require('admin.inc.php');
require_once(INCLUDE_DIR."/class.themes.php");

//custom manage

$page = 'themes.inc.php';
//custom request

$nav->setTabActive('manage');
require(STAFFINC_DIR.'header.inc.php');
require(STAFFINC_DIR.$page);
include(STAFFINC_DIR.'footer.inc.php');
?>