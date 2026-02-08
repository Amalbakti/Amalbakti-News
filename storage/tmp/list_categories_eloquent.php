<?php
require __DIR__ . '/../../vendor/autoload.php';
$app = require __DIR__ . '/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$categories = App\Models\Category::select('id','name','slug')->get();
foreach ($categories as $c) {
    echo "{$c->id}\t{$c->name}\t{$c->slug}\n";
}
