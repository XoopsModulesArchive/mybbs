<?php

function b_mybbs_newpost($options)
{
    $db = XoopsDatabaseFactory::getDatabaseConnection();

    $myts = MyTextSanitizer::getInstance();

    global $xoopsConfig;

    $limit = 5;

    $block = [];

    $sql = sprintf(
        'SELECT b.*,u.uid AS users_uid ,u.user_avatar,u.uname,m.bbs_name FROM %s b LEFT JOIN %s u ON(b.uid = u.uid) LEFT JOIN %s m ON (b.bbs_id = m.bbs_id) WHERE b.status != 0 ORDER BY b.post_id DESC LIMIT %d',
        $db->prefix('mybbs_posts'),
        $db->prefix('users'),
        $db->prefix('mybbs_master'),
        $limit
    );

    if (!$res = $db->query($sql)) {
        return false;
    }

    while (false !== ($row = $db->fetchArray($res))) {
        // for display

        $row['post_time'] = formatTimestamp($row['post_time'], 'm');

        $row['message'] = $myts->displayTarea(mb_substr($row['message'], 0, 20));

        $row['title'] = $myts->displayTarea($row['title']);

        if (0 == $row['uid']) {
            $row['uname'] = $row['name'] . '@' . $xoopsConfig['anonymous'];
        }

        $block['messages'][] = &$row;

        unset ($row);
    }

    return $block;
}

?>
