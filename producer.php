<?php
$conf = new RdKafka\Conf();
$conf->set('bootstrap.servers', 'localhost:9092');

$producer = new RdKafka\Producer($conf);
$topic = $producer->newTopic("kafka_main");

$messages = ['User1 Data', 'User2 Data', 'User3 Data', 'User4 Data', 'User5 Data'];

foreach ($messages as $msg) {
    $topic->produce(RD_KAFKA_PARTITION_UA, 0, $msg);
    echo "Produced: $msg\n";
    $producer->poll(0);
    usleep(500000); // 0.5s delay
}

$producer->flush(10000);
?>
