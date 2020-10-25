<?php

$modversion['name']        = 'mybbs';
$modversion['version']     = 0.2;
$modversion['description'] = 'my bbs';

$modversion['credits']  = "negi-mix";
$modversion['author']   = "negi-mix";
$modversion['license']  = "GPL";
$modversion['official'] = 0;
$modversion['image']    = "images/mybbs.gif";
$modversion['dirname']  = "mybbs";

// Template
$modversion['templates'][0]['file']        = 'mybbs.html';
$modversion['templates'][0]['description'] = '';

$modversion['templates'][1]['file']        = 'mybbs_list.html';
$modversion['templates'][1]['description'] = '';

$modversion['templates'][2]['file']        = 'mybbs_msgs.html';
$modversion['templates'][2]['description'] = '';

$modversion['templates'][3]['file']        = 'mybbs_form.html';
$modversion['templates'][3]['description'] = '';

$modversion['templates'][4]['file']        = 'mybbs_res.html';
$modversion['templates'][4]['description'] = '';

$modversion['templates'][5]['file']        = 'mybbs_edit.html';
$modversion['templates'][5]['description'] = '';

// Block
$modversion['blocks'][1]['file']      = "mybbs_new.php";
$modversion['blocks'][1]['name']      = _MI_MYBBS_BNAME1;
$modversion['blocks'][1]['show_func'] = "b_mybbs_newpost";
$modversion['blocks'][1]['options']   = "10|0";
//$modversion['blocks'][1]['edit_func'] = "b_mybbs_newpost_edit";
$modversion['blocks'][1]['template'] = 'mybbs_block_newpost.html';

// Sql
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

$modversion['tables'][0] = "mybbs_posts";
$modversion['tables'][1] = "mybbs_master";

// Admin
$modversion['hasAdmin']   = 1;
$modversion['adminindex'] = "admin/index.php";
//$modversion['adminmenu']  = "admin/menu.php";

// Menu
$modversion['hasMain'] = 1;

// Search
$modversion['hasSearch']      = 1;
$modversion['search']['file'] = "include/search.inc.php";
$modversion['search']['func'] = "mybbs_search";

require_once "submenu.php";
?>
