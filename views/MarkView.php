<?php

class MarkView
{
    public static function list(
        array  $marks,
        array  $years,
        ?string $selectedYear,
        array  $classes,
        ?string $selectedClassId,
        array  $students,
        ?string $selectedStudentId,
        ?array  $student
    ): void {
        echo "<h1>Osztályzatok</h1>";

        
        echo '<form method="get" action="index.php" style="display:inline-block;margin-right:15px;">';
        echo '<input type="hidden" name="view" value="marks">';
        echo '<label><strong>Tanév:</strong></label> ';
        echo '<select name="year" onchange="this.form.submit()">';
        echo '<option value="">– válassz –</option>';
        foreach ($years as $y) {
            $val = htmlspecialchars($y['year'], ENT_QUOTES, 'UTF-8');
            $sel = ($val === $selectedYear) ? ' selected' : '';
            echo "<option value=\"{$val}\"{$sel}>{$val}</option>";
        }
        echo '</select></form>';

        
        if ($selectedYear && !empty($classes)) {
            echo '<form method="get" action="index.php" style="display:inline-block;margin-right:15px;">';
            echo '<input type="hidden" name="view" value="marks">';
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
            echo '</select></form>';
        }

        
        if ($selectedClassId && !empty($students)) {
            echo '<h2>Tanulók</h2>';
            echo '<table border="1" cellpadding="5">';
            echo '<tr><th>Név</th><th>Jegyek megtekintése</th></tr>';
            foreach ($students as $s) {
                $sid   = $s['id'];
                $sname = htmlspecialchars($s['name'], ENT_QUOTES, 'UTF-8');
                $active = ($sid == $selectedStudentId) ? ' style="background:#fffacd"' : '';
                $url = "index.php?view=marks&year={$selectedYear}&class_id={$selectedClassId}&student_id={$sid}";
                echo "<tr{$active}><td>{$sname}</td><td><a href=\"{$url}\">Jegyek</a></td></tr>";
            }
            echo '</table>';
        } elseif ($selectedClassId) {
            echo '<p>Nincs tanuló ebben az osztályban.</p>';
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

        
        if ($selectedStudentId && $student) {
            $sname = htmlspecialchars($student['name'], ENT_QUOTES, 'UTF-8');
            $addUrl = "index.php?view=add-mark&year={$selectedYear}&class_id={$selectedClassId}&student_id={$selectedStudentId}";

            echo "<hr><h2>{$sname} osztályzatai</h2>";
            echo "<p><a href=\"{$addUrl}\">+ Új osztályzat hozzáadása</a></p>";

            if (empty($marks)) {
                echo '<p>Nincs még osztályzat ehhez a tanulóhoz.</p>';
            } else {
                echo '<table border="1" cellpadding="5">';
                echo '<tr><th>Tantárgy</th><th>Jegy</th><th>Dátum</th><th>Műveletek</th></tr>';
                foreach ($marks as $m) {
                    $mid        = $m['id'];
                    $subName    = htmlspecialchars($m['subject_name'], ENT_QUOTES, 'UTF-8');
                    $mark       = htmlspecialchars($m['mark'],         ENT_QUOTES, 'UTF-8');
                    $date       = htmlspecialchars($m['date'],         ENT_QUOTES, 'UTF-8');
                    $editUrl    = "index.php?view=edit-mark&id={$mid}&year={$selectedYear}&class_id={$selectedClassId}&student_id={$selectedStudentId}";
                    $deleteUrl  = "index.php?view=marks&delete={$mid}&year={$selectedYear}&class_id={$selectedClassId}&student_id={$selectedStudentId}";
                    echo "<tr>";
                    echo "<td>{$subName}</td><td>{$mark}</td><td>{$date}</td>";
                    echo "<td><a href=\"{$editUrl}\">Módosítás</a> | ";
                    echo "<a href=\"{$deleteUrl}\" onclick=\"return confirm('Biztos törlöd?')\">Törlés</a></td>";
                    echo "</tr>";
                }
                echo '</table>';
            }
        }
    }

    public static function addForm(
        array  $subjects,
        ?string $studentId,
        ?string $classId,
        ?string $year
    ): void {
        $subjectOpts = '';
        foreach ($subjects as $s) {
            $sid  = $s['id'];
            $name = htmlspecialchars($s['name'], ENT_QUOTES, 'UTF-8');
            $subjectOpts .= "<option value=\"{$sid}\">{$name}</option>";
        }

        $backUrl = "index.php?view=marks&year={$year}&class_id={$classId}&student_id={$studentId}";

        echo <<<HTML
        <h1>Új osztályzat hozzáadása</h1>
        <form method="post" action="index.php?view=marks">
            <input type="hidden" name="student_id" value="{$studentId}">
            <input type="hidden" name="class_id"   value="{$classId}">
            <input type="hidden" name="year"        value="{$year}">

            <label>Tantárgy:</label><br>
            <select name="subject_id">{$subjectOpts}</select><br><br>

            <label>Jegy (1-5):</label><br>
            <input type="number" name="mark" min="1" max="5" required><br><br>

            <label>Dátum:</label><br>
            <input type="date" name="date" required><br><br>

            <button type="submit" name="add-mark">Hozzáadás</button>
            <a href="{$backUrl}">Mégse</a>
        </form>
        HTML;
    }

    public static function editForm(
        array  $m,
        array  $subjects,
        ?string $studentId,
        ?string $classId,
        ?string $year
    ): void {
        $id   = $m['id'];
        $mark = htmlspecialchars($m['mark'], ENT_QUOTES, 'UTF-8');
        $date = htmlspecialchars($m['date'], ENT_QUOTES, 'UTF-8');

        $subjectOpts = '';
        foreach ($subjects as $s) {
            $sid  = $s['id'];
            $name = htmlspecialchars($s['name'], ENT_QUOTES, 'UTF-8');
            $sel  = ($sid == $m['subject_id']) ? ' selected' : '';
            $subjectOpts .= "<option value=\"{$sid}\"{$sel}>{$name}</option>";
        }

        $backUrl = "index.php?view=marks&year={$year}&class_id={$classId}&student_id={$studentId}";

        echo <<<HTML
        <h1>Osztályzat módosítása</h1>
        <form method="post" action="index.php?view=marks">
            <input type="hidden" name="id"         value="{$id}">
            <input type="hidden" name="student_id" value="{$studentId}">
            <input type="hidden" name="class_id"   value="{$classId}">
            <input type="hidden" name="year"        value="{$year}">

            <label>Tantárgy:</label><br>
            <select name="subject_id">{$subjectOpts}</select><br><br>

            <label>Jegy (1-5):</label><br>
            <input type="number" name="mark" value="{$mark}" min="1" max="5" required><br><br>

            <label>Dátum:</label><br>
            <input type="date" name="date" value="{$date}" required><br><br>

            <button type="submit" name="update-mark">Mentés</button>
            <a href="{$backUrl}">Mégse</a>
        </form>
        HTML;
    }

    public static function maintenanceList(array $items, array $students, array $subjects, string $route): void
    {
        $studentMap = [];
        foreach ($students as $s) {
            $studentMap[$s['id']] = $s['name'];
        }
        $subjectMap = [];
        foreach ($subjects as $s) {
            $subjectMap[$s['id']] = $s['name'];
        }
        echo <<<HTML
        <h1>Osztályzatok</h1>
        <table border="1" cellpadding="5">
            <tr><th>ID</th><th>Tanuló</th><th>Tantárgy</th><th>Jegy</th><th>Dátum</th><th>Műveletek</th></tr>
        HTML;
        foreach ($items as $m) {
            $id      = $m['id'];
            $student = htmlspecialchars($studentMap[$m['student_id']] ?? $m['student_id'], ENT_QUOTES, 'UTF-8');
            $subject = htmlspecialchars($subjectMap[$m['subject_id']] ?? $m['subject_id'], ENT_QUOTES, 'UTF-8');
            $mark    = htmlspecialchars($m['mark'], ENT_QUOTES, 'UTF-8');
            $date    = htmlspecialchars($m['date'], ENT_QUOTES, 'UTF-8');
            echo <<<HTML
            <tr>
                <td>{$id}</td><td>{$student}</td><td>{$subject}</td>
                <td>{$mark}</td><td>{$date}</td>
                <td>
                    <a href="index.php?view={$route}&edit={$id}">Módosítás</a> |
                    <a href="index.php?view={$route}&delete-mark={$id}"
                       onclick="return confirm('Biztos törlöd?')">Törlés</a>
                </td>
            </tr>
            HTML;
        }
        echo "</table>";
    }

    public static function maintenanceAddForm(array $students, array $subjects, string $route): void
    {
        $sOpts   = self::buildOptions($students, 'name');
        $subOpts = self::buildOptions($subjects, 'name');
        $back    = "index.php?view={$route}";
        echo <<<HTML
        <h1>Új osztályzat</h1>
        <form method="post" action="index.php?view={$route}">
            <label>Tanuló:</label><br>
            <select name="student_id">{$sOpts}</select><br><br>
            <label>Tantárgy:</label><br>
            <select name="subject_id">{$subOpts}</select><br><br>
            <label>Jegy (1-5):</label><br>
            <input type="number" name="mark" min="1" max="5" required><br><br>
            <label>Dátum:</label><br>
            <input type="date" name="date" required><br><br>
            <button type="submit" name="add-mark">Hozzáadás</button>
            <a href="{$back}">Mégse</a>
        </form>
        HTML;
    }

    public static function maintenanceEditForm(array $m, array $students, array $subjects, string $route): void
    {
        $id      = $m['id'];
        $mark    = htmlspecialchars($m['mark'], ENT_QUOTES, 'UTF-8');
        $date    = htmlspecialchars($m['date'], ENT_QUOTES, 'UTF-8');
        $sOpts   = self::buildOptions($students, 'name',  $m['student_id']);
        $subOpts = self::buildOptions($subjects, 'name',  $m['subject_id']);
        $back    = "index.php?view={$route}";
        echo <<<HTML
        <h1>Osztályzat módosítása</h1>
        <form method="post" action="index.php?view={$route}">
            <input type="hidden" name="id" value="{$id}">
            <label>Tanuló:</label><br>
            <select name="student_id">{$sOpts}</select><br><br>
            <label>Tantárgy:</label><br>
            <select name="subject_id">{$subOpts}</select><br><br>
            <label>Jegy (1-5):</label><br>
            <input type="number" name="mark" value="{$mark}" min="1" max="5" required><br><br>
            <label>Dátum:</label><br>
            <input type="date" name="date" value="{$date}" required><br><br>
            <button type="submit" name="update-mark">Mentés</button>
            <a href="{$back}">Mégse</a>
        </form>
        HTML;
    }

    private static function buildOptions(array $items, string $labelField, $selected = null): string
    {
        $html = '';
        foreach ($items as $item) {
            $sel   = ($item['id'] == $selected) ? ' selected' : '';
            $label = htmlspecialchars($item[$labelField], ENT_QUOTES, 'UTF-8');
            $html .= "<option value=\"{$item['id']}\"{$sel}>{$label}</option>";
        }
        return $html;
    }
}
