
CREATE DATABASE IF NOT EXISTS sge_entregas_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE USER IF NOT EXISTS 'rest_api_user'@'localhost' identified by 'rest_api_password';
GRANT ALL on blog.* to 'rest_api_user'@'localhost';

use sge_entregas_db;


DROP TABLE IF EXISTS `entrega`;

CREATE TABLE IF NOT EXISTS `entrega`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cte` varchar(44) NOT NULL,
  `valor` decimal(15,2) NOT NULL DEFAULT 0,
  `destinatario` varchar(255),
  `endereco` text NOT NULL,
  `endereco_latitude` int NOT NULL DEFAULT 0,
  `endereco_longitude` int NOT NULL DEFAULT 0,
  `descricao` varchar(255) NOT NULL,
  `status` int NOT NULL DEFAULT 0,
  `observacoes` text NOT NULL,
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_limite_entrega` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_realizacao_entrega` datetime NULL,
  PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `relatorio_entrega`;

CREATE TABLE IF NOT EXISTS `relatorio_entrega` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `id_entrega` int NOT NULL,
  `status` int NOT NULL DEFAULT 0,
  `latitude` int NOT NULL DEFAULT 0,
  `longitude` int NOT NULL DEFAULT 0,
  `descricao` varchar(255) NOT NULL,
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data` datetime NULL,
  PRIMARY KEY (`id`),
  INDEX indice_entrega (id_entrega),
    FOREIGN KEY (id_entrega)
        REFERENCES entrega(id)
        ON DELETE CASCADE
);


