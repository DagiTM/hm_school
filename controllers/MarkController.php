<?php
require_once "models/MarkModel.php";
require_once "models/ClassModel.php";
require_once "models/StudentModel.php";
require_once "models/SubjectModel.php";
require_once "views/MarkView.php";

class MarkController
{
    private MarkModel   $model;
    private ClassModel  $classModel;
    private StudentModel $studentModel;
    private SubjectModel $subjectModel;
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo          = $pdo;
        $this->model        = new MarkModel($pdo);
        $this->classModel   = new ClassModel($pdo);
        $this->studentModel = new StudentModel($pdo);
        $this->subjectModel = new SubjectModel($pdo);
    }

    public function handleRequest(string $view)
    {
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (isset($_POST['add-mark'])) {
                $this->model->create(
                    $_POST['subject_id'], $_POST['mark'],
                    $_POST['student_id'], $_POST['date']
                );
                
                $sid = $_POST['student_id'];
                $cid = $_POST['class_id'] ?? '';
                $yr  = $_POST['year']     ?? '';
                header("Location: index.php?view=marks&year={$yr}&class_id={$cid}&student_id={$sid}");
                exit;
            }

            if (isset($_POST['update-mark'])) {
                $this->model->update(
                    $_POST['id'], $_POST['subject_id'], $_POST['mark'],
                    $_POST['student_id'], $_POST['date']
                );
                $sid = $_POST['student_id'];
                $cid = $_POST['class_id'] ?? '';
                $yr  = $_POST['year']     ?? '';
                header("Location: index.php?view=marks&year={$yr}&class_id={$cid}&student_id={$sid}");
                exit;
            }
        }

        
        if (isset($_GET['delete'])) {
            $this->model->delete($_GET['delete']);
            $sid = $_GET['student_id'] ?? '';
            $cid = $_GET['class_id']   ?? '';
            $yr  = $_GET['year']       ?? '';
            header("Location: index.php?view=marks&year={$yr}&class_id={$cid}&student_id={$sid}");
            exit;
        }

        $years      = $this->classModel->getYears();
        $subjects   = $this->subjectModel->getAll();

        $selectedYear      = $_GET['year']       ?? null;
        $selectedClassId   = $_GET['class_id']   ?? null;
        $selectedStudentId = $_GET['student_id'] ?? null;

        $classes  = $selectedYear    ? $this->classModel->getClassesByYearAll($selectedYear) : [];
        $students = $selectedClassId ? $this->getStudentsByClass($selectedClassId) : [];

        switch ($view) {

            case 'marks':
                $marks = $selectedStudentId
                    ? $this->model->getByStudent($selectedStudentId)
                    : [];
                $student = $selectedStudentId
                    ? $this->studentModel->find($selectedStudentId)
                    : null;
                MarkView::list(
                    $marks, $years, $selectedYear,
                    $classes, $selectedClassId,
                    $students, $selectedStudentId, $student
                );
                break;

            case 'add-mark':
                MarkView::addForm($subjects, $selectedStudentId, $selectedClassId, $selectedYear);
                break;

            case 'edit-mark':
                $mark = $this->model->find($_GET['id']);
                MarkView::editForm($mark, $subjects, $selectedStudentId, $selectedClassId, $selectedYear);
                break;
        }
    }

    private function getStudentsByClass($classId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM students WHERE class_id = :class_id ORDER BY name"
        );
        $stmt->execute(['class_id' => $classId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
