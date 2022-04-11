package com.github.maxback.flink.relatoriodeslocveiculos;

import java.time.LocalDateTime;
import java.time.ZonedDateTime;
import java.time.temporal.ChronoUnit;

import org.apache.flink.table.annotation.DataTypeHint;
import org.apache.flink.table.functions.ScalarFunction;

public class ConverteStatusStrParaInt extends ScalarFunction {

    public @DataTypeHint("BIGINT") int eval(
        @DataTypeHint("VARCHAR") String statusStr) {

        if(statusStr.equals("PENDENTE")) return 1;
        if(statusStr.equals("PROGRAMADA")) return 2;
        if(statusStr.equals("EM_TRANSIDO")) return 3;
        if(statusStr.equals("REALIZADA")) return 4;
        if(statusStr.equals("CANCELADA")) return 5;
        
        try {
            int ret = Integer.parseInt(statusStr);
            return ret;
        } catch (Exception e) {
            return 0;
        }
        
    }
}