<?php

class ClassModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        return $this->pdo
            ->query("SELECT * FROM classes ORDER BY id DESC, grade ASC, letter ASC")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM classes WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($grade, $letter, $year)
    {
        $stmt = $this->pdo->prepare("INSERT INTO classes (grade, letter, year) VALUES (:grade, :letter, :year)");
        $stmt->execute(['grade' => $grade, 'letter' => $letter, 'year' => $year]);
    }

    public function update($id, $grade, $letter, $year)
    {
        $stmt = $this->pdo->prepare("UPDATE classes SET grade = :grade, letter = :letter, year = :year WHERE id = :id");
        $stmt->execute(['grade' => $grade, 'letter' => $letter, 'year' => $year]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM classes WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function getYears() {
        return $this->pdo
            ->query("SELECT DISTINCT year FROM `classes`")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getClassesByYear($year) {
        $stmt = $this->pdo->prepare("SELECT * FROM classes WHERE year = :year");
        $stmt->execute(['year' => $year]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getClassesByYearAll($year) {
        $stmt = $this->pdo->prepare("SELECT * FROM classes WHERE year = :year ORDER BY grade ASC, letter ASC");
        $stmt->execute(['year' => $year]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
