<?php

define("IN_SITE", true);
require_once(__DIR__."/../../config.php");
require_once(__DIR__."/../../libs/db.php");
require_once(__DIR__."/../../libs/helper.php");
require_once(__DIR__.'/../../models/is_admin.php');

if (isset($_POST['id'])) {
    if ($CMSNT->site('status_demo') != 0) {
        $data = json_encode([
            'status'    => 'error',
            'msg'       => 'Không được dùng chức năng này vì đây là trang web demo'
        ]);
        die($data);
    }
    $id = check_string($_POST['id']);
    if (!$row = $CMSNT->get_row("SELECT * FROM `dichvu_groups` WHERE `id` = '$id' ")) {
        $data = json_encode([
            'status'    => 'error',
            'msg'       => 'ID nhóm dịch vụ không tồn tại trong hệ thống'
        ]);
        die($data);
    }
    $dichvu = $CMSNT->get_row("SELECT * FROM `dichvugame` WHERE `id` = '".$row['dichvugame_id']."' ");
    $isRemove = $CMSNT->remove("dichvu_groups", " `id` = '$id' ");
    if ($isRemove) {
        $Mobile_Detect = new Mobile_Detect();
        $CMSNT->insert("logs", [
            'user_id'       => $getUser['id'],
            'ip'            => myip(),
            'device'        => $Mobile_Detect->getUserAgent(),
            'createdate'    => gettime(),
            'action'        => 'Xoá nhóm dịch vụ '.$dichvu['name'].' ('.$row['name'].' ID '.$row['id'].')'
        ]);
        $data = json_encode([
            'status'    => 'success',
            'msg'       => 'Xoá nhóm dịch vụ thành công'
        ]);
        die($data);
    }
} else {
    $data = json_encode([
        'status'    => 'error',
        'msg'       => 'Dữ liệu không hợp lệ'
    ]);
    die($data);
}
