<?php
require_once "config.php";

class Install
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function generate(): string
    {
        $log = [];

        $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
        $this->pdo->exec("TRUNCATE TABLE `marks`");
        $this->pdo->exec("TRUNCATE TABLE `students`");
        $this->pdo->exec("TRUNCATE TABLE `classes`");
        $this->pdo->exec("TRUNCATE TABLE `subjects`");
        $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
        $log[] = "Meglévő adatok törölve.";

        $subjectIds = $this->generateSubjects($log);
        $this->generateClassesAndStudents($subjectIds, $log);

        $log[] = "Adatgenerálás sikeresen befejezve.";
        return implode("\n", $log);
    }

    private function generateSubjects(array &$log): array
    {
        $ids = [];
        foreach (SUBJECTS as $name) {
            $stmt = $this->pdo->prepare("INSERT INTO subjects (name) VALUES (:name)");
            $stmt->execute(['name' => $name]);
            $ids[] = $this->pdo->lastInsertId();
            $log[] = "Tantárgy létrehozva: {$name}";
        }
        return $ids;
    }

    private function generateClassesAndStudents(array $subjectIds, array &$log): void
    {
        $years = [2022, 2023, 2024];

        foreach ($years as $year) {
            foreach (CLASSES as $classLabel) {
                preg_match('/(\d+)([A-Z]+)/', $classLabel, $m);
                $grade  = $m[1];
                $letter = $m[2];

                $stmt = $this->pdo->prepare(
                    "INSERT INTO classes (grade, letter, year) VALUES (:grade, :letter, :year)"
                );
                $stmt->execute(['grade' => $grade, 'letter' => $letter, 'year' => $year]);
                $classId = $this->pdo->lastInsertId();
                $log[] = "Osztály létrehozva: {$year}/{$classLabel}";

                $count = rand(MIN_CLASS_COUNT, MAX_CLASS_COUNT);
                $usedNames = [];

                for ($i = 0; $i < $count; $i++) {
                    $name = $this->uniqueName($usedNames);
                    $usedNames[] = $name;

                    $birthYear = $year - $grade + 6;
                    $birthDate = $birthYear . '-'
                        . str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT) . '-'
                        . str_pad(rand(1, 28), 2, '0', STR_PAD_LEFT);

                    $stmt = $this->pdo->prepare(
                        "INSERT INTO students (class_id, name, birth_date) VALUES (:class_id, :name, :birth_date)"
                    );
                    $stmt->execute(['class_id' => $classId, 'name' => $name, 'birth_date' => $birthDate]);
                    $studentId = $this->pdo->lastInsertId();

                    foreach ($subjectIds as $subjectId) {
                        for ($j = 0; $j < MARKS_COUNT; $j++) {
                            $mark = rand(1, 5);
                            $month = rand(9, 12);
                            $day   = rand(1, 28);
                            $date  = $year . '-'
                                . str_pad($month, 2, '0', STR_PAD_LEFT) . '-'
                                . str_pad($day,   2, '0', STR_PAD_LEFT);

                            $stmt = $this->pdo->prepare(
                                "INSERT INTO marks (subject_id, mark, student_id, date)
                                 VALUES (:subject_id, :mark, :student_id, :date)"
                            );
                            $stmt->execute([
                                'subject_id' => $subjectId,
                                'mark'       => $mark,
                                'student_id' => $studentId,
                                'date'       => $date,
                            ]);
                        }
                    }
                }
                $log[] = "  → {$count} tanuló létrehozva ({$year}/{$classLabel})";
            }
        }
    }

    private function uniqueName(array $used): string
    {
        $lastNames  = NAMES['lastnames'];
        $allFirsts  = array_merge(NAMES['firstnames']['men'], NAMES['firstnames']['women']);

        do {
            $name = $lastNames[array_rand($lastNames)] . ' ' . $allFirsts[array_rand($allFirsts)];
        } while (in_array($name, $used));

        return $name;
    }
}
