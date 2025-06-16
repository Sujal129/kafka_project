<?php
header('Content-Type: application/json');

$users = [];

for ($i = 1; $i <= 10000; $i++) {
    $users[] = [
        'id' => $i,
        'name' => "User $i",
        'email' => "user$i@example.com",
        'username' => "user$i",
        'phone' => "123-456-789$i",
        'website' => "www.user$i.com"
    ];
}

echo json_encode($users, JSON_PRETTY_PRINT);
