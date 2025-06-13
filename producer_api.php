<?php

use RdKafka\Conf;
use RdKafka\Producer;

$conf = new Conf();
$conf->set('metadata.broker.list', 'localhost:9092');

$producer = new Producer($conf);
$topic = $producer->newTopic("kafka_main");

// Fetch API Data
$apiUrl = "https://jsonplaceholder.typicode.com/users";
$response = file_get_contents($apiUrl);

if ($response === false) {
    die("❌ Failed to fetch data from API\n");
}

$data = json_decode($response, true);

if (!is_array($data)) {
    die("❌ Invalid data format from API\n");
}

// Produce messages to Kafka
foreach ($data as $user) {
    $msg = "User: {$user['id']} - {$user['name']} ({$user['email']})";
    $topic->produce(RD_KAFKA_PARTITION_UA, 0, $msg);
    echo "✅ Produced: $msg\n";
    $producer->poll(0);
    usleep(500000); // Optional delay
}

while ($producer->getOutQLen() > 0) {
    $producer->poll(100);
}

echo "✅ All API data sent to Kafka topic.\n";
