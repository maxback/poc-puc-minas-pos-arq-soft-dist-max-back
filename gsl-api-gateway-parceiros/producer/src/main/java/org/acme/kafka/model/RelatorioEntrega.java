package org.acme.kafka.model;

import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.TimeZone;

public class RelatorioEntrega {

    public long id;
    public String uuid;
    public long id_entrega;
    public StatusEntrega status;
    public double latitude;
    public double longitude;
    public String descricao;
    public String data; //no formato It conforms to ISO 8601 (2012-04-23T18:25:43.511Z)
    
    public RelatorioEntrega(String uuid, long id_entrega, StatusEntrega status, String descricao, double latitude, double longitude) {
        this.id = 0; //será definido ao salvar no bnaco de ados
        this.uuid = uuid; //para pesquisar depois no banco
        this.id_entrega = id_entrega;
        this.status = status;
        this.latitude = latitude;
        this.longitude = longitude;
        this.descricao = descricao;

        Date date = new Date(System.currentTimeMillis());

        SimpleDateFormat sdf;
        sdf = new SimpleDateFormat("yyyy-MM-dd'T'HH:mm:ss.SSSXXX");
        //sdf.setTimeZone(TimeZone.getTimeZone("CET"));
        this.data = sdf.format(date);
        
    }

    public RelatorioEntrega() {
    }

    @Override
    public String toString() {
        return "RelatorioEntrega{data=" + data + ", descricao=" + descricao + ", uuid=" + uuid + ", id_entrega="
                + id_entrega + ", latitude=" + latitude + ", longitude=" + longitude + ", status=" + status + "}";
    }



    
    
}
