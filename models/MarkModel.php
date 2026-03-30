<?php

class MarkModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        return $this->pdo
            ->query("SELECT m.*, s.name AS subject_name, st.name AS student_name
                     FROM marks m
                     JOIN subjects s ON s.id = m.subject_id
                     JOIN students st ON st.id = m.student_id
                     ORDER BY m.id DESC")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM marks WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByStudent($student_id)
    {
        $stmt = $this->pdo->prepare(
            "SELECT m.*, s.name AS subject_name
             FROM marks m
             JOIN subjects s ON s.id = m.subject_id
             WHERE m.student_id = :student_id
             ORDER BY s.name, m.date"
        );
        $stmt->execute(['student_id' => $student_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($subject_id, $mark, $student_id, $date)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO marks (subject_id, mark, student_id, date)
             VALUES (:subject_id, :mark, :student_id, :date)"
        );
        $stmt->execute(['subject_id' => $subject_id, 'mark' => $mark,
                        'student_id' => $student_id, 'date' => $date]);
    }

    public function update($id, $subject_id, $mark, $student_id, $date)
    {
        $stmt = $this->pdo->prepare(
            "UPDATE marks SET subject_id = :subject_id, mark = :mark,
             student_id = :student_id, date = :date WHERE id = :id"
        );
        $stmt->execute(['subject_id' => $subject_id, 'mark' => $mark,
                        'student_id' => $student_id, 'date' => $date, 'id' => $id]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM marks WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
