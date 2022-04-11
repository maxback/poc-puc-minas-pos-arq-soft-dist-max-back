package org.acme.kafka.model;

import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.TimeZone;
import java.time.LocalDateTime;
import java.sql.Timestamp;

import java.time.format.DateTimeFormatter;

public class RelatorioEntrega {

    public long id;
    public String uuid;
    public long id_entrega;
    public StatusEntrega status;
    public double latitude;
    public double longitude;
    public String descricao;
    //public LocalDateTime data;
    //public Timestamp data;
    public String data;

    public void AtualizarCampoData() {
        //exemplo: 2022-04-11 04:49:32.127
        DateTimeFormatter dtf = DateTimeFormatter.ofPattern("yyyy-MM-dd HH:mm:ss.nnn");  
        LocalDateTime now = LocalDateTime.now();  
        this.data = dtf.format(now);
    }
    
    public RelatorioEntrega(String uuid, long id_entrega, StatusEntrega status, String descricao, double latitude, double longitude) {
        this.id = 0; //será definido ao salvar no bnaco de ados
        this.uuid = uuid; //para pesquisar depois no banco
        this.id_entrega = id_entrega;
        this.status = status;
        this.latitude = latitude;
        this.longitude = longitude;
        this.descricao = descricao;

        AtualizarCampoData();

    }

    public RelatorioEntrega() {
        AtualizarCampoData();
    }

    @Override
    public String toString() {
        return "RelatorioEntrega{data=" + data + ", descricao=" + descricao + ", uuid=" + uuid + ", id_entrega="
                + id_entrega + ", latitude=" + latitude + ", longitude=" + longitude + ", status=" + status + "}";
    }



    
    
}
