<?php

// $Id: edit.php,v 1.1 2006/03/27 15:37:47 mikhail Exp $
include '../../mainfile.php';
require XOOPS_ROOT_PATH . '/header.php';

require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/include/mybbs.inc.php';
require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/class.mybbs.php';

$_form = &get_form();

$bbs_id = (int)$_form['bbs_id'];
$post_id = (int)$_form['post_id'];
$mode = $_form['mode'];

$title = $_form['title'];
$message = $_form['message'];

if (!$bbs_id) {
    redirect_header('index.php', 1, _MD_BBS_BBS_NO_SELECTED);
}
if (!$post_id) {
    redirect_header('index.php', 1, _MD_MYBBS_POST_ID_NO_SELECTED);
}

switch ($mode) {
    case 'form':
        // Show Edit Form
        $myts = MyTextSanitizer::getInstance();
        $GLOBALS['xoopsOption']['template_main'] = 'mybbs_edit.html';

        $post = MyBBS::getOneMessage($bbs_id, $post_id);
        $bbs_info = MyBBS::getBbsInfo($bbs_id);

        $post_allowed = MyBBS::isPostAllowed($bbs_id, $bbs_info);

        $form_message_tarea = MyBbs::getTarea(
            htmlspecialchars($post['message']),
            $bbs_info['tarea_dhtml'],
            $bbs_info['tarea_font'],
            $bbs_info['tarea_smily']
        );

        // Set Form
        $xoopsTpl->assign('form_bbs_id', $bbs_id);
        $xoopsTpl->assign('form_post_id', $post_id);
        $xoopsTpl->assign('form_action', $_SERVER['SCRIPT_NAME']);
        $xoopsTpl->assign('form_mode', 'edit');
        $xoopsTpl->assign('form_title', htmlspecialchars($post['title']));
        $xoopsTpl->assign('form_message', htmlspecialchars($post['message']));
        $xoopsTpl->assign('form_submit', _MD_MYBBS_EDIT_SUBMIT);
        $xoopsTpl->assign('bbs_name', $bbs_info['bbs_name']);
        $xoopsTpl->assign('post_allowed', $post_allowed);
        $xoopsTpl->assign('is_admin', $xoopsUser->isAdmin($xoopsModule->mid()));
        $xoopsTpl->assign('form_message_tarea', $form_message_tarea);

        require XOOPS_ROOT_PATH . '/footer.php';
        exit;

    case 'edit':

        MyBBS::editMessage($bbs_id, $post_id, $title, $message);
        redirect_header("index.php?bbs_id=$bbs_id", 2, _MD_MYBBS_EDIT_TRUE);

        break;
    default:
        redirect_header('index.php', 1, 'unknown mode');
        break;
}
?>
