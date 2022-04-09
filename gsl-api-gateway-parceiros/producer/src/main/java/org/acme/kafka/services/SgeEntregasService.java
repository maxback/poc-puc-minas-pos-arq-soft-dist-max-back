package org.acme.kafka.services;


import org.eclipse.microprofile.rest.client.inject.RegisterRestClient;

import javax.enterprise.context.ApplicationScoped;
import javax.ws.rs.GET;
import javax.ws.rs.Path;
import javax.ws.rs.PathParam;
import javax.ws.rs.Produces;
import javax.ws.rs.core.Response;

@Path("/entregas")
@RegisterRestClient
@ApplicationScoped
public interface SgeEntregasService {

    @GET
    @Path("/{idEntrega}/relatorio/{id}")
	@Produces("application/json")
	public Response buscarRegistrosRelatorioEntrega(@PathParam("idEntrega") String idEntrega,
        @PathParam("id") String id);

    @GET
    @Path("/{idEntrega}/relatorio")
	@Produces("application/json")
    public Response buscarTodosRegistrosRelatorioEntrega(@PathParam("idEntrega") String idEntrega);
    

}
