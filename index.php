<?php
require_once "config.php";
require_once "views/LayoutView.php";
require_once "views/HomeView.php";
require_once "views/ClassView.php";
require_once "views/StudentView.php";
require_once "views/SubjectView.php";
require_once "views/MarkView.php";
require_once "views/MaintenanceView.php";
require_once "views/ListsView.php";
require_once "models/SubjectModel.php";
require_once "models/StudentModel.php";
require_once "models/ClassModel.php";
require_once "models/MarkModel.php";
require_once "models/Install.php";
require_once "controllers/SubjectController.php";
require_once "controllers/ClassController.php";
require_once "controllers/StudentController.php";
require_once "controllers/MarkController.php";
require_once "controllers/MaintenanceController.php";
require_once "controllers/ListsController.php";
require_once "controllers/Router.php";

$pdo = new PDO(
    "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
    DB_USERNAME,
    DB_PASSWORD,
    [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]
);

$view = $_GET['view'] ?? 'home';
$router = new Router($pdo);

LayoutView::head();
LayoutView::menu();

try {
    $router->handle($view);
} catch (Exception $e) {
    echo '<div style="background:#fee;border:1px solid red;padding:10px;margin:10px">';
    echo '<strong>Hiba:</strong> ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    echo '</div>';
}

LayoutView::footer();
