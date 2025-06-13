<?php
$conf = new RdKafka\Conf();
$conf->set('group.id', 'demo-consumer-group');

$conf->set('bootstrap.servers', 'localhost:9092');
$conf->set('auto.offset.reset', 'earliest');

$consumer = new RdKafka\KafkaConsumer($conf);
$consumer->subscribe(['kafka_main']);

echo "Consumer 1 started:\n";

while (true) {
    $message = $consumer->consume(120 * 1000);
    switch ($message->err) {
        case RD_KAFKA_RESP_ERR_NO_ERROR:
            echo "Consumer 1 received: " . $message->payload . "\n";
            break;
        case RD_KAFKA_RESP_ERR__PARTITION_EOF:
            break;
        case RD_KAFKA_RESP_ERR__TIMED_OUT:
            echo "Timed out\n";
            break;
        default:
            echo "Consumer 1 error: " . $message->errstr() . "\n";
            break;
    }
}
?>