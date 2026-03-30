<?php
require_once "controllers/SubjectController.php";
require_once "controllers/ClassController.php";
require_once "controllers/StudentController.php";
require_once "controllers/MarkController.php";
require_once "controllers/MaintenanceController.php";
require_once "controllers/ListsController.php";
require_once "views/HomeView.php";

class Router
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function handle(string $view): void
    {
        switch ($view) {

            case 'subjects':
            case 'add-subject':
            case 'edit-subject':
                $controller = new SubjectController($this->pdo);
                $controller->handleRequest($view);
                break;

            case 'classes':
            case 'add-class':
            case 'edit-class':
                $controller = new ClassController($this->pdo);
                $controller->handleRequest($view);
                break;
                
            case 'students':
            case 'add-student':
            case 'edit-student':
                $controller = new StudentController($this->pdo);
                $controller->handleRequest($view);
                break;
            
            case 'marks':
            case 'add-mark':
            case 'edit-mark':
                $controller = new MarkController($this->pdo);
                $controller->handleRequest($view);
                break;

            case 'maintenance':
            case 'maintenance-subjects':
            case 'maintenance-classes':
            case 'maintenance-students':
            case 'maintenance-marks':
                $controller = new MaintenanceController($this->pdo);
                $controller->handleRequest($view);
                break;

            case 'lists':
                $controller = new ListsController($this->pdo);
                $controller->handleRequest($view);
                break;

            default:
                HomeView::render();
        }
    }
}
