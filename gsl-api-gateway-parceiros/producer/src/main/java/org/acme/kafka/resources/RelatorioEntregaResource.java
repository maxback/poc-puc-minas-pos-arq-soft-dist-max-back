package org.acme.kafka.resources;

import java.util.UUID;

import javax.ws.rs.GET;
import javax.ws.rs.POST;
import javax.ws.rs.Path;
import javax.ws.rs.PathParam;
import javax.ws.rs.core.Response;
import javax.ws.rs.Consumes;
import javax.ws.rs.Produces;

import org.acme.kafka.model.RelatorioEntrega;
import org.acme.kafka.services.SgeEntregasService;
import org.eclipse.microprofile.reactive.messaging.Channel;
import org.eclipse.microprofile.reactive.messaging.Emitter;

import javax.inject.Inject;
import org.eclipse.microprofile.rest.client.inject.RestClient;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;


@Path("/entrega")
public class RelatorioEntregaResource {

    private Logger logger = LoggerFactory.getLogger(getClass());

    @Channel("relatorio-entrega-recebido")
    Emitter<RelatorioEntrega> relatorioEntregaEmitter;

    @Inject
    @RestClient
    SgeEntregasService sgeEntregasService;

    /**
     * Endpoint que gera um relatório de entrega informando o status atual da entrega {idEntrega}
     * e enviando apra um topica kafka (relatorio-entrega-recebido).
     */
    @POST
    @Path("/{idEntrega}/relatorio")
    @Consumes("application/json")
    public Response criarNovoRegistrosRelatorioEntrega(@PathParam("idEntrega") long idEntrega,
        RelatorioEntrega relatorioEntrega) {

        logger.info("********************************** POST reg rel entrega idEntrega = [{}] e registro = [{}] **********************************",
            idEntrega, relatorioEntrega);

        UUID uuidRelatorio = UUID.randomUUID();

        if(relatorioEntrega.id_entrega != idEntrega) {
            logger.info("id_entrega diferente do id passado no endpoint.");
            return Response.status(400).build();
        }

        relatorioEntrega.uuid = uuidRelatorio.toString();

        logger.info("Enviando solicitação via mensageria para o serviço sge-entrega", relatorioEntrega);
        logger.info("[{}]", relatorioEntrega);

        relatorioEntregaEmitter.send(relatorioEntrega);

        return Response.status(200).entity(relatorioEntrega).build();
    }

    private void logResponse(String contexto, Response response) {
        
        String s = contexto + " = [" + response.toString() + "]";

        if (response.hasEntity()) {
            try {
                response.bufferEntity();
                s = contexto + " = [" + response.readEntity(String.class) + "]";
                
            } catch (Exception e) {
                s = s + " - Erro convertendo resposta: " + e.toString();
            }    
        }

        logger.info(s);
    }

    @GET
    @Path("/{idEntrega}/relatorio/{id}")
    @Produces("application/json")
    public Response buscarRegistrosRelatorioEntrega(@PathParam("idEntrega") String idEntrega,
        @PathParam("id") String id) {

        logger.info("********************************** GET reg rel entrega idEntrega = [{}] e id = [{}] **********************************",
            idEntrega, id);

        try {
            Response response = sgeEntregasService.buscarRegistrosRelatorioEntrega(idEntrega, id);
            logResponse("Resposta do serviço sge-entrega", response);
            return response;

        } catch (Exception e) {
            logger.warn("*************** RETORNARÁ 502 Bad Gateway, DEVIDO A Erro chamando service SGE REST: [{}]", e);
            return Response.status(502).entity(e).build();
            
        }
        
    }

    @GET
    @Path("/{idEntrega}/relatorio")
    @Produces("application/json")
    public Response buscarTodosRegistrosRelatorioEntrega(@PathParam("idEntrega") String idEntrega) {

        logger.info("********************************** GET TODOS reg rel entrega idEntrega = [{}]  **********************************",
            idEntrega);

        try {
            Response response = sgeEntregasService.buscarTodosRegistrosRelatorioEntrega(idEntrega);
            logResponse("Resposta do serviço sge-entrega", response);
            return response;
        } catch (Exception e) {
            logger.warn("*************** RETORNARÁ 502 Bad Gateway, DEVIDO A Erro chamando service SGE REST: [{}]", e);
            return Response.status(502).entity(e).build();
            
        }
        
    }

}
