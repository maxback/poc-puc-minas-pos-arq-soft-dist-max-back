<?php

require 'ConsumerKafkaBase.php';

//class ConsumerKafkaBase { };

class ConsumerKafkaRelatorioEntrega extends ConsumerKafkaBase 
{

    //fazer overrride na classe filha
    protected function hookTrataNovoJson($topic, $JsonStr) {
        echo "############# Tratando tópico [$topic] #########################";
    }

};


$onsumer = new ConsumerKafkaRelatorioEntrega();
$onsumer->consumirTopico("relatorio-entrega-recebido");

?>
