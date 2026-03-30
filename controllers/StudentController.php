<?php
require_once "models/StudentModel.php";
require_once "models/ClassModel.php";
require_once "views/StudentView.php";

class StudentController
{
    private StudentModel $model;
    private ClassModel $classModel;

    public function __construct(PDO $pdo)
    {
        $this->model      = new StudentModel($pdo);
        $this->classModel = new ClassModel($pdo);
    }

    public function handleRequest(string $view)
    {
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (isset($_POST['add-student'])) {
                $this->model->create($_POST['class_id'], $_POST['name'], $_POST['birth_date']);
                header("Location: index.php?view=students");
                exit;
            }

            if (isset($_POST['update-student'])) {
                $this->model->update($_POST['id'], $_POST['class_id'], $_POST['name'], $_POST['birth_date']);
                header("Location: index.php?view=students");
                exit;
            }
        }

        
        if (isset($_GET['delete'])) {
            $this->model->delete($_GET['delete']);
            header("Location: index.php?view=students");
            exit;
        }

        $classes = $this->classModel->getAll();

        
        switch ($view) {

            case 'students':
                $students = $this->model->getAll();
                StudentView::list($students);
                break;

            case 'add-student':
                StudentView::addForm($classes);
                break;

            case 'edit-student':
                $student = $this->model->find($_GET['id']);
                StudentView::editForm($student, $classes);
                break;
        }
    }
}
