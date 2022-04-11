package com.github.maxback.flink.relatoriodeslocveiculos;

import org.apache.flink.table.api.EnvironmentSettings;
import org.apache.flink.table.api.Table;
import org.apache.flink.table.api.TableEnvironment;
import org.apache.flink.table.api.Tumble;
import org.apache.flink.table.expressions.TimeIntervalUnit;

import static org.apache.flink.table.api.Expressions.*;


public class RelatorioDeslocVeiculos {

    public static Table report(Table relatorioEntregaRecebido) {

        //maxback = https://nightlies.apache.org/flink/flink-docs-release-1.14/docs/try-flink/table_api/
        
        return relatorioEntregaRecebido.select(
            $("id_entrega"),
            $("data").floor(TimeIntervalUnit.HOUR).as("log_ts"),
            $("status"))
        .groupBy($("id_entrega"), $("status"), $("log_ts"))
        .select(
            $("id_entrega"),
            $("status"),
            $("log_ts"),
            $("status").count().as("contador"));
        
            
        //funcao personalizada
        /*
        return relatorioEntregaRecebido.select(
            $("id_entrega"),
            call(MyFloor.class, $("data")).as("log_ts"),
            $("status"))
        .groupBy($("id_entrega"), $("log_ts"))
        .select(
            $("id_entrega"),
            $("log_ts"),
            $("status").sum().as("status"));
        */
        
        //janela de tempo
        /*
        return relatorioEntregaRecebido
        .window(Tumble.over(lit(1).hour()).on($("data")).as("log_ts"))
        .groupBy($("id_entrega"), $("log_ts"))
        .select(
            $("id_entrega"),
            $("log_ts").start().as("log_ts"),
            $("status").sum().as("status"));        
        */
    }

    public static void main(String[] args) throws Exception {
        EnvironmentSettings settings = EnvironmentSettings.newInstance().build();
        TableEnvironment tEnv = TableEnvironment.create(settings);

        tEnv.executeSql("CREATE TABLE relatorio_entrega_recebido (\n" +
                "    id_entrega  BIGINT,\n" +
                "    status      VARCHAR(256),\n" +
                //java java.time.LocalDateTime -> TIMESTAMP
                //ver https://nightlies.apache.org/flink/flink-docs-master/docs/dev/table/types/#list-of-data-types
                "    data TIMESTAMP(3),\n" +
                "    WATERMARK FOR data AS data - INTERVAL '5' SECOND\n" +
                ") WITH (\n" +
                "    'connector' = 'kafka',\n" +
                "    'topic'     = 'relatorio-entrega-recebido-notificacao',\n" +
                "    'properties.bootstrap.servers' = 'gsl-mensageria-kafka:9092',\n" +
                "    'format'    = 'json'\n" +
                ")");

        tEnv.executeSql("CREATE TABLE relatorio_desloc_veiculos (\n" +
                "    id_entrega BIGINT,\n" +
                "    status     VARCHAR(256)\n," + 
                "    log_ts     TIMESTAMP(3),\n" +
                "    contador     BIGINT\n," +
                "    PRIMARY KEY (id_entrega, status, log_ts) NOT ENFORCED" +
                ") WITH (\n" +
                "  'connector'  = 'jdbc',\n" +
                "  'url'        = 'jdbc:mysql://gsl-dados-consolidados-db:3306/gsl_dados_consolidados_db',\n" +
                "  'table-name' = 'relatorio_desloc_veiculos',\n" +
                "  'driver'     = 'com.mysql.jdbc.Driver',\n" +
                "  'username'   = 'root',\n" +
                "  'password'   = '12345'\n" +
                ")");

        Table relatorioEntregaRecebido = tEnv.from("relatorio_entrega_recebido");
        report(relatorioEntregaRecebido).executeInsert("relatorio_desloc_veiculos");
    }
}
