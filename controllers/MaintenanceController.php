<?php
require_once "models/Install.php";
require_once "models/ClassModel.php";
require_once "models/StudentModel.php";
require_once "models/SubjectModel.php";
require_once "models/MarkModel.php";
require_once "views/MaintenanceView.php";

class MaintenanceController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function handleRequest(string $view): void
    {
        
        if (isset($_POST['generate-data'])) {
            $install = new Install($this->pdo);
            $log = $install->generate();
            MaintenanceView::generateResult($log);
            return;
        }

        
        if (isset($_GET['delete-subject'])) {
            $model = new SubjectModel($this->pdo);
            $model->delete($_GET['delete-subject']);
            header("Location: index.php?view=maintenance-subjects");
            exit;
        }
        if (isset($_GET['delete-class'])) {
            $model = new ClassModel($this->pdo);
            $model->delete($_GET['delete-class']);
            header("Location: index.php?view=maintenance-classes");
            exit;
        }
        if (isset($_GET['delete-student'])) {
            $model = new StudentModel($this->pdo);
            $model->delete($_GET['delete-student']);
            header("Location: index.php?view=maintenance-students");
            exit;
        }
        if (isset($_GET['delete-mark'])) {
            $model = new MarkModel($this->pdo);
            $model->delete($_GET['delete-mark']);
            header("Location: index.php?view=maintenance-marks");
            exit;
        }

        
        if (isset($_POST['add-subject'])) {
            $model = new SubjectModel($this->pdo);
            $model->create($_POST['name']);
            header("Location: index.php?view=maintenance-subjects");
            exit;
        }
        if (isset($_POST['update-subject'])) {
            $model = new SubjectModel($this->pdo);
            $model->update($_POST['id'], $_POST['name']);
            header("Location: index.php?view=maintenance-subjects");
            exit;
        }

        
        if (isset($_POST['add-class'])) {
            $model = new ClassModel($this->pdo);
            $model->create($_POST['grade'], $_POST['letter'], $_POST['year']);
            header("Location: index.php?view=maintenance-classes");
            exit;
        }
        if (isset($_POST['update-class'])) {
            $model = new ClassModel($this->pdo);
            $model->update($_POST['id'], $_POST['grade'], $_POST['letter'], $_POST['year']);
            header("Location: index.php?view=maintenance-classes");
            exit;
        }

        
        if (isset($_POST['add-student'])) {
            $model = new StudentModel($this->pdo);
            $model->create($_POST['class_id'], $_POST['name'], $_POST['birth_date']);
            header("Location: index.php?view=maintenance-students");
            exit;
        }
        if (isset($_POST['update-student'])) {
            $model = new StudentModel($this->pdo);
            $model->update($_POST['id'], $_POST['class_id'], $_POST['name'], $_POST['birth_date']);
            header("Location: index.php?view=maintenance-students");
            exit;
        }

        
        if (isset($_POST['add-mark'])) {
            $model = new MarkModel($this->pdo);
            $model->create($_POST['subject_id'], $_POST['mark'], $_POST['student_id'], $_POST['date']);
            header("Location: index.php?view=maintenance-marks");
            exit;
        }
        if (isset($_POST['update-mark'])) {
            $model = new MarkModel($this->pdo);
            $model->update($_POST['id'], $_POST['subject_id'], $_POST['mark'], $_POST['student_id'], $_POST['date']);
            header("Location: index.php?view=maintenance-marks");
            exit;
        }

        switch ($view) {
            case 'maintenance':
                MaintenanceView::dashboard();
                break;

            case 'maintenance-subjects':
                $model = new SubjectModel($this->pdo);
                $items = $model->getAll();
                if (isset($_GET['edit'])) {
                    $item = $model->find($_GET['edit']);
                    MaintenanceView::subjectEdit($item);
                } elseif (isset($_GET['add'])) {
                    MaintenanceView::subjectAdd();
                } else {
                    MaintenanceView::subjectList($items);
                }
                break;

            case 'maintenance-classes':
                $model = new ClassModel($this->pdo);
                $items = $model->getAll();
                if (isset($_GET['edit'])) {
                    $item = $model->find($_GET['edit']);
                    MaintenanceView::classEdit($item);
                } elseif (isset($_GET['add'])) {
                    MaintenanceView::classAdd();
                } else {
                    MaintenanceView::classList($items);
                }
                break;

            case 'maintenance-students':
                $studentModel = new StudentModel($this->pdo);
                $classModel   = new ClassModel($this->pdo);
                $items = $studentModel->getAll();
                $classes = $classModel->getAll();
                if (isset($_GET['edit'])) {
                    $item = $studentModel->find($_GET['edit']);
                    MaintenanceView::studentEdit($item, $classes);
                } elseif (isset($_GET['add'])) {
                    MaintenanceView::studentAdd($classes);
                } else {
                    MaintenanceView::studentList($items, $classes);
                }
                break;

            case 'maintenance-marks':
                $markModel    = new MarkModel($this->pdo);
                $studentModel = new StudentModel($this->pdo);
                $subjectModel = new SubjectModel($this->pdo);
                $items    = $markModel->getAll();
                $students = $studentModel->getAll();
                $subjects = $subjectModel->getAll();
                if (isset($_GET['edit'])) {
                    $item = $markModel->find($_GET['edit']);
                    MaintenanceView::markEdit($item, $students, $subjects);
                } elseif (isset($_GET['add'])) {
                    MaintenanceView::markAdd($students, $subjects);
                } else {
                    MaintenanceView::markList($items, $students, $subjects);
                }
                break;
        }
    }
}
