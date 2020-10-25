<?php

include '../../mainfile.php';
require XOOPS_ROOT_PATH . '/header.php';

require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/include/mybbs.inc.php';
require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/class.mybbs.php';

$_form = &get_form();

$bbs_id = (int)$_form['bbs_id'];
$post_id = (int)$_form['post_id'];
$mode = $_form['mode'];

if (!$bbs_id) {
    redirect_header('index.php', 1, _MD_BBS_BBS_NO_SELECTED);
}
if (!$post_id) {
    redirect_header('index.php', 1, _MD_MYBBS_POST_ID_NO_SELECTED);
}

switch ($mode) {
    case 'check':

        $hiddens = [
            'bbs_id' => $bbs_id,
            'post_id' => $post_id,
            'mode' => 'del',
        ];
        $action = 'del.php';
        $msg = _MD_MYBBS_DELETE_CONFIRM;
        $submit = _MD_MYBBS_DELETE_SUBMIT;
        xoops_confirm($hiddens, $action, $msg, $submit);
        require XOOPS_ROOT_PATH . '/footer.php';
        exit;
        break;
    case 'del':

        MyBBS::deleteMessage($bbs_id, $post_id);
        redirect_header("index.php?bbs_id=$bbs_id", 2, _MD_MYBBS_DELETE_TRUE);

        break;
    default:
        redirect_header('index.php', 1, 'unknown mode');
        break;
}
?>
