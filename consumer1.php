<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('max_execution_time', 0); // Prevent timeout

$conf = new RdKafka\Conf();
$conf->set('group.id', 'demo-consumer-group');

// Start reading from earliest if no previous offset exists
$conf->set('auto.offset.reset', 'earliest');

// Optional: manual offset commit control (future use)
// $conf->set('enable.auto.commit', 'false');

$conf->set('bootstrap.servers', 'localhost:9092');

$consumer = new RdKafka\KafkaConsumer($conf);
$consumer->subscribe(['kafka_main']);

echo "🟢 Consumer 1 started and listening on 'kafka_main' topic...\n";

while (true) {
    $message = $consumer->consume(1000); // Wait max 1 second
    switch ($message->err) {
        case RD_KAFKA_RESP_ERR_NO_ERROR:
            echo "✅ Consumer 1 received: " . $message->payload . "\n";

            // Optional: commit manually if auto commit is disabled
            // $consumer->commit($message);
            break;

        case RD_KAFKA_RESP_ERR__PARTITION_EOF:
            // End of partition event (normal)
            break;

        case RD_KAFKA_RESP_ERR__TIMED_OUT:
            // No message in timeout window
            break;

        default:
            echo "❌ Consumer 1 error: " . $message->errstr() . "\n";
            break;
    }

    usleep(5000); // Small delay (50ms) to reduce CPU usage
}
