<?php
require_once "models/ClassModel.php";
require_once "views/ClassView.php";

class ClassController
{
    private ClassModel $model;

    public function __construct(PDO $pdo)
    {
        $this->model = new ClassModel($pdo);
    }

    public function handleRequest(string $view)
    {
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (isset($_POST['add-class'])) {
                $this->model->create($_POST['grade'], $_POST['letter'], $_POST['year']);
                header("Location: index.php?view=classes");
                exit;
            }

            if (isset($_POST['update-class'])) {
                $this->model->update($_POST['id'], $_POST['grade'], $_POST['letter'], $_POST['year']);
                header("Location: index.php?view=classes");
                exit;
            }
        }

        
        if (isset($_GET['delete'])) {
            $this->model->delete($_GET['delete']);
            header("Location: index.php?view=classes");
            exit;
        }

        
        switch ($view) {

            case 'classes':
                $classes = $this->model->getAll();
                ClassView::list($classes);
                break;

            case 'add-class':
                ClassView::addForm();
                break;

            case 'edit-class':
                $class = $this->model->find($_GET['id']);
                ClassView::editForm($class);
                break;
        }
    }
}
