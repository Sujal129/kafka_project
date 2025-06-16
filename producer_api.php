<?php

use RdKafka\Conf;
use RdKafka\Producer;

// Show all errors (important for debugging large-scale runs)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('max_execution_time', 0); // Prevent timeout for long data

// Kafka configuration
$conf = new Conf();
$conf->set('metadata.broker.list', 'localhost:9092');
// Optional: enable compression for performance
$conf->set('compression.codec', 'snappy');

$producer = new Producer($conf);
$topic = $producer->newTopic("kafka_main");

// Fetch API data
$apiUrl = "http://localhost/api_10000_users.php";
$response = file_get_contents($apiUrl);

if ($response === false) {
    die("❌ Failed to fetch data from API\n");
}

$data = json_decode($response, true);

if (!is_array($data)) {
    die("❌ Invalid data format from API\n");
}
  
echo "✅ API Data Fetched:" . count($data) . " records\n";

// Produce messages to Kafka
$count = 0;
foreach ($data as $user) {
    $msg = "User: {$user['id']} - {$user['name']} ({$user['email']})";

    // Optionally use a key to keep users with same ID in same partition
    $key = $user['id'];

    $topic->produce(RD_KAFKA_PARTITION_UA, 0, $msg, (string)$key);
    echo "📤 Produced: $msg\n";

    // Poll Kafka producer queue to process delivery reports and events
    $producer->poll(0);

    // Small delay to prevent buffer overload (1ms)
    usleep(1000);

    $count++;
    // Optional: poll more deeply every 100 messages
    if ($count % 1000 === 0) {
        $producer->poll(10);
    }
}

// Ensure all remaining messages are sent
$producer->flush(10000); // wait up to 10 seconds

echo "✅ All $count messages sent to Kafka topic.\n";
