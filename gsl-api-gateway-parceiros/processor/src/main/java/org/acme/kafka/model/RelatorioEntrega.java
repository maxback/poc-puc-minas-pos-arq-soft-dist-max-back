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
    public Date data;
    
    public RelatorioEntrega(String uuid, long id_entrega, StatusEntrega status, String descricao, double latitude, double longitude) {
        this.id = 0; //será definido ao salvar no bnaco de ados
        this.uuid = uuid; //para pesquisar depois no banco
        this.id_entrega = id_entrega;
        this.status = status;
        this.latitude = latitude;
        this.longitude = longitude;
        this.descricao = descricao;

        this.data = new Date(System.currentTimeMillis());
    }

    public RelatorioEntrega() {

        this.data = new Date(System.currentTimeMillis());
    }


    @Override
    public String toString() {
        return "RelatorioEntrega{data=" + data + ", descricao=" + descricao + ", uuid=" + uuid + ", id_entrega="
                + id_entrega + ", latitude=" + latitude + ", longitude=" + longitude + ", status=" + status + "}";
    }



    
    
}
