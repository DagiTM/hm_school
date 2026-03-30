<?php
require_once "models/SubjectModel.php";
require_once "views/SubjectView.php";

class SubjectController
{
    private SubjectModel $model;

    public function __construct(PDO $pdo)
    {
        $this->model = new SubjectModel($pdo);
    }

    public function handleRequest(string $view)
    {
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (isset($_POST['add-subject'])) {
                $this->model->create($_POST['name']);
                header("Location: index.php?view=subjects");
                exit;
            }

            if (isset($_POST['update-subject'])) {
                $this->model->update($_POST['id'], $_POST['name']);
                header("Location: index.php?view=subjects");
                exit;
            }
        }

        
        if (isset($_GET['delete'])) {
            $this->model->delete($_GET['delete']);
            header("Location: index.php?view=subjects");
            exit;
        }

        
        switch ($view) {

            case 'subjects':
                $subjects = $this->model->getAll();
                SubjectView::list($subjects);
                break;

            case 'add-subject':
                SubjectView::addForm();
                break;

            case 'edit-subject':
                $subject = $this->model->find($_GET['id']);
                SubjectView::editForm($subject);
                break;
        }
    }
}
