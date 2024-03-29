<?php


$conf = new RdKafka\Conf();

// Set a rebalance callback to log partition assignments (optional)
$conf->setRebalanceCb(function (RdKafka\KafkaConsumer $kafka, $err, array $partitions = null) {
    switch ($err) {
        case RD_KAFKA_RESP_ERR__ASSIGN_PARTITIONS:
            echo "Assign: ";
            var_dump($partitions);
            $kafka->assign($partitions);
            break;

         case RD_KAFKA_RESP_ERR__REVOKE_PARTITIONS:
             echo "Revoke: ";
             var_dump($partitions);
             $kafka->assign(NULL);
             break;

         default:
            throw new \Exception($err);
    }
});


//payload

$group_id             = 'kafka-quickstart-producer';
$metadata_broker_list = 'gsl-mensageria-kafka';
$bootstrap_servers    = 'gsl-mensageria-kafka:9092';
$auto_offset_reset    = 'earliest';
$topic                = 'relatorio-entrega-recebido';
$consume_timeout      = 120*1000;


//$group_id             = $_ENV['kafka.group.id']; //'kafka-quickstart-producer'
//$metadata_broker_list = $_ENV['kafka.metadata.broker.list']; //'gsl-mensageria-kafka'
//$bootstrap_servers    = $_ENV['kafka.bootstrap.servers' ]; //'gsl-mensageria-kafka:9092'
//$auto_offset_reset    = $_ENV['kafka.auto.offset.reset']; //'earliest'
if(isset($_ENV['kafka.topic'])) {
    $topic                = $_ENV['kafka.topic'];
    echo "Valor do tópico carregado da variável de ambiente: [$topic]";
}
//$consume_timeout      = $_ENV['kafka.consume.timeout']; //120*1000


echo "group_id = $group_id, " .
  "metadata_broker_list = $metadata_broker_list, " .
  "bootstrap_servers = $bootstrap_servers, " .
  "auto_offset_reset = $auto_offset_reset, " .
  "topic = $topic, " .
  "consume_timeout = $consume_timeout";

$conf->set('group.id', $group_id);
//$conf->set('group.id', 'kafka-quickstart-processor');

// Initial list of Kafka brokers
$conf->set('metadata.broker.list', $metadata_broker_list);
$conf->set('bootstrap.servers', $bootstrap_servers);

// Set where to start consuming messages when there is no initial offset in
// offset store or the desired offset is out of range.
// 'earliest': start from the beginning
$conf->set('auto.offset.reset', $auto_offset_reset);

$consumer = new RdKafka\KafkaConsumer($conf);

// Subscribe to topic 
$consumer->subscribe([$topic]);

$arquivoAtual = $_SERVER ['SCRIPT_NAME'];
echo "\n[" . date('Y-m-d h:i:s') . "] INFO ********************** Iniciando consumo do tópico [$topic] - Por: $arquivoAtual ********************************************************\n";

$url_proxy = "http://gse-entregas-service-php:9000/";
if(isset($_ENV["services.SgeEntregasService.url"])) {
    $url_proxy = $_ENV["services.SgeEntregasService.url"];
}
$url_proxy = rtrim($url_proxy, '/');

echo "Waiting for partition assignment... (make take some time when\n";
echo "quickly re-joining the group after leaving it.)\n";

while (true) {
    $message = $consumer->consume($consume_timeout);
    switch ($message->err) {
        case RD_KAFKA_RESP_ERR_NO_ERROR:

            echo "\n=======================";
            echo "\n[" . date('Y-m-d h:i:s') . "] INFO Iniciandio tratamento da mensagem por: $arquivoAtual ...\n";
            var_dump($message);
            echo "\n=======================";
            echo "\n=======================";
            echo "\n[" . date('Y-m-d h:i:s') . "] INFO Chamando endpoint $url_proxy/relatorio_entrega com POST...\n";
            try {
                $json = $message->payload;

                $ch = curl_init("$url_proxy/relatorio_entrega");
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json);                                                                  
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                    'Content-Type: application/json',                                                                                
                    'Content-Length: ' . strlen($json))                                                                       
                );

                $resultado = curl_exec($ch);

                echo "\n[" . date('Y-m-d h:i:s') . "] INFO Resultado da chamanda do endpoint: " . $resultado;
                echo "\n=======================";
            } catch (Exception $e) {
                echo "\n[" . date('Y-m-d h:i:s') . "] ERROR Chamando endpoint com POST...Erro: " . $e->getMessage();
            }
            break;
        case RD_KAFKA_RESP_ERR__PARTITION_EOF:
            echo "No more messages; will wait for more\n";
            break;
        case RD_KAFKA_RESP_ERR__TIMED_OUT:
            echo "Timed out\n";
            break;
        default:
        echo "\n[" . date('Y-m-d h:i:s') . "] ERROR Code: $message->err - Mensagem: $message->errstr()"; 
            //throw new \Exception($message->errstr(), $message->err);
            break;
    }
}

//https://github.com/arnaud-lb/php-rdkafka#examples
//https://arnaud.le-blanc.net/php-rdkafka-doc/phpdoc/rdkafka.examples-high-level-consumer.html

//baseado em https://github.com/scalablescripts/php-kafka/blob/main/consumer.php
// e https://www.youtube.com/watch?v=7ZrGVpExBHU&t=7s

?>
