<?php
include_once "./Controller/commentController.php";

$controller = new CommentController();
$action = $_GET['action'] ?? 'list';
$maTour = $_GET['maTour'] ?? 1;

switch ($action) {
    case 'list':
        $controller->list($maTour);
        break;
    case 'add':
        $controller->add();
        break;
    case 'delete':
        $maCom = $_GET['maCom'];
        $controller->delete($maCom, $maTour);
        break;
    case 'manage': // admin quản lý bình luận
        $controller->manage();
        break;
}
?>
