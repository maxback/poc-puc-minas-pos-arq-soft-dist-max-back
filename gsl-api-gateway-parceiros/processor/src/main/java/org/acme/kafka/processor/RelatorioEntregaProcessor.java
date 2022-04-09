package org.acme.kafka.processor;

import java.util.Random;

import javax.enterprise.context.ApplicationScoped;

import org.acme.kafka.model.Quote;
import org.acme.kafka.model.RelatorioEntrega;
import org.acme.kafka.model.StatusEntrega;
import org.eclipse.microprofile.reactive.messaging.Incoming;
import org.eclipse.microprofile.reactive.messaging.Outgoing;

import io.smallrye.reactive.messaging.annotations.Blocking;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

public class RelatorioEntregaProcessor {

    private Logger logger = LoggerFactory.getLogger(getClass());


    /*
    * Recebe da stream relatorio-entrega criada a partir do tópico relatorio-entrega-recebido
    * e salvar no banco de Dados (aqui apenas memória)
    */
    @Incoming("relatorio-entrega")
    @Blocking
    public void process(RelatorioEntrega relatorioEntrega) throws InterruptedException {
        logger.info("@@@@@@@@@@@@@@@@@@@ Tratando canal relatorio-entrega @@@@@@@@@@@@@@@@@@@@@@@");
        Thread.sleep(2000);
        logger.info("teria salvo no banco agora !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!");
    }
    
}
