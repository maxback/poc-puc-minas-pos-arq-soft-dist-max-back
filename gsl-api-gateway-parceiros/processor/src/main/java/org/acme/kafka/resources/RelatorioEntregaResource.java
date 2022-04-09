package org.acme.kafka.resources;

import java.util.UUID;

import javax.ws.rs.GET;
import javax.ws.rs.POST;
import javax.ws.rs.Path;
import javax.ws.rs.PathParam;
import javax.ws.rs.core.Response;

import org.acme.kafka.model.RelatorioEntrega;
import org.acme.kafka.model.StatusEntrega;
import org.eclipse.microprofile.reactive.messaging.Channel;
import org.eclipse.microprofile.reactive.messaging.Emitter;

import javax.inject.Inject;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;


@Path("/entrega")
public class RelatorioEntregaResource {

    private Logger logger = LoggerFactory.getLogger(getClass());


    @GET
    @Path("/{idEntrega}/relatorio/{id}")
    public Response buscarRegistrosRelatorioEntrega(@PathParam("idEntrega") long idEntrega,
        @PathParam("id") String id) {

        logger.info("********************************** GET reg rel entrega idEntrega = [{}] e id = [{}] **********************************",
            idEntrega, id);

          
        return Response.status(200)
            .entity(new RelatorioEntrega(id, idEntrega, StatusEntrega.PENDENTE, "", 0, 0))
            .build();    
        
    }

}
