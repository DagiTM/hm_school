<?php

class StudentView
{
    public static function list($students)
    {
        echo <<<HTML
            <h1>Tanulók</h1>

            <p><a href="index.php?view=add-student">Új tanuló hozzáadása</a></p>

            <table border="1" cellpadding="5">
                <tr>
                    <th>ID</th>
                    <th>Osztály</th>
                    <th>Név</th>
                    <th>Születési idő</th>
                    <th>Műveletek</th>
                </tr>
        HTML;
        foreach ($students as $s) {
            $id         = $s['id'];
            $class_label = htmlspecialchars($s['class_label'], ENT_QUOTES, 'UTF-8');
            $name        = htmlspecialchars($s['name'],        ENT_QUOTES, 'UTF-8');
            $birth_date  = htmlspecialchars($s['birth_date'],  ENT_QUOTES, 'UTF-8');

            echo <<<HTML
                <tr>
                    <td>{$id}</td>
                    <td>{$class_label}</td>
                    <td>{$name}</td>
                    <td>{$birth_date}</td>
                    <td>
                        <a href="index.php?view=edit-student&id={$id}">Módosítás</a> |
                        <a href="index.php?view=students&delete={$id}"
                           onclick="return confirm('Biztos törlöd?')">Törlés</a>
                    </td>
                </tr>
            HTML;
        }

        echo "</table>";
    }

    public static function addForm($classes = [])
    {
        $classOpts = '';
        foreach ($classes as $c) {
            $cid   = $c['id'];
            $label = htmlspecialchars($c['year'] . '/' . $c['grade'] . $c['letter'], ENT_QUOTES, 'UTF-8');
            $classOpts .= "<option value=\"{$cid}\">{$label}</option>";
        }

        
        $classField = $classOpts
            ? "<select name=\"class_id\">{$classOpts}</select>"
            : "<input type=\"number\" name=\"class_id\" required>";

        echo <<<HTML
            <h1>Új tanuló hozzáadása</h1>

            <form method="post" action="index.php?view=students">
                <label>Osztály:</label><br>
                {$classField}<br><br>
                <label>Név:</label><br>
                <input type="text" name="name" maxlength="50" required><br><br>
                <label>Születési idő:</label><br>
                <input type="date" name="birth_date" required><br><br>

                <button type="submit" name="add-student">Hozzáadás</button>
                <a href="index.php?view=students">Mégse</a>
            </form>
        HTML;
    }

    public static function editForm($student, $classes = [])
    {
        $id         = $student['id'];
        $name       = htmlspecialchars($student['name'],       ENT_QUOTES, 'UTF-8');
        $birth_date = htmlspecialchars($student['birth_date'], ENT_QUOTES, 'UTF-8');

        $classOpts = '';
        foreach ($classes as $c) {
            $cid   = $c['id'];
            $label = htmlspecialchars($c['year'] . '/' . $c['grade'] . $c['letter'], ENT_QUOTES, 'UTF-8');
            $sel   = ($cid == $student['class_id']) ? ' selected' : '';
            $classOpts .= "<option value=\"{$cid}\"{$sel}>{$label}</option>";
        }

        $classField = $classOpts
            ? "<select name=\"class_id\">{$classOpts}</select>"
            : "<input type=\"number\" name=\"class_id\" value=\"{$student['class_id']}\" required>";

        echo <<<HTML
            <h1>Tanuló módosítása</h1>

            <form method="post" action="index.php?view=students">
                <input type="hidden" name="id" value="{$id}">

                <label>Osztály:</label><br>
                {$classField}<br><br>

                <label>Név:</label><br>
                <input type="text" name="name" value="{$name}" maxlength="50" required><br><br>

                <label>Születési idő:</label><br>
                <input type="date" name="birth_date" value="{$birth_date}" required><br><br>

                <button type="submit" name="update-student">Mentés</button>
                <a href="index.php?view=students">Mégse</a>
            </form>
        HTML;
    }
}
