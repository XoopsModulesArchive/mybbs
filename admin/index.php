<?php
// $Id: index.php,v 1.1 2006/03/27 15:37:44 mikhail Exp $

include '../../../mainfile.php';
require XOOPS_ROOT_PATH . '/include/cp_header.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/class.mybbs.php';
require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/include/mybbs.inc.php';

xoops_cp_header();

$configHandler = xoops_getHandler('config');
$xoopsConfigUser = $configHandler->getConfigsByCat(XOOPS_CONF_USER);

$myts = MyTextSanitizer::getInstance();

$_form = &get_form();
$op = &$_form['op'];
$ok = &$_form['ok'];
$bbs_id = &$_form['bbs_id'];

if ('delete' == $op) { // del
    if (1 == $ok) {
        if (MyBBS::deleteMaster($bbs_id)) {
            redirect_header($_SERVER['SCRIPT_NAME'], 1, _MD_A_MYBBS_DBTRUE);
        } else {
            redirect_header($_SERVER['SCRIPT_NAME'], 1, _MD_A_MYBBS_DBFALSE);
        }
    } else {
        xoops_confirm(
            ['op' => 'delete', 'bbs_id' => $bbs_id, 'ok' => 1],
            $_SERVER['SCRIPT_NAME'],
            _MD_A_MYBBS_BBSDELETE
        );

        xoops_cp_footer();

        exit;
    }
}

if ('add_bbs' == $op) {
    if (!$_form['bbs_name']) {
        redirect_header($_SERVER['SCRIPT_NAME'], 2, _MD_A_MYBBS_NO_BBS_NAME);
    }

    $hash['bbs_name'] = $_form['bbs_name'];

    $hash['bbs_note'] = $_form['bbs_note'];

    $hash['bbs_contents'] = $_form['bbs_contents'];

    $hash['sort_order'] = intval($_form['sort_order']);

    $hash['guest_post'] = intval($_form['guest_post']);

    $hash['page_limit'] = intval($_form['page_limit']);

    $hash['tarea_dhtml'] = intval($_form['tarea_dhtml']);

    $hash['tarea_smily'] = intval($_form['tarea_smily']);

    $hash['tarea_font'] = intval($_form['tarea_font']);

    if ($hash['page_limit'] <= 0) {
        $hash['page_limit'] = _MD_A_MYBBS_PAGE_LIMIT_DEFAULT;
    }

    if (MyBBS::addMaster($hash)) {
        redirect_header($_SERVER['SCRIPT_NAME'], 2, _MD_A_MYBBS_DBTRUE);
    } else {
        redirect_header($_SERVER['SCRIPT_NAME'], 2, _MD_A_MYBBS_DBFALSE);
    }

    xoops_cp_footer();

    exit;
}
if ('edit_bbs' == $op) {
    if (!$_form['bbs_name']) {
        redirect_header($_SERVER['SCRIPT_NAME'], 2, _MD_A_MYBBS_NO_BBS_NAME);
    }

    $hash['bbs_name'] = $_form['bbs_name'];

    $hash['bbs_note'] = $_form['bbs_note'];

    $hash['bbs_contents'] = $_form['bbs_contents'];

    $hash['sort_order'] = intval($_form['sort_order']);

    $hash['guest_post'] = intval($_form['guest_post']);

    $hash['page_limit'] = intval($_form['page_limit']);

    $hash['tarea_dhtml'] = intval($_form['tarea_dhtml']);

    $hash['tarea_smily'] = intval($_form['tarea_smily']);

    $hash['tarea_font'] = intval($_form['tarea_font']);

    if ($hash['page_limit'] <= 0) {
        $hash['page_limit'] = _MD_A_MYBBS_PAGE_LIMIT_DEFAULT;
    }

    if (MyBBS::editMaster($bbs_id, $hash)) {
        redirect_header($_SERVER['SCRIPT_NAME'], 3, _MD_A_MYBBS_DBTRUE);
    } else {
        redirect_header($_SERVER['SCRIPT_NAME'], 3, _MD_A_MYBBS_DBFALSE);
    }

    xoops_cp_footer();

    exit;
}

// list
$bbs_list = MyBBS::getBbsList();
$yesno = [0 => _NO, 1 => _YES];
?>
<h4><?= _MD_A_MYBBS_BBS_LIST ?></h2>
    <table border='0' cellpadding='0' cellspacing='0'>
        <tr>
            <td class='bg2'>
                <table border='0' cellpadding='4' cellspacing='1'>
                    <tr class='bg3' align='center'>
                        <td><?= _MD_A_MYBBS_BBS_ID ?></td>
                        <td><?= _MD_A_MYBBS_SORT_ORDER ?></td>
                        <td><?= _MD_A_MYBBS_NAME ?></td>
                        <td><?= _MD_A_MYBBS_CONTENTS ?></td>
                        <td><?= _MD_A_MYBBS_NOTE ?></td>
                        <td><?= _MD_A_MYBBS_PAGE_LIMIT ?></td>
                        <td><?= _MD_A_MYBBS_GUEST_POST ?></td>
                        <td><?= _MD_A_MYBBS_TAREA_TYPE ?></td>
                        <td><?= _MD_A_MYBBS_ACTION ?></td>
                    </tr>
                    <?php foreach ($bbs_list as $row) { ?>
                        <tr class='bg1'>
                            <td align='left'><?= $row['bbs_id'] ?></td>
                            <td align='left'><?= $row['sort_order'] ?></td>
                            <td align='left'><?= htmlspecialchars($row['bbs_name']) ?></td>
                            <td align='left'><?= $myts->displayTarea($row['bbs_contents']) ?></td>
                            <td align='left'><?= $myts->displayTarea($row['bbs_note']) ?></td>
                            <td align='left'><?= $row['page_limit'] ?></td>
                            <td align='left'><?= $yesno[$row['guest_post']] ?></td>
                            <td align='left'>
                                <?= _MD_A_MYBBS_TAREA_DHTML ?> : <?= $yesno[$row['tarea_dhtml']] ?> <br>
                                <?= _MD_A_MYBBS_TAREA_FONT ?> : <?= $yesno[$row['tarea_font']] ?> <br>
                                <?= _MD_A_MYBBS_TAREA_SMILY ?> : <?= $yesno[$row['tarea_smily']] ?> <br>
                            </td>


                            <td><a href='<?= $_SERVER['SCRIPT_NAME'] ?>?op=edit&bbs_id=<?= $row['bbs_id'] ?>'><?= _MD_A_MYBBS_EDIT ?></a> | <a href='<?= $_SERVER['SCRIPT_NAME'] ?>?op=delete&bbs_id=<?= $row['bbs_id'] ?>'><?= _MD_A_MYBBS_DELETE ?></a></td>
                        </tr>
                    <?php } ?>
                </table>
            </td>
        </tr>
    </table>
    <?php

    if ('edit' == $op) {
        $bbs_info = MyBBS::getBbsInfo($bbs_id);
    } else {
        $bbs_info = [];

        $bbs_id = 0;
    }

    $form = new XoopsThemeForm(_MD_A_MYBBS_BBSCONF, 'bbs', $_SERVER['SCRIPT_NAME']);
    $form->addElement(new XoopsFormHidden('bbs_id', $bbs_id));
    $form->addElement(new XoopsFormText(_MD_A_MYBBS_NAME, 'bbs_name', 60, 60, htmlspecialchars($bbs_info['bbs_name'])));
    $form->addElement(new XoopsFormDhtmlTextArea (_MD_A_MYBBS_CONTENTS, 'bbs_contents', htmlspecialchars($bbs_info['bbs_contents'])));
    $form->addElement(new XoopsFormDhtmlTextArea (_MD_A_MYBBS_NOTE, 'bbs_note', htmlspecialchars($bbs_info['bbs_note'])));
    $form->addElement(new XoopsFormText(_MD_A_MYBBS_SORT_ORDER, 'sort_order', 3, 3, $bbs_info['sort_order']));
    $form->addElement(new XoopsFormRadioYN(_MD_A_MYBBS_GUEST_POST, 'guest_post', $bbs_info['guest_post']));
    $form->addElement(new XoopsFormText(_MD_A_MYBBS_PAGE_LIMIT, 'page_limit', 2, 2, $bbs_info['page_limit']));

    // TextArea Tray
    $form_type_tray = new XoopsFormElementTray(_MD_A_MYBBS_TAREA_TYPE, '&nbsp;');
    $cb1 = new XoopsFormCheckBox('', 'tarea_dhtml', $bbs_info['tarea_dhtml']);
    $cb1->addOption(1, _MD_A_MYBBS_TAREA_DHTML);
    $cb2 = new XoopsFormCheckBox('', 'tarea_font', $bbs_info['tarea_font']);
    $cb2->addOption(1, _MD_A_MYBBS_TAREA_FONT);
    $cb3 = new XoopsFormCheckBox('', 'tarea_smily', $bbs_info['tarea_smily']);
    $cb3->addOption(1, _MD_A_MYBBS_TAREA_SMILY);
    $form_type_tray->addElement($cb1);
    $form_type_tray->addElement($cb2);
    $form_type_tray->addElement($cb3);
    $form->addElement($form_type_tray);

    if ('edit' == $op) {
        $form->addElement(new XoopsFormHidden('op', 'edit_bbs'));

        $submit_button = new XoopsFormButton('', 'submit', _MD_A_MYBBS_EDIT, 'submit');
    } else {
        $form->addElement(new XoopsFormHidden('op', 'add_bbs'));

        $submit_button = new XoopsFormButton('', 'submit', _MD_A_MYBBS_REGIST, 'submit');
    }
    $form->addElement($submit_button);
    $form->display();

    xoops_cp_footer();
    ?>
