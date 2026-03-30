<?php
require_once "models/Install.php";
require_once "models/ClassModel.php";
require_once "models/StudentModel.php";
require_once "models/SubjectModel.php";
require_once "models/MarkModel.php";
require_once "views/MaintenanceView.php";
require_once "views/SubjectView.php";
require_once "views/ClassView.php";
require_once "views/StudentView.php";
require_once "views/MarkView.php";

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
            (new SubjectModel($this->pdo))->delete($_GET['delete-subject']);
            header("Location: index.php?view=maintenance-subjects");
            exit;
        }
        if (isset($_GET['delete-class'])) {
            (new ClassModel($this->pdo))->delete($_GET['delete-class']);
            header("Location: index.php?view=maintenance-classes");
            exit;
        }
        if (isset($_GET['delete-student'])) {
            (new StudentModel($this->pdo))->delete($_GET['delete-student']);
            header("Location: index.php?view=maintenance-students");
            exit;
        }
        if (isset($_GET['delete-mark'])) {
            (new MarkModel($this->pdo))->delete($_GET['delete-mark']);
            header("Location: index.php?view=maintenance-marks");
            exit;
        }

        if (isset($_POST['add-subject'])) {
            (new SubjectModel($this->pdo))->create($_POST['name']);
            header("Location: index.php?view=maintenance-subjects");
            exit;
        }
        if (isset($_POST['update-subject'])) {
            (new SubjectModel($this->pdo))->update($_POST['id'], $_POST['name']);
            header("Location: index.php?view=maintenance-subjects");
            exit;
        }

        if (isset($_POST['add-class'])) {
            (new ClassModel($this->pdo))->create($_POST['grade'], $_POST['letter'], $_POST['year']);
            header("Location: index.php?view=maintenance-classes");
            exit;
        }
        if (isset($_POST['update-class'])) {
            (new ClassModel($this->pdo))->update($_POST['id'], $_POST['grade'], $_POST['letter'], $_POST['year']);
            header("Location: index.php?view=maintenance-classes");
            exit;
        }

        if (isset($_POST['add-student'])) {
            (new StudentModel($this->pdo))->create($_POST['class_id'], $_POST['name'], $_POST['birth_date']);
            header("Location: index.php?view=maintenance-students");
            exit;
        }
        if (isset($_POST['update-student'])) {
            (new StudentModel($this->pdo))->update($_POST['id'], $_POST['class_id'], $_POST['name'], $_POST['birth_date']);
            header("Location: index.php?view=maintenance-students");
            exit;
        }

        if (isset($_POST['add-mark'])) {
            (new MarkModel($this->pdo))->create($_POST['subject_id'], $_POST['mark'], $_POST['student_id'], $_POST['date']);
            header("Location: index.php?view=maintenance-marks");
            exit;
        }
        if (isset($_POST['update-mark'])) {
            (new MarkModel($this->pdo))->update($_POST['id'], $_POST['subject_id'], $_POST['mark'], $_POST['student_id'], $_POST['date']);
            header("Location: index.php?view=maintenance-marks");
            exit;
        }

        $route = $view;

        switch ($view) {
            case 'maintenance':
                MaintenanceView::dashboard();
                break;

            case 'maintenance-subjects':
                $model = new SubjectModel($this->pdo);
                if (isset($_GET['edit'])) {
                    SubjectView::editForm($model->find($_GET['edit']), $route);
                } elseif (isset($_GET['add'])) {
                    SubjectView::addForm($route);
                } else {
                    echo '<p><a href="index.php?view=maintenance-subjects&add=1">+ Új tantárgy</a>'
                       . ' | <a href="index.php?view=maintenance">← Karbantartás</a></p>';
                    SubjectView::list($model->getAll(), $route);
                }
                break;

            case 'maintenance-classes':
                $model = new ClassModel($this->pdo);
                if (isset($_GET['edit'])) {
                    ClassView::editForm($model->find($_GET['edit']), $route);
                } elseif (isset($_GET['add'])) {
                    ClassView::addForm($route);
                } else {
                    echo '<p><a href="index.php?view=maintenance-classes&add=1">+ Új osztály</a>'
                       . ' | <a href="index.php?view=maintenance">← Karbantartás</a></p>';
                    ClassView::list($model->getAll(), $route);
                }
                break;

            case 'maintenance-students':
                $studentModel = new StudentModel($this->pdo);
                $classes      = (new ClassModel($this->pdo))->getAll();
                if (isset($_GET['edit'])) {
                    StudentView::editForm($studentModel->find($_GET['edit']), $classes, $route);
                } elseif (isset($_GET['add'])) {
                    StudentView::addForm($classes, $route);
                } else {
                    echo '<p><a href="index.php?view=maintenance-students&add=1">+ Új tanuló</a>'
                       . ' | <a href="index.php?view=maintenance">← Karbantartás</a></p>';
                    StudentView::list($studentModel->getAll(), $route);
                }
                break;

            case 'maintenance-marks':
                $markModel    = new MarkModel($this->pdo);
                $studentModel = new StudentModel($this->pdo);
                $subjectModel = new SubjectModel($this->pdo);
                $students     = $studentModel->getAll();
                $subjects     = $subjectModel->getAll();
                if (isset($_GET['edit'])) {
                    MarkView::maintenanceEditForm($markModel->find($_GET['edit']), $students, $subjects, $route);
                } elseif (isset($_GET['add'])) {
                    MarkView::maintenanceAddForm($students, $subjects, $route);
                } else {
                    echo '<p><a href="index.php?view=maintenance-marks&add=1">+ Új osztályzat</a>'
                       . ' | <a href="index.php?view=maintenance">← Karbantartás</a></p>';
                    MarkView::maintenanceList($markModel->getAll(), $students, $subjects, $route);
                }
                break;
        }
    }
}
