<?

include "../../mainfile.php";
require XOOPS_ROOT_PATH . "/header.php";

require_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/include/mybbs.inc.php";
require_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/class/class.mybbs.php";

$_form =& get_form();

$bbs_id = (int)$_form["bbs_id"];
$mode   = $_form["mode"];

if (!$bbs_id) {
    redirect_header('index.php', 1, _MD_BBS_BBS_NO_SELECTED);
}

$bbs_info = MyBBS::getBbsInfo($bbs_id);

if (is_object($xoopsUser)) {
    $uid = $xoopsUser->uid();
} else {
    $uid = 0;
}

if (!strlen($_form['message'])) {
    redirect_header("index.php?bbs_id=$bbs_id", 2, _MD_MYBBS_ERRMES_MESSAGE);
} elseif (!strlen($_form['title'])) {
    redirect_header("index.php?bbs_id=$bbs_id", 2, _MD_MYBBS_ERRMES_TITLE);
} elseif ($bbs_info["guest_post"] == 0 && $uid == 0) {
    redirect_header("index.php?bbs_id=$bbs_id", 2, _MD_MYBBS_GUEST_POST_DENIED);
}

switch ($mode) {
    case "regist":
        if (MyBBS::postMessage($_form["bbs_id"], $_form)) {
            redirect_header("index.php?bbs_id={$bbs_id}", 2, _MD_MYBBS_POST_TRUE);
        } else {
            redirect_header("index.php?bbs_id={$bbs_id}", 2, _MD_MYBBS_POST_FALSE);
        }
        break;
    case "res":
        if (!$_form["parent_id"] || !$_form["root_id"]) {
            redirect_header('index.php', 2, 'parent_id or root_id is null');
        }
        if (MyBBS::postMessage($_form["bbs_id"], $_form, $_form["parent_id"], $_form["root_id"])) {
            redirect_header("index.php?bbs_id={$bbs_id}", 2, _MD_MYBBS_POST_TRUE);
        } else {
            redirect_header("index.php?bbs_id={$bbs_id}", 2, _MD_MYBBS_POST_FALSE);
        }
        break;
    default:
        redirect_header('index.php', 3, 'mode error');
        break;
}

?>
