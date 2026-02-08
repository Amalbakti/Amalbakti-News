<?php
require __DIR__ . '/../../vendor/autoload.php';
$app = require __DIR__ . '/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$email = 'test@example.com';
$user = App\Models\User::where('email', $email)->first();
if (!$user) {
    echo "User not found: {$email}\n";
    exit(0);
}
$id = $user->id;
$name = $user->name;
echo "Found user: {$id}\t{$name}\t{$email}\n";
$user->delete();
// verify
$check = App\Models\User::where('email', $email)->first();
if (!$check) {
    echo "Deleted user {$email} (id: {$id}).\n";
} else {
    echo "Failed to delete user {$email}.\n";
}
