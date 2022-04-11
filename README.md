# poc-puc-minas-pos-arq-soft-dist-max-back
Arquivos da prova de Conceito para TCC - Especialização em Arquitetura de Software Distribuído


## Passos:

1) O projeto em java dev compilar e remover a imagem antes de abrir o docker compose:

~/tcc/poc/poc-puc-minas-pos-arq-soft-dist-max-back$ mvn clean package -Dmaven.test.skip

docker rm ffaf396f947b
docker rmi 70b20ea5fd93

2) 
- Os fontes java deve ir na pasta de mandar compilar com o maven (processor é apenas um teste agora, mas está compilando):

cd gsl-api-gateway-parceiros/

mvn clean package -Dmaven.test.skip


- Os fontes em PHP são carregados de fora no volume e assim aplica-se em tempo real.
Porém se mudou o arquivo sge-entregas/Dockerfile então será necessário realizar um build :

docker build -f ./sge-entregas/Dockerfile .

ou
/home/mback/tcc/poc/poc-puc-minas-pos-arq-soft-dist-max-back/sge-entregas# docker build .

3) Para compilar o extrator de dados (flick)

cd gsl-extrator-tabela-deslocamento-veiculo

docker build .


4) é só rodar, filtrando o log se desejar

/home/mback/tcc/poc/poc-puc-minas-pos-arq-soft-dist-max-back# docker-compose up -d

docker-compose up -d
docker-compose logs -f | grep -E "(quarkus|service-php)"


Extra: Execução manual do consumer php:
docker exec 94a1a6a272e8 php /var/www/html/public/mensageria/consumer.php



## Monitorar:

docker-compose up -d
docker-compose logs -f | grep -E "(integracao|php|relatorio-entrega-)"

docker-compose logs -f | grep -E "ERROR|WARN|Erro|erro)"


Extrator de dados (apache flink):
console web (he Flink console):
http://localhost:8082/

Grafana:
http://localhost:3000/d/FOe0PbmGk/walkthrough?viewPanel=2&orgId=1&refresh=5s

Dados na base consolidada de dados:
Explore the results from inside MySQL.

//docker-compose exec gsl-dados-consolidados-db mysql -Dgsl_dados_consolidados_db -uapache_flink_user -papache_flink_password

docker-compose exec gsl-dados-consolidados-db mysql -Dgsl_dados_consolidados_db -uroot -p12345

use gsl_dados_consolidados_db;


select count(*) from relatorio_desloc_veiculos;

select * from relatorio_desloc_veiculos;


docker-compose logs jobmanager | grep ERROR -B 5 -A 5

docker-compose logs -f | grep -E "(relatorio-entrega-|ERROR)" -B 3 -A 15



## Consultas no grafana

SELECT 
  now() as time,
  CAST(id_entrega AS CHAR(50)) as metric,
  sum(contador) as value
FROM 
  relatorio_desloc_veiculos
GROUP BY
  id_entrega

