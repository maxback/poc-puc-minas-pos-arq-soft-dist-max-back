<?php
//namespace Src;

//use Src\Database;
//use RdKafka\Conf;

/*
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$dbConnection = (new Database())->connet();
*/
class ConsumerKafkaBase {

    protected $db;
    protected $consumer;
    protected $topic;
    protected $consume_timeout;
  
    public function __construct()
    {
        $this->db = $dbConnection;
        $conf = new RdKafka\Conf();

        $group_id             = 'kafka-quickstart-producer';
        $metadata_broker_list = 'gsl-mensageria-kafka';
        $bootstrap_servers    = 'gsl-mensageria-kafka:9092';
        $auto_offset_reset    = 'earliest';
        $topic                = 'relatorio-entrega-recebido';
        $consume_timeout      = 2*60*1000;

        /*
        $group_id             = $_ENV['kafka.group.id']; //'kafka-quickstart-producer'
        $metadata_broker_list = $_ENV['kafka.metadata.broker.list']; //'gsl-mensageria-kafka'
        $bootstrap_servers    = $_ENV['kafka.bootstrap.servers' ]; //'gsl-mensageria-kafka:9092'
        $auto_offset_reset    = $_ENV['kafka.auto.offset.reset']; //'earliest'
        $topic                = $_ENV['kafka.default.topic']; //'relatorio-entrega-recebido'
        $consume_timeout      = $_ENV['kafka.consume.timeout']; //120*1000
        */
        if(isset($_ENV['kafka.consume.timeout'])) {
            $consume_timeout      = $_ENV['kafka.consume.timeout'];
        }

        echo "\n\ngroup_id = $group_id, " .
        "kafka.metadata_broker_list = $metadata_broker_list, " .
        "kafka.bootstrap_servers = $bootstrap_servers, " .
        "kafka.auto_offset_reset = $auto_offset_reset, " .
        "kafka.default.topic = $topic, " .
        "kafka.consume_timeout = $consume_timeout\n\n";

        $conf->set('group.id', $group_id);
        //$conf->set('group.id', 'kafka-quickstart-processor');

        // Initial list of Kafka brokers
        $conf->set('metadata.broker.list', $metadata_broker_list);
        $conf->set('bootstrap.servers', $bootstrap_servers);

        // Set where to start consuming messages when there is no initial offset in
        // offset store or the desired offset is out of range.
        // 'earliest': start from the beginning
        $conf->set('auto.offset.reset', $auto_offset_reset);

        $this->consumer = new RdKafka\KafkaConsumer($conf); 
        
        $this->topic = $topic;
        $this->consume_timeout = $consume_timeout;
    }


    //fazer overrride na classe filha
    protected function hookTrataNovoJson($topic, $JsonStr) {
        echo "***** Sem hook para tratar este evento => Será descartado!! *****";
    }

    //consime um topico que vai se carregar de $_ENV[]
    public function consumir() {
        $this->consumirTopico(null);
    }

    //consime um topico, passado por parametro para o bloker definido nas variaveis de ambiente
    //se pasar em branco vai se carregar de $_ENV[]
    public function consumirTopico($topic) {

        if($topic === null || $tipoc == "") {
            $topic = $this->topic;
        }

        $arquivoAtual = $_SERVER ['SCRIPT_NAME'];
        echo "\n[" . date('Y-m-d h:i:s') . "] INFO ********************** Iniciando consumo do tópico [$topic] - Por: $arquivoAtual ********************************************************\n";
        
        echo "\n[" . date('Y-m-d h:i:s') . "] INFO Esperando antes de iniciar ($this->consume_timeout ms)...";

        usleep($this->consume_timeout * 1000);
        

        echo "\n[" . date('Y-m-d h:i:s') . "] INFO Waiting for partition assignment... (make take some time when\n";
        echo "\n[" . date('Y-m-d h:i:s') . "] INFO quickly re-joining the group after leaving it.)\n";
        
        while (true) {
            echo "[" . date('Y-m-d h:i:s') . "] INFO Esperando mensagem ($this->consume_timeout ms)...";
            $message = $this->consumer->consume($this->consume_timeout);
            switch ($message->err) {
                case RD_KAFKA_RESP_ERR_NO_ERROR:
        
                    echo "\n[" . date('Y-m-d h:i:s') . "] INFO =======================";
                    echo "\n[" . date('Y-m-d h:i:s') . "] INFO Message:\n";
                    var_dump($message);
                    echo "\n[" . date('Y-m-d h:i:s') . "] INFO =======================\n";
                    $this->hookTrataNovoJson($topic, $message->payload);
                    echo "\n[" . date('Y-m-d h:i:s') . "] INFO =======================\n";
        
                    break;
                case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                    echo "\n[" . date('Y-m-d h:i:s') . "] INFO No more messages; will wait for more\n";
                    break;
                case RD_KAFKA_RESP_ERR__TIMED_OUT:
                    echo "\n[" . date('Y-m-d h:i:s') . "] INFO Timed out\n";
                    break;
                default:
                    throw new \Exception($message->errstr(), $message->err);
                    break;
            }
        }

    }


  


}