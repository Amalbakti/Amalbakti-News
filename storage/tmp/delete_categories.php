<?php
require __DIR__ . '/../../vendor/autoload.php';
$app = require __DIR__ . '/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$ids = [3, 6];
foreach ($ids as $id) {
    $c = App\Models\Category::find($id);
    if ($c) {
        echo "Deleting {$c->id}\t{$c->name}\n";
        $c->delete();
    } else {
        echo "Not found: {$id}\n";
    }
}
