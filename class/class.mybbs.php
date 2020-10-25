<?php

class MyBBS
{
    public function getBbsInfo($bbs_id, $sanitize = false)
    {
        global $xoopsDB;

        $myts = MyTextSanitizer::getInstance();

        //$this->db = $xoopsDB;

        $sql = 'select * from ' . $xoopsDB->prefix('mybbs_master') . " where bbs_id = $bbs_id";

        $res = $xoopsDB->query($sql);

        $num = $xoopsDB->getRowsNum($res);

        $row = $xoopsDB->fetchArray($res);

        if ($sanitize) {
            $row['bbs_note'] = $myts->displayTarea($row['bbs_note'], $html = 1);

            $row['bbs_contents'] = $myts->displayTarea($row['bbs_contents'], $html = 1);
        }

        return $row;
    }

    public function getBbsList()
    {
        global $xoopsDB;

        $myts = MyTextSanitizer::getInstance();

        $sql = 'select * from ' . $xoopsDB->prefix('mybbs_master') . ' where status = 1 order by sort_order';

        $res = $xoopsDB->query($sql);

        $bbs_list = [];

        while (false !== ($row = $xoopsDB->fetchArray($res))) {
            //sanitize

            //displayTarea(&$text, $html = 0, $smiley = 1, $xcode = 1, $image = 1, $br = 1)

            $row['bbs_note'] = $myts->displayTarea($row['bbs_note'], $html = 1);

            $row['bbs_contents'] = $myts->displayTarea($row['bbs_contents'], $html = 1);

            $bbs_list[] = $row;
        }

        return $bbs_list;
    }

    public function getMessages($bbs_id, $args)
    {
        global $xoopsDB;

        global $xoopsConfig;

        $myts = MyTextSanitizer::getInstance();

        extract($args);

        // Set default

        if (!isset($start)) {
            $start = 0;
        }

        if (!isset($limit)) {
            $limit = 20;
        }

        // BBS Info

        $bbs_info = self::getBbsInfo($bbs_id);

        $guest_name = $bbs_info['guest_name'];

        $guest_avatar = 'blank.gif';

        // Count Parent Messages

        $sql = sprintf(
            'SELECT COUNT(*) AS num FROM %s WHERE parent_id = 0 AND status = 1 AND bbs_id = %s',
            $xoopsDB->prefix('mybbs_posts'),
            $bbs_id
        );

        if (isset($post_id) && 0 != $post_id) {
            $sql .= sprintf(' and post_id = %d ', $post_id);
        }

        //echo $sql;

        $res = $xoopsDB->query($sql);

        $ary = $xoopsDB->fetchArray($res);

        $num = $ary['num'];

        // Get Parent Messages

        $ext_where = '';

        if (isset($post_id) && 0 != $post_id) {
            $ext_where = sprintf(' and b.post_id = %d ', $post_id);
        }

        $sql = sprintf(
            "select b.*,u.uid as users_uid ,u.user_avatar,u.uname from %s as b left join %s as u on ( b.uid = u.uid ) where b.bbs_id = %d and b.parent_id = 0 and b.status = 1 $ext_where order by b.post_id desc limit %d,%d",

            $xoopsDB->prefix('mybbs_posts'),
            $xoopsDB->prefix('users'),
            $bbs_id,
            $start,
            $limit
        );

        $res = $xoopsDB->query($sql);

        //echo $sql;

        $parent_id_ary = [];

        $list = [];

        while (false !== ($row = $xoopsDB->fetchArray($res))) {
            $row['post_time'] = date(DATE_FORMAT, $row['post_time']);

            if (0 == $row['uid']) {
                $row['uname'] = $row['name'] . '@' . $xoopsConfig['anonymous'];

                $row['user_avatar'] = $guest_avatar;
            } elseif (!$row['users_uid']) {
                $row['uname'] = $xoopsConfig['anonymous'];

                $row['user_avatar'] = $guest_avatar;
            }

            $row['message'] = $myts->displayTarea($row['message']);

            $row['title'] = $myts->displayTarea($row['title']);

            $row['name'] = $myts->displayTarea($row['name']);

            $parent_id_ary[] = $row['post_id'];

            $list[$row['post_id']] = $row;
        }

        // Get Child Messages

        if (count($parent_id_ary) > 0) {
            if (isset($post_id) && 0 != $post_id) {
                $pid_list = $post_id;
            } else {
                $pid_list = implode(',', $parent_id_ary);
            }

            $sql = sprintf(
                'SELECT b.*,u.uid AS users_uid,u.user_avatar,u.uname FROM %s AS b LEFT JOIN %s AS u ON ( b.uid = u.uid ) WHERE b.bbs_id = %d AND b.status = 1 AND b.parent_id IN(%s) ORDER BY b.parent_id,b.post_id ',
                $xoopsDB->prefix('mybbs_posts'),
                $xoopsDB->prefix('users'),
                $bbs_id,
                $pid_list
            );

            $res = $xoopsDB->query($sql);

            while (false !== ($row = $xoopsDB->fetchArray($res))) {
                //$hash["body"] = body_convert($hash["body"]);

                $row['post_time'] = date(DATE_FORMAT, $row['post_time']);

                if (0 == $row['uid']) {
                    $row['uname'] = $row['name'];

                    $row['user_avatar'] = $guest_avatar;
                } elseif (!$row['users_uid']) {
                    $row['uname'] = $xoopsConfig['anonymous'];

                    $row['user_avatar'] = $guest_avatar;
                }

                $row['message'] = $myts->displayTarea($row['message']);

                $row['title'] = $myts->displayTarea($row['title']);

                $row['name'] = $myts->displayTarea($row['name']);

                @$list[$row[parent_id]]['res'][] = $row;
            }
        }

        return [$num, $list];
    }

    // function getMessages

    public function getOneMessage($bbs_id, $post_id)
    {
        global $xoopsDB;

        $sql = sprintf(
            'SELECT * FROM %s WHERE bbs_id = %d AND post_id = %d ',
            $xoopsDB->prefix('mybbs_posts'),
            $bbs_id,
            $post_id
        );

        $res = $xoopsDB->query($sql);

        $row = $xoopsDB->fetchArray($res);

        return $row;
    }

    public function isPostAllowed($bbs_id, $bbs_info = null)
    {
        global $xoopsUser;

        if (!$bbs_info) {
            $bbs_info = self::getBbsInfo($bbs_id);
        }

        if (1 == $bbs_info['guest_post']) {
            return true;
        }  

        if (is_object($xoopsUser) && 0 != $xoopsUser->uid()) {
            return true;
        }

        return false;
    }

    /**
     * @param mixed $bbs_id
     * @param mixed $_form
     * @param mixed $parent_id
     * @param mixed $root_id
     */

    public function postMessage($bbs_id, $_form, $parent_id = 0, $root_id = 0)
    {
        global $xoopsUser;

        global $xoopsDB;

        if (is_object($xoopsUser)) {
            $uid = $xoopsUser->uid();

            $xoopsUser->incrementPost();

            $name = 'NULL';
        } else {
            $bbs_info = self::getBbsInfo($bbs_id);

            if (1 != $bbs_info['guest_post']) {
                die('POST DENIED');
            }

            $uid = 0;

            $name = $xoopsDB->quoteString($_form['name']);
        }

        $sql = sprintf(
            'INSERT INTO %s (bbs_id,uid,name,title,message,parent_id,root_id,post_time,post_ip) VALUES(%d, %d, %s, %s, %s, %d, %d, %s, %s)',
            $xoopsDB->prefix('mybbs_posts'),
            $bbs_id,
            $uid,
            $name,
            $xoopsDB->quoteString($_form['title']),
            $xoopsDB->quoteString($_form['message']),
            $parent_id,
            $root_id,
            time(),
            $xoopsDB->quoteString($_SERVER['REMOTE_ADDR'])
        );

        $res = $xoopsDB->query($sql);

        return $res;
    }

    // check edit/delete permition

    public function isEditAllowed($post_id)
    {
        global $xoopsUser;

        global $xoopsModule;

        global $xoopsDB;

        if (!$xoopsUser->isAdmin($xoopsModule->mid())) {
            $sql = sprintf(
                'SELECT uid FROM %s WHERE post_id = %d',
                $xoopsDB->prefix('mybbs_posts'),
                $post_id
            );

            $res = $xoopsDB->query($sql);

            $row = $xoopsDB->fetchArray($res);

            if ($row['uid'] != $xoopsUser->uid()) {
                return false;
                //die("You don't have a permition to delete this message");
            }
        }

        return true;
    }

    public function deleteMessage($bbs_id, $post_id)
    {
        global $xoopsUser;

        global $xoopsDB;

        global $xoopsModule;

        if (!self::isEditAllowed($post_id)) {
            die("You don't have a permition to delete this message");
        }

        $sql = sprintf(
            'UPDATE %s SET status = 0 WHERE bbs_id = %d AND post_id = %d',
            $xoopsDB->prefix('mybbs_posts'),
            $bbs_id,
            $post_id
        );

        //echo $sql;

        $res = $xoopsDB->queryF($sql);

        //var_dump($res);

        return $res;
    }

    public function editMessage($bbs_id, $post_id, $title, $message)
    {
        global $xoopsUser;

        global $xoopsDB;

        if (!self::isEditAllowed($post_id)) {
            die("You don't have a permition to edit this message");
        }

        $sql = sprintf(
            'UPDATE %s SET title = %s , message = %s  WHERE bbs_id = %d AND post_id = %d',
            $xoopsDB->prefix('mybbs_posts'),
            $xoopsDB->quoteString($title),
            $xoopsDB->quoteString($message),
            $bbs_id,
            $post_id
        );

        //echo $sql;

        $res = $xoopsDB->query($sql);

        //var_dump($res);

        return $res;
    }

    public function deleteMaster($bbs_id)
    {
        global $xoopsDB;

        $sql = sprintf(
            'UPDATE %s SET status = 0 WHERE bbs_id = %d LIMIT 1',
            $xoopsDB->prefix('mybbs_master'),
            $bbs_id
        );

        $res = $xoopsDB->query($sql);

        return $res;
    }

    public function addMaster($hash)
    {
        global $xoopsDB;

        extract($hash);

        $sql = sprintf(
            'INSERT INTO %s (bbs_name,bbs_note,status,sort_order,bbs_contents) VALUES(%s,%s,1,%d,%s)',
            $xoopsDB->prefix('mybbs_master'),
            $xoopsDB->quoteString($bbs_name),
            $xoopsDB->quoteString($bbs_note),
            $sort_order,
            $xoopsDB->quoteString($bbs_contents)
        );

        $res = $xoopsDB->query($sql);

        return $res;
    }

    public function editMaster($bbs_id, $hash)
    {
        global $xoopsDB;

        extract($hash);

        $sql = sprintf(
            'UPDATE %s SET bbs_name = %s, bbs_contents = %s, bbs_note = %s, sort_order = %s, guest_post = %d, page_limit = %d ,tarea_dhtml = %d, tarea_smily = %d, tarea_font = %d WHERE bbs_id = %d ',
            $xoopsDB->prefix('mybbs_master'),
            $xoopsDB->quoteString($bbs_name),
            $xoopsDB->quoteString($bbs_contents),
            $xoopsDB->quoteString($bbs_note),
            $sort_order,
            $guest_post,
            $page_limit,
            $tarea_dhtml,
            $tarea_smily,
            $tarea_font,
            $bbs_id
        );

        //echo $sql;

        $res = $xoopsDB->query($sql);

        return $res;
    }

    public function getTarea($message, $tarea_dhtml = 0, $tarea_font = 0, $tarea_smily = 0)
    {
        global $xoopsModule;

        require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

        require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/formdhtmltextareale.php';

        if (!$tarea_dhtml) {
            define('_DHTML_NO_DHTML_CODE', true);
        }

        if (!$tarea_font) {
            define('_DHTML_NO_DHTML_CODE_FONT', true);
        }

        if (!$tarea_smily) {
            define('_DHTML_NO_DHTML_CODE_SMILY', true);
        }

        $tarea = new XoopsFormDhtmlTextAreaLe('', 'message', $value = $message, $rows = 5, $cols = 40, $hiddentext = 'xoopsHiddenText');

        $form_message_tarea = $tarea->render();

        return $form_message_tarea;
    }
}// class MyBBS
?>
