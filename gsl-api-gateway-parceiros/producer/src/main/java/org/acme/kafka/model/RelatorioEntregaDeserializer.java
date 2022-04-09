package org.acme.kafka.model;

import io.quarkus.kafka.client.serialization.ObjectMapperDeserializer;

public class RelatorioEntregaDeserializer extends ObjectMapperDeserializer<RelatorioEntrega> {
    public RelatorioEntregaDeserializer() {
        super(RelatorioEntrega.class);
    }
    
}
