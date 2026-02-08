<?php
$db = new PDO('sqlite:'.getcwd().'/database/database.sqlite');
$rows = $db->query('SELECT id,name,slug FROM categories')->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $r) {
    echo $r['id'] . "\t" . $r['name'] . "\t" . ($r['slug'] ?? '') . "\n";
}
