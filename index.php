<?php

// $Id: index.php,v 1.1 2006/03/27 15:37:47 mikhail Exp $
include "../../mainfile.php";

require_once XOOPS_ROOT_PATH . "/header.php";
require_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/include/mybbs.inc.php";
require_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/class/class.mybbs.php";

$_form =& get_form();

$bbs_id = (int)@$_form['bbs_id'];
$start  = (int)@$_form['start'];

$bbs_list = MyBBS::getBbsList();
//print_d($bbs_list);

if (sizeof($bbs_list) == 0) {
    die(_MD_NO_BBS_MASTER);
}
$xoopsTpl->assign('bbs_list', $bbs_list);

if (!$bbs_id) {
    if (sizeof($bbs_list) == 1) {
        $bbs_id = $bbs_list[0]['bbs_id'];
    } else { // Multi BBS
        $GLOBALS['xoopsOption']['template_main'] = 'mybbs_list.html';
        // $myts = MyTextSanitizer::getInstance();

        require XOOPS_ROOT_PATH . "/footer.php";
        exit;
    }
}

$bbs_info = MyBBS::getBbsInfo($bbs_id, $sanitize = true);

if (!$bbs_info) {
    die(_MD_NO_BBS_MASTER);
}

$per_page = $bbs_info["page_limit"];
[$all_num, $messages] = MyBBS::getMessages($bbs_id, ["start" => $start, "limit" => $per_page]);

//print_d($messages);

$post_allowed = MyBBS::isPostAllowed($bbs_id, $bbs_info);

if (is_object($xoopsUser)) {
    $uid      = $xoopsUser->uid();
    $is_admin = $xoopsUser->isAdmin($xoopsModule->mid());
} else {
    $uid      = 0;
    $is_admin = false;
}

/******* Page Navi *********/
if ($all_num > $per_page) {
    require XOOPS_ROOT_PATH . '/class/pagenav.php';
    $extra_arg = "bbs_id=$bbs_id";
    $nav       = new XoopsPageNav($all_num, $per_page, $start, "start", $extra_arg);
    $xoopsTpl->assign('bbs_pagenav', $nav->renderNav(4));
} else {
    $xoopsTpl->assign('bbs_pagenav', '');
}
/******* Page Navi *********/

$form_message_tarea = MyBbs::getTarea('', $bbs_info['tarea_dhtml'], $bbs_info['tarea_font'], $bbs_info['tarea_smily']);

// Template
$GLOBALS['xoopsOption']['template_main'] = 'mybbs.html';

// SetFrom
$xoopsTpl->assign('bbs_name', $bbs_info["bbs_name"]);
$xoopsTpl->assign('form_action', "regist.php");
$xoopsTpl->assign('form_parent', 0);
$xoopsTpl->assign('form_mode', "regist");
$xoopsTpl->assign('form_bbs_id', $bbs_id);
$xoopsTpl->assign('form_submit', _MD_MYBBS_POST_SUBMIT);
$xoopsTpl->assign('form_message_tarea', $form_message_tarea);

$xoopsTpl->assign('uid', $uid);
$xoopsTpl->assign('lang_post_denied', _MD_MYBBS_GUEST_POST_DENIED);
$xoopsTpl->assign('guest_post', $bbs_info["guest_post"]);
$xoopsTpl->assign('guest_name', $xoopsConfig["anonymous"]);
$xoopsTpl->assign('post_allowed', $post_allowed);
$xoopsTpl->assign('is_admin', $is_admin);
$xoopsTpl->assign('bbs_info', $bbs_info);

// SetComments
$xoopsTpl->assign('messages', $messages);
require XOOPS_ROOT_PATH . "/footer.php";
?>
