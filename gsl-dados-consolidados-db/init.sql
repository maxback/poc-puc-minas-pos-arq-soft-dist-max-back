
CREATE DATABASE IF NOT EXISTS gsl_dados_consolidados_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE USER IF NOT EXISTS 'apache_flink_user'@'localhost' identified by 'apache_flink_password';
GRANT ALL on blog.* to 'apache_flink_user'@'localhost';

use gsl_dados_consolidados_db;


CREATE TABLE IF NOT EXISTS relatorio_desloc_veiculos (
	id_entrega BIGINT NOT NULL,
	status     BIGINT NOT NULL,
	log_ts     TIMESTAMP(3) NOT NULL,
	contador	   BIGINT NOT NULL,
	PRIMARY KEY (id_entrega, status, log_ts)
);




