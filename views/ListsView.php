<?php

class ListsView
{
    public static function render(
        array  $years,
        ?string $selectedYear,
        array  $classes,
        ?string $selectedClassId,
        ?array  $classData,
        array  $students,
        ?float  $classAvg,
        array  $subjectAvgs,
        ?string $selectedStudent,
        ?array  $studentDetail,
        ?float  $studentAvg,
        array  $studentSubjectAvg
    ): void {
        echo "<h1>Listák</h1>";

        
        echo '<form method="get" action="index.php" style="display:inline-block;margin-right:20px;">';
        echo '<input type="hidden" name="view" value="lists">';
        echo '<label><strong>Tanév:</strong></label> ';
        echo '<select name="year" onchange="this.form.submit()">';
        echo '<option value="">– válassz –</option>';
        foreach ($years as $y) {
            $val = htmlspecialchars($y['year'], ENT_QUOTES, 'UTF-8');
            $sel = ($val === $selectedYear) ? ' selected' : '';
            echo "<option value=\"{$val}\"{$sel}>{$val}</option>";
        }
        echo '</select>';
        echo '</form>';

        
        if ($selectedYear && !empty($classes)) {
            echo '<form method="get" action="index.php" style="display:inline-block;">';
            echo '<input type="hidden" name="view" value="lists">';
            echo '<input type="hidden" name="year" value="' . htmlspecialchars($selectedYear, ENT_QUOTES, 'UTF-8') . '">';
            echo '<label><strong>Osztály:</strong></label> ';
            echo '<select name="class_id" onchange="this.form.submit()">';
            echo '<option value="">– válassz –</option>';
            foreach ($classes as $c) {
                $cid   = $c['id'];
                $label = htmlspecialchars($c['grade'] . $c['letter'], ENT_QUOTES, 'UTF-8');
                $sel   = ($cid == $selectedClassId) ? ' selected' : '';
                echo "<option value=\"{$cid}\"{$sel}>{$label}</option>";
            }
            echo '</select>';
            echo '</form>';
        }

        if (!$selectedYear) {
            echo '<p>Kérjük, válasszon tanévet!</p>';
            return;
        }
        if ($selectedYear && empty($classes)) {
            echo '<p>Ehhez a tanévhez nincs osztály.</p>';
            return;
        }
        if (!$selectedClassId) {
            echo '<p>Kérjük, válasszon osztályt!</p>';
            return;
        }

        
        $grade  = htmlspecialchars($classData['grade'],  ENT_QUOTES, 'UTF-8');
        $letter = htmlspecialchars($classData['letter'], ENT_QUOTES, 'UTF-8');
        $year   = htmlspecialchars($classData['year'],   ENT_QUOTES, 'UTF-8');

        echo "<h2>{$year}/{$grade}{$letter} osztály tanulói</h2>";

        if ($classAvg !== null) {
            echo "<p><strong>Osztály tanulmányi átlaga:</strong> {$classAvg}</p>";
        } else {
            echo "<p><em>Nincs elérhető jegy az osztályban.</em></p>";
        }

        
        if (!empty($subjectAvgs)) {
            echo "<h3>Tantárgyankénti átlag az osztályban</h3>";
            echo '<table border="1" cellpadding="5">';
            echo '<tr><th>Tantárgy</th><th>Átlag</th></tr>';
            foreach ($subjectAvgs as $sa) {
                $subName = htmlspecialchars($sa['subject_name'], ENT_QUOTES, 'UTF-8');
                $avg     = round($sa['avg'], 2);
                echo "<tr><td>{$subName}</td><td>{$avg}</td></tr>";
            }
            echo '</table>';
        }

        
        if (empty($students)) {
            echo '<p>Nincs tanuló ebben az osztályban.</p>';
            return;
        }

        echo '<h3>Tanulók</h3>';
        echo '<table border="1" cellpadding="5">';
        echo '<tr><th>Név</th><th>Születési dátum</th><th>Részletek</th></tr>';
        foreach ($students as $s) {
            $sid   = $s['id'];
            $sname = htmlspecialchars($s['name'],       ENT_QUOTES, 'UTF-8');
            $bdate = htmlspecialchars($s['birth_date'], ENT_QUOTES, 'UTF-8');
            $sel   = ($sid == $selectedStudent) ? ' style="background:#fffacd"' : '';
            echo "<tr{$sel}>";
            echo "<td>{$sname}</td><td>{$bdate}</td>";
            echo "<td><a href=\"index.php?view=lists&year={$selectedYear}&class_id={$selectedClassId}&student_id={$sid}\">Részletek</a></td>";
            echo "</tr>";
        }
        echo '</table>';

        
        if ($studentDetail) {
            $sname = htmlspecialchars($studentDetail['name'], ENT_QUOTES, 'UTF-8');
            echo "<hr><h2>{$sname} részletes adatai</h2>";

            if ($studentAvg !== null) {
                echo "<p><strong>Tanulmányi átlag:</strong> {$studentAvg}</p>";
            } else {
                echo "<p><em>Nincs jegy rögzítve.</em></p>";
            }

            if (!empty($studentSubjectAvg)) {
                echo "<h3>Tantárgyankénti átlag</h3>";
                echo '<table border="1" cellpadding="5">';
                echo '<tr><th>Tantárgy</th><th>Átlag</th></tr>';
                foreach ($studentSubjectAvg as $sa) {
                    $subName = htmlspecialchars($sa['subject_name'], ENT_QUOTES, 'UTF-8');
                    $avg     = round($sa['avg'], 2);
                    echo "<tr><td>{$subName}</td><td>{$avg}</td></tr>";
                }
                echo '</table>';
            }

            $backUrl = "index.php?view=lists&year={$selectedYear}&class_id={$selectedClassId}";
            echo "<p><a href=\"{$backUrl}\">← Vissza az osztályhoz</a></p>";
        }
    }
}
