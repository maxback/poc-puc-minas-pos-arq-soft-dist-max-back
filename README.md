# poc-puc-minas-pos-arq-soft-dist-max-back
Arquivos da prova de Conceito para TCC - Especialização em Arquitetura de Software Distribuído


## Passos:

1) O projeto em java dev compilar e remover a iamgem antes de abrir o docker compose:

~/tcc/poc/poc-puc-minas-pos-arq-soft-dist-max-back$ mvn clean package -Dmaven.test.skip

docker rm ffaf396f947b
docker rmi 70b20ea5fd93

2) Os fontes em PHP são carregados de fora no volume e assim aplica-se em tempo real.
Porém se mudou o arquivo sge-entregas/Dockerfile então será necessário realizar um build :

docker build -f ./sge-entregas/Dockerfile .

ou
/home/mback/tcc/poc/poc-puc-minas-pos-arq-soft-dist-max-back/sge-entregas# docker build .


3) é só rodar, filtrando o log se desejar

/home/mback/tcc/poc/poc-puc-minas-pos-arq-soft-dist-max-back# docker-compose up

docker-compose up | grep -E "(quarkus|service-php)"

4) Execução manual do consumer php:
docker exec 94a1a6a272e8 php /var/www/html/public/mensageria/consumer.php



## Monitorar:

docker-compose up | grep -E "(integracao|php|relatorio-entrega-recebido)"

