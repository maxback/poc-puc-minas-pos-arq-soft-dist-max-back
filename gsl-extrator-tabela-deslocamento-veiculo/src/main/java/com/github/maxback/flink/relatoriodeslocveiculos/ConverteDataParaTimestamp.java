package com.github.maxback.flink.relatoriodeslocveiculos;

import java.time.LocalDateTime;
import java.time.ZonedDateTime;
import java.time.temporal.ChronoUnit;

import org.apache.flink.table.annotation.DataTypeHint;
import org.apache.flink.table.functions.ScalarFunction;

public class ConverteDataParaTimestamp extends ScalarFunction {

    public @DataTypeHint("TIMESTAMP(3)") LocalDateTime eval(
        @DataTypeHint("VARCHAR") String dateTimeInString) {

        ZonedDateTime zdt = ZonedDateTime.parse(dateTimeInString);    
        
        return zdt.toLocalDateTime();
    }
}