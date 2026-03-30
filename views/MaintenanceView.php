<?php

class MaintenanceView
{
    public static function dashboard(): void
    {
        echo <<<HTML
        <h1>Karbantartás</h1>
        <p>Ezen az oldalon lehetőség van az adatbázis feltöltésére és az adatok kezelésére.</p>

        <h2>Adatgenerálás</h2>
        <form method="post" action="index.php?view=maintenance">
            <p>Az alábbi gombra kattintva a rendszer automatikusan generál legalább 3 tanévet,
               évfolyamonként 4-5 osztályt, osztályonként 12-15 tanulót,
               és minden tanulónak legalább 5 tantárgyból 3-4 jegyet.</p>
            <button type="submit" name="generate-data"
                    onclick="return confirm('Biztosan generálod az adatokat? Meglévő adatok megmaradnak.')">
                Adatok generálása
            </button>
        </form>

        <hr>
        <h2>Adattáblák kezelése</h2>
        <ul>
            <li><a href="index.php?view=maintenance-subjects">Tantárgyak kezelése</a></li>
            <li><a href="index.php?view=maintenance-classes">Osztályok kezelése</a></li>
            <li><a href="index.php?view=maintenance-students">Tanulók kezelése</a></li>
            <li><a href="index.php?view=maintenance-marks">Osztályzatok kezelése</a></li>
        </ul>
        HTML;
    }

    public static function generateResult(string $log): void
    {
        $safe = htmlspecialchars($log, ENT_QUOTES, 'UTF-8');
        echo <<<HTML
        <h1>Adatgenerálás eredménye</h1>
        <pre style="background:#f4f4f4;padding:10px;border:1px solid #ccc;">{$safe}</pre>
        <p><a href="index.php?view=maintenance">← Vissza a Karbantartáshoz</a></p>
        HTML;
    }

    

    public static function subjectList(array $items): void
    {
        echo <<<HTML
        <h1>Tantárgyak</h1>
        <p><a href="index.php?view=maintenance-subjects&add=1">+ Új tantárgy</a>
           | <a href="index.php?view=maintenance">← Karbantartás</a></p>
        <table border="1" cellpadding="5">
            <tr><th>ID</th><th>Név</th><th>Műveletek</th></tr>
        HTML;
        foreach ($items as $s) {
            $id   = $s['id'];
            $name = htmlspecialchars($s['name'], ENT_QUOTES, 'UTF-8');
            echo <<<HTML
            <tr>
                <td>{$id}</td><td>{$name}</td>
                <td>
                    <a href="index.php?view=maintenance-subjects&edit={$id}">Módosítás</a> |
                    <a href="index.php?view=maintenance-subjects&delete-subject={$id}"
                       onclick="return confirm('Biztos törlöd?')">Törlés</a>
                </td>
            </tr>
            HTML;
        }
        echo "</table>";
    }

    public static function subjectAdd(): void
    {
        echo <<<HTML
        <h1>Új tantárgy</h1>
        <form method="post" action="index.php?view=maintenance-subjects">
            <label>Név:</label><br>
            <input type="text" name="name" required><br><br>
            <button type="submit" name="add-subject">Hozzáadás</button>
            <a href="index.php?view=maintenance-subjects">Mégse</a>
        </form>
        HTML;
    }

    public static function subjectEdit(array $s): void
    {
        $id   = $s['id'];
        $name = htmlspecialchars($s['name'], ENT_QUOTES, 'UTF-8');
        echo <<<HTML
        <h1>Tantárgy módosítása</h1>
        <form method="post" action="index.php?view=maintenance-subjects">
            <input type="hidden" name="id" value="{$id}">
            <label>Név:</label><br>
            <input type="text" name="name" value="{$name}" required><br><br>
            <button type="submit" name="update-subject">Mentés</button>
            <a href="index.php?view=maintenance-subjects">Mégse</a>
        </form>
        HTML;
    }

    

    public static function classList(array $items): void
    {
        echo <<<HTML
        <h1>Osztályok</h1>
        <p><a href="index.php?view=maintenance-classes&add=1">+ Új osztály</a>
           | <a href="index.php?view=maintenance">← Karbantartás</a></p>
        <table border="1" cellpadding="5">
            <tr><th>ID</th><th>Tanév</th><th>Évfolyam</th><th>Betű</th><th>Műveletek</th></tr>
        HTML;
        foreach ($items as $c) {
            $id     = $c['id'];
            $year   = htmlspecialchars($c['year'],   ENT_QUOTES, 'UTF-8');
            $grade  = htmlspecialchars($c['grade'],  ENT_QUOTES, 'UTF-8');
            $letter = htmlspecialchars($c['letter'], ENT_QUOTES, 'UTF-8');
            echo <<<HTML
            <tr>
                <td>{$id}</td><td>{$year}</td><td>{$grade}</td><td>{$letter}</td>
                <td>
                    <a href="index.php?view=maintenance-classes&edit={$id}">Módosítás</a> |
                    <a href="index.php?view=maintenance-classes&delete-class={$id}"
                       onclick="return confirm('Biztos törlöd?')">Törlés</a>
                </td>
            </tr>
            HTML;
        }
        echo "</table>";
    }

    public static function classAdd(): void
    {
        echo <<<HTML
        <h1>Új osztály</h1>
        <form method="post" action="index.php?view=maintenance-classes">
            <label>Tanév:</label><br>
            <input type="number" name="year" required><br><br>
            <label>Évfolyam:</label><br>
            <input type="number" name="grade" required><br><br>
            <label>Betűjel:</label><br>
            <input type="text" name="letter" maxlength="1" required><br><br>
            <button type="submit" name="add-class">Hozzáadás</button>
            <a href="index.php?view=maintenance-classes">Mégse</a>
        </form>
        HTML;
    }

    public static function classEdit(array $c): void
    {
        $id     = $c['id'];
        $year   = htmlspecialchars($c['year'],   ENT_QUOTES, 'UTF-8');
        $grade  = htmlspecialchars($c['grade'],  ENT_QUOTES, 'UTF-8');
        $letter = htmlspecialchars($c['letter'], ENT_QUOTES, 'UTF-8');
        echo <<<HTML
        <h1>Osztály módosítása</h1>
        <form method="post" action="index.php?view=maintenance-classes">
            <input type="hidden" name="id" value="{$id}">
            <label>Tanév:</label><br>
            <input type="number" name="year" value="{$year}" required><br><br>
            <label>Évfolyam:</label><br>
            <input type="number" name="grade" value="{$grade}" required><br><br>
            <label>Betűjel:</label><br>
            <input type="text" name="letter" value="{$letter}" maxlength="1" required><br><br>
            <button type="submit" name="update-class">Mentés</button>
            <a href="index.php?view=maintenance-classes">Mégse</a>
        </form>
        HTML;
    }

    

    public static function studentList(array $items, array $classes): void
    {
        
        $classMap = [];
        foreach ($classes as $c) {
            $classMap[$c['id']] = $c['year'] . '/' . $c['grade'] . $c['letter'];
        }

        echo <<<HTML
        <h1>Tanulók</h1>
        <p><a href="index.php?view=maintenance-students&add=1">+ Új tanuló</a>
           | <a href="index.php?view=maintenance">← Karbantartás</a></p>
        <table border="1" cellpadding="5">
            <tr><th>ID</th><th>Osztály</th><th>Név</th><th>Születési dátum</th><th>Műveletek</th></tr>
        HTML;
        foreach ($items as $s) {
            $id         = $s['id'];
            $name       = htmlspecialchars($s['name'],       ENT_QUOTES, 'UTF-8');
            $birth_date = htmlspecialchars($s['birth_date'], ENT_QUOTES, 'UTF-8');
            $className  = $classMap[$s['class_id']] ?? $s['class_id'];
            echo <<<HTML
            <tr>
                <td>{$id}</td><td>{$className}</td><td>{$name}</td><td>{$birth_date}</td>
                <td>
                    <a href="index.php?view=maintenance-students&edit={$id}">Módosítás</a> |
                    <a href="index.php?view=maintenance-students&delete-student={$id}"
                       onclick="return confirm('Biztos törlöd?')">Törlés</a>
                </td>
            </tr>
            HTML;
        }
        echo "</table>";
    }

    public static function studentAdd(array $classes): void
    {
        $opts = self::classOptions($classes);
        echo <<<HTML
        <h1>Új tanuló</h1>
        <form method="post" action="index.php?view=maintenance-students">
            <label>Osztály:</label><br>
            <select name="class_id">{$opts}</select><br><br>
            <label>Név:</label><br>
            <input type="text" name="name" required><br><br>
            <label>Születési dátum:</label><br>
            <input type="date" name="birth_date" required><br><br>
            <button type="submit" name="add-student">Hozzáadás</button>
            <a href="index.php?view=maintenance-students">Mégse</a>
        </form>
        HTML;
    }

    public static function studentEdit(array $s, array $classes): void
    {
        $id         = $s['id'];
        $name       = htmlspecialchars($s['name'],       ENT_QUOTES, 'UTF-8');
        $birth_date = htmlspecialchars($s['birth_date'], ENT_QUOTES, 'UTF-8');
        $opts = self::classOptions($classes, $s['class_id']);
        echo <<<HTML
        <h1>Tanuló módosítása</h1>
        <form method="post" action="index.php?view=maintenance-students">
            <input type="hidden" name="id" value="{$id}">
            <label>Osztály:</label><br>
            <select name="class_id">{$opts}</select><br><br>
            <label>Név:</label><br>
            <input type="text" name="name" value="{$name}" required><br><br>
            <label>Születési dátum:</label><br>
            <input type="date" name="birth_date" value="{$birth_date}" required><br><br>
            <button type="submit" name="update-student">Mentés</button>
            <a href="index.php?view=maintenance-students">Mégse</a>
        </form>
        HTML;
    }

    

    public static function markList(array $items, array $students, array $subjects): void
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
        <p><a href="index.php?view=maintenance-marks&add=1">+ Új osztályzat</a>
           | <a href="index.php?view=maintenance">← Karbantartás</a></p>
        <table border="1" cellpadding="5">
            <tr><th>ID</th><th>Tanuló</th><th>Tantárgy</th><th>Jegy</th><th>Dátum</th><th>Műveletek</th></tr>
        HTML;
        foreach ($items as $m) {
            $id      = $m['id'];
            $student = $studentMap[$m['student_id']] ?? $m['student_id'];
            $subject = $subjectMap[$m['subject_id']] ?? $m['subject_id'];
            $mark    = htmlspecialchars($m['mark'], ENT_QUOTES, 'UTF-8');
            $date    = htmlspecialchars($m['date'], ENT_QUOTES, 'UTF-8');
            echo <<<HTML
            <tr>
                <td>{$id}</td><td>{$student}</td><td>{$subject}</td>
                <td>{$mark}</td><td>{$date}</td>
                <td>
                    <a href="index.php?view=maintenance-marks&edit={$id}">Módosítás</a> |
                    <a href="index.php?view=maintenance-marks&delete-mark={$id}"
                       onclick="return confirm('Biztos törlöd?')">Törlés</a>
                </td>
            </tr>
            HTML;
        }
        echo "</table>";
    }

    public static function markAdd(array $students, array $subjects): void
    {
        $sOpts  = self::studentOptions($students);
        $subOpts = self::subjectOptions($subjects);
        echo <<<HTML
        <h1>Új osztályzat</h1>
        <form method="post" action="index.php?view=maintenance-marks">
            <label>Tanuló:</label><br>
            <select name="student_id">{$sOpts}</select><br><br>
            <label>Tantárgy:</label><br>
            <select name="subject_id">{$subOpts}</select><br><br>
            <label>Jegy (1-5):</label><br>
            <input type="number" name="mark" min="1" max="5" required><br><br>
            <label>Dátum:</label><br>
            <input type="date" name="date" required><br><br>
            <button type="submit" name="add-mark">Hozzáadás</button>
            <a href="index.php?view=maintenance-marks">Mégse</a>
        </form>
        HTML;
    }

    public static function markEdit(array $m, array $students, array $subjects): void
    {
        $id   = $m['id'];
        $mark = htmlspecialchars($m['mark'], ENT_QUOTES, 'UTF-8');
        $date = htmlspecialchars($m['date'], ENT_QUOTES, 'UTF-8');
        $sOpts   = self::studentOptions($students, $m['student_id']);
        $subOpts = self::subjectOptions($subjects, $m['subject_id']);
        echo <<<HTML
        <h1>Osztályzat módosítása</h1>
        <form method="post" action="index.php?view=maintenance-marks">
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
            <a href="index.php?view=maintenance-marks">Mégse</a>
        </form>
        HTML;
    }

    

    private static function classOptions(array $classes, $selected = null): string
    {
        $html = '';
        foreach ($classes as $c) {
            $sel   = ($c['id'] == $selected) ? ' selected' : '';
            $label = htmlspecialchars($c['year'] . '/' . $c['grade'] . $c['letter'], ENT_QUOTES, 'UTF-8');
            $html .= "<option value=\"{$c['id']}\"{$sel}>{$label}</option>";
        }
        return $html;
    }

    private static function studentOptions(array $students, $selected = null): string
    {
        $html = '';
        foreach ($students as $s) {
            $sel  = ($s['id'] == $selected) ? ' selected' : '';
            $name = htmlspecialchars($s['name'], ENT_QUOTES, 'UTF-8');
            $html .= "<option value=\"{$s['id']}\"{$sel}>{$name}</option>";
        }
        return $html;
    }

    private static function subjectOptions(array $subjects, $selected = null): string
    {
        $html = '';
        foreach ($subjects as $s) {
            $sel  = ($s['id'] == $selected) ? ' selected' : '';
            $name = htmlspecialchars($s['name'], ENT_QUOTES, 'UTF-8');
            $html .= "<option value=\"{$s['id']}\"{$sel}>{$name}</option>";
        }
        return $html;
    }
}
