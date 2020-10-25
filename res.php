<?php

// $Id: res.php,v 1.1 2006/03/27 15:37:47 mikhail Exp $
include "../../mainfile.php";

require_once XOOPS_ROOT_PATH . "/header.php";
require_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/include/mybbs.inc.php";
require_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/class/class.mybbs.php";
//require_once XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/class/formdhtmltextareale.php";

$GLOBALS['xoopsOption']['template_main'] = 'mybbs_res.html';

$_form =& get_form();

$_form["start"] = 0;
$bbs_id         = (int)$_form['bbs_id'];
$parent_id      = (int)$_form['parent_id'];
$root_id        = (int)$_form['root_id'];

if (!$bbs_id) {
    redirect_header("index.php?bbs_id=$bbs_id", 1, _MD_MYBBS_BBS_NO_SELECTED);
}
if (!$parent_id) {
    redirect_header("index.php?bbs_id=$bbs_id", 1, _MD_MYBBS_MESSAGE_NO_SELECTE);
}
if (!$root_id) {
    redirect_header("index.php?bbs_id=$bbs_id", 1, _MD_MYBBS_MESSAGE_NO_SELECTED);
}

[$all_num, $messages] = MyBBS::getMessages($bbs_id, ["post_id" => $root_id]);

if ($all_num == 0) {
    redirect_header("index.php?bbs_id=$bbs_id", 1, _MD_MYBBS_MESSAGE_NO_SELECTED);
}
$post_allowed = MyBBS::isPostAllowed($bbs_id);
$bbs_info     = MyBBS::getBbsInfo($bbs_id);

$form_message_tarea = MyBbs::getTarea('', $bbs_info['tarea_dhtml'], $bbs_info['tarea_font'], $bbs_info['tarea_smily']);

// Res Title
$res_title = "Re: " . $messages[$root_id]['title'];

// SetFrom
$xoopsTpl->assign('bbs_name', $bbs_info["bbs_name"]);
$xoopsTpl->assign('form_action', "regist.php");
$xoopsTpl->assign('form_parent', $parent_id);
$xoopsTpl->assign('form_mode', "res");
$xoopsTpl->assign('form_bbs_id', $bbs_id);
$xoopsTpl->assign('form_root_id', $root_id);
$xoopsTpl->assign('form_title', $res_title);
// SetComments
$xoopsTpl->assign('messages', $messages);
$xoopsTpl->assign('form_message_tarea', $form_message_tarea);
$xoopsTpl->assign('post_allowed', $post_allowed);
$xoopsTpl->assign('form_submit', _MD_MYBBS_POST_SUBMIT);
$xoopsTpl->assign('lang_res_message', _MD_MYBBS_RES_MESSAGE);
require XOOPS_ROOT_PATH . "/footer.php";
?>
