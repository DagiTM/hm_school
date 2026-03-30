<?php

class ClassView
{
    public static function list($classes, string $route = 'classes')
    {
        $addRoute = ($route === 'classes') ? 'add-class' : "{$route}&add=1";
        echo <<<HTML
            <h1>Osztályok</h1>
            <p><a href="index.php?view={$addRoute}">Új osztály hozzáadása</a></p>
            <table border="1" cellpadding="5">
                <tr>
                    <th>ID</th>
                    <th>Tanév</th>
                    <th>Évfolyam</th>
                    <th>Betűjel</th>
                    <th>Műveletek</th>
                </tr>
        HTML;
        foreach ($classes as $c) {
            $id     = $c['id'];
            $grade  = htmlspecialchars($c['grade'],  ENT_QUOTES, 'UTF-8');
            $letter = htmlspecialchars($c['letter'], ENT_QUOTES, 'UTF-8');
            $year   = htmlspecialchars($c['year'],   ENT_QUOTES, 'UTF-8');
            $editUrl   = ($route === 'classes')
                ? "index.php?view=edit-class&id={$id}"
                : "index.php?view={$route}&edit={$id}";
            $deleteUrl = ($route === 'classes')
                ? "index.php?view=classes&delete={$id}"
                : "index.php?view={$route}&delete-class={$id}";
            echo <<<HTML
                <tr>
                    <td>{$id}</td>
                    <td>{$grade}</td>
                    <td>{$letter}</td>
                    <td>{$year}</td>
                    <td>
                        <a href="{$editUrl}">Módosítás</a> |
                        <a href="{$deleteUrl}" onclick="return confirm('Biztos törlöd?')">Törlés</a>
                    </td>
                </tr>
            HTML;
        }
        echo "</table>";
    }

    public static function addForm(string $route = 'classes')
    {
        $action = "index.php?view={$route}";
        $back   = ($route === 'classes') ? "index.php?view=classes" : "index.php?view={$route}";
        echo <<<HTML
            <h1>Új osztály hozzáadása</h1>
            <form method="post" action="{$action}">
                <label>Tanév:</label><br>
                <input type="number" name="year"><br><br>
                <label>Évfolyam:</label><br>
                <input type="number" name="grade"><br><br>
                <label>Betűjel:</label><br>
                <input type="text" name="letter"><br><br>
                <button type="submit" name="add-class">Hozzáadás</button>
                <a href="{$back}">Mégse</a>
            </form>
        HTML;
    }

    public static function editForm($class, string $route = 'classes')
    {
        $id     = $class['id'];
        $grade  = htmlspecialchars($class['grade'],  ENT_QUOTES, 'UTF-8');
        $letter = htmlspecialchars($class['letter'], ENT_QUOTES, 'UTF-8');
        $year   = htmlspecialchars($class['year'],   ENT_QUOTES, 'UTF-8');
        $action = "index.php?view={$route}";
        $back   = ($route === 'classes') ? "index.php?view=classes" : "index.php?view={$route}";
        echo <<<HTML
            <h1>Tantárgy módosítása</h1>
            <form method="post" action="{$action}">
                <input type="hidden" name="id" value="{$id}">
                <label>Tanév:</label><br>
                <input type="number" name="year" value="{$year}"><br><br>
                <label>Évfolyam:</label><br>
                <input type="number" name="grade" value="{$grade}"><br><br>
                <label>Betűjel:</label><br>
                <input type="text" name="letter" value="{$letter}"><br><br>
                <button type="submit" name="update-class">Mentés</button>
                <a href="{$back}">Mégse</a>
            </form>
        HTML;
    }
}
