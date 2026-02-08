<?php
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__ . '/../resources/views'));
$pattern = '/public\s+[\w\[\]\|\s]*\$([a-zA-Z_][a-zA-Z0-9_]*)/';
foreach ($files as $file) {
    if ($file->isFile() && strpos($file->getFilename(), '.blade.php') !== false) {
        $content = file_get_contents($file->getPathname());
        if (strpos($content, 'new class extends Component') !== false) {
            preg_match_all($pattern, $content, $m);
            $counts = array_count_values($m[1]);
            $dups = array_filter($counts, fn($c) => $c > 1);
            if (!empty($dups)) {
                echo "FILE: " . $file->getPathname() . PHP_EOL;
                foreach ($dups as $name => $count) {
                    echo "  $name => $count" . PHP_EOL;
                }
            }
        }
    }
}
