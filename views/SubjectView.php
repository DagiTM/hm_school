<?php

class SubjectView
{
    public static function list($subjects, string $route = 'subjects')
    {
        $addRoute = ($route === 'subjects') ? 'add-subject' : "{$route}&add=1";
        echo <<<HTML
            <h1>Tantárgyak</h1>
            <p><a href="index.php?view={$addRoute}">Új tantárgy hozzáadása</a></p>
            <table border="1" cellpadding="5">
                <tr>
                    <th>ID</th>
                    <th>Név</th>
                    <th>Műveletek</th>
                </tr>
        HTML;
        foreach ($subjects as $s) {
            $id   = $s['id'];
            $name = htmlspecialchars($s['name'], ENT_QUOTES, 'UTF-8');
            $editUrl   = ($route === 'subjects')
                ? "index.php?view=edit-subject&id={$id}"
                : "index.php?view={$route}&edit={$id}";
            $deleteUrl = ($route === 'subjects')
                ? "index.php?view=subjects&delete={$id}"
                : "index.php?view={$route}&delete-subject={$id}";
            echo <<<HTML
                <tr>
                    <td>{$id}</td>
                    <td>{$name}</td>
                    <td>
                        <a href="{$editUrl}">Módosítás</a> |
                        <a href="{$deleteUrl}" onclick="return confirm('Biztos törlöd?')">Törlés</a>
                    </td>
                </tr>
            HTML;
        }
        echo "</table>";
    }

    public static function addForm(string $route = 'subjects')
    {
        $action = "index.php?view={$route}";
        $back   = ($route === 'subjects') ? "index.php?view=subjects" : "index.php?view={$route}";
        echo <<<HTML
            <h1>Új tantárgy hozzáadása</h1>
            <form method="post" action="{$action}">
                <label>Tantárgy neve:</label><br>
                <input type="text" name="name"><br><br>
                <button type="submit" name="add-subject">Hozzáadás</button>
                <a href="{$back}">Mégse</a>
            </form>
        HTML;
    }

    public static function editForm($subject, string $route = 'subjects')
    {
        $id     = $subject['id'];
        $name   = htmlspecialchars($subject['name'], ENT_QUOTES, 'UTF-8');
        $action = "index.php?view={$route}";
        $back   = ($route === 'subjects') ? "index.php?view=subjects" : "index.php?view={$route}";
        echo <<<HTML
            <h1>Tantárgy módosítása</h1>
            <form method="post" action="{$action}">
                <input type="hidden" name="id" value="{$id}">
                <label>Új név:</label><br>
                <input type="text" name="name" value="{$name}"><br><br>
                <button type="submit" name="update-subject">Mentés</button>
                <a href="{$back}">Mégse</a>
            </form>
        HTML;
    }
}
