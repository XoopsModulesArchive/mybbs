<?

global $xoopsDB;
if (is_object($xoopsDB)) {
    $sql = "select * from " . $xoopsDB->prefix("mybbs_master") . " where status = 1 ";
    $res = $xoopsDB->query($sql);
    $ary = [];
    while (false !== ($row = $xoopsDB->fetchArray($res))) {
        $hash  = [
            'name' => $row["bbs_name"],
            'url'  => "index.php?bbs_id=" . $row["bbs_id"],
        ];
        $ary[] = $hash;
    }
    if (sizeof($ary) > 1) {
        $modversion['sub'] = $ary;
    }
}
?>
