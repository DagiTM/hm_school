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
            <p>Az alábbi gombra kattintva a rendszer automatikusan generálja az adatokat.</p>
            <button type="submit" name="generate-data"
                    onclick="return confirm('Biztosan generálod az adatokat? Meglévő adatok törlődnek.')">
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
}
