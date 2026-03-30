<?php
require_once "models/ClassModel.php";
require_once "models/StudentModel.php";
require_once "models/SubjectModel.php";
require_once "models/MarkModel.php";
require_once "views/ListsView.php";

class ListsController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function handleRequest(string $view): void
    {
        $classModel   = new ClassModel($this->pdo);
        $subjectModel = new SubjectModel($this->pdo);

        $years   = $classModel->getYears();
        $subjects = $subjectModel->getAll();

        $selectedYear    = $_GET['year']    ?? null;
        $selectedClassId = $_GET['class_id'] ?? null;
        $selectedStudent = $_GET['student_id'] ?? null;

        $classes = [];
        if ($selectedYear) {
            $classes = $classModel->getClassesByYearAll($selectedYear);
        }

        $students = [];
        $classData = null;
        $classAvg  = null;
        $subjectAvgs = [];

        if ($selectedClassId) {
            $classData = $classModel->find($selectedClassId);

            
            $stmt = $this->pdo->prepare(
                "SELECT s.* FROM students s WHERE s.class_id = :class_id ORDER BY s.name"
            );
            $stmt->execute(['class_id' => $selectedClassId]);
            $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

            
            $stmt = $this->pdo->prepare(
                "SELECT AVG(m.mark) as avg
                 FROM marks m
                 JOIN students s ON s.id = m.student_id
                 WHERE s.class_id = :class_id"
            );
            $stmt->execute(['class_id' => $selectedClassId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $classAvg = $row['avg'] ? round($row['avg'], 2) : null;

            
            $stmt = $this->pdo->prepare(
                "SELECT sub.name AS subject_name, AVG(m.mark) AS avg
                 FROM marks m
                 JOIN students s ON s.id = m.student_id
                 JOIN subjects sub ON sub.id = m.subject_id
                 WHERE s.class_id = :class_id
                 GROUP BY sub.id, sub.name
                 ORDER BY sub.name"
            );
            $stmt->execute(['class_id' => $selectedClassId]);
            $subjectAvgs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        
        $studentDetail     = null;
        $studentAvg        = null;
        $studentSubjectAvg = [];

        if ($selectedStudent) {
            $stmt = $this->pdo->prepare("SELECT * FROM students WHERE id = :id");
            $stmt->execute(['id' => $selectedStudent]);
            $studentDetail = $stmt->fetch(PDO::FETCH_ASSOC);

            
            $stmt = $this->pdo->prepare(
                "SELECT AVG(mark) as avg FROM marks WHERE student_id = :id"
            );
            $stmt->execute(['id' => $selectedStudent]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $studentAvg = $row['avg'] ? round($row['avg'], 2) : null;

            
            $stmt = $this->pdo->prepare(
                "SELECT sub.name AS subject_name, AVG(m.mark) AS avg
                 FROM marks m
                 JOIN subjects sub ON sub.id = m.subject_id
                 WHERE m.student_id = :id
                 GROUP BY sub.id, sub.name
                 ORDER BY sub.name"
            );
            $stmt->execute(['id' => $selectedStudent]);
            $studentSubjectAvg = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        ListsView::render(
            $years,
            $selectedYear,
            $classes,
            $selectedClassId,
            $classData,
            $students,
            $classAvg,
            $subjectAvgs,
            $selectedStudent,
            $studentDetail,
            $studentAvg,
            $studentSubjectAvg
        );
    }
}
