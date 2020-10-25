<?

function mybbs_search($queryarray, $andor, $limit, $offset, $userid)
{
    global $xoopsDB;

    $mybbs_posts  = $xoopsDB->prefix("mybbs_posts");
    $mybbs_master = $xoopsDB->prefix("mybbs_master");

    $sql = "SELECT p.post_id,p.bbs_id,p.post_time,p.uid,p.title,p.message,p.root_id,p.parent_id
FROM {$mybbs_posts} p
LEFT JOIN {$mybbs_master} m ON p.bbs_id=m.bbs_id
WHERE m.status = 1 and p.status = 1";
    if ($userid != 0) {
        $sql .= " AND p.uid= {$userid} ";
    }
    // because count() returns 1 even if a supplied variable
    // is not an array, we must check if $querryarray is really an array
    if (is_array($queryarray) && $count = count($queryarray)) {
        $sql .= " AND ((p.title LIKE '%$queryarray[0]%' OR p.message LIKE '%$queryarray[0]%')";
        for ($i = 1; $i < $count; $i++) {
            $sql .= " $andor ";
            $sql .= "(p.title LIKE '%$queryarray[$i]%' OR p.message LIKE '%$queryarray[$i]%')";
        }
        $sql .= ") ";
    }

    $sql .= "ORDER BY p.post_time DESC";

    $result = $xoopsDB->query($sql, $limit, $offset);
    $ret    = [];
    $i      = 0;
    while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
        $link = "res.php?bbs_id={$myrow['bbs_id']}";
        if ($myrow['parent_id'] == 0) {
            $link .= "&amp;parent_id={$myrow['post_id']}&amp;root_id={$myrow['post_id']}";
        } else {
            $link .= "&amp;parent_id={$myrow['parent_id']}&amp;root_id={$myrow['root_id']}";
        }
        $ret[$i]['link']  = $link;
        $ret[$i]['title'] = $myrow['title'];
        $ret[$i]['time']  = $myrow['post_time'];
        $ret[$i]['uid']   = $myrow['uid'];
        $i++;
    }
    return $ret;
}

?>
