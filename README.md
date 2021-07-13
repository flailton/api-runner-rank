<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Runner Rank (API REST)

Este projeto foi desenvolvido com [Laravel Framework](https://laravel.com) versão 8.34.0 e [MySQL Server](https://www.mysql.com/) versão 5.7.

## Sobre Runner Rank

O Runner Rank é uma API para gerenciamento de corredores, provas e resultados. Também capaz de fornecer a listagem de classificação Geral ou por Idade(18-25 anos, 25-35 anos, 35-45 anos, 45-55 anos e acima de 55 anos).

## Instalação utilizando Docker

#### Requisitos

- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/)
- [Git](https://git-scm.com/downloads)

Para instalar o projeto execute **todos os passos** abaixo, nesta ordem.

Clone o repositório [API Runner Rank](https://github.com/flailton/api-runner-rank) do GitHub:

```bash
git clone https://github.com/flailton/api-runner-rank && cd api-runner-rank
```

Faça o build das imagens Docker utilizadas no projeto e inicie os containers da aplicação:

```bash
docker-compose up -d --build
```

Execute o comando para instalar as dependências do projeto:

```bash
docker-compose exec php composer install
```

Execute o comando para gerar a chave da aplicação:

```bash
docker-compose exec app php artisan key:generate
```

Execute o comando para executar as migrations do Laravel e montar a estrutura do banco de dados:

```bash
docker-compose exec app php artisan migrate
```

Execute o comando para executar as seeds do Laravel e popular as tabelas do banco de dados:

```bash
docker-compose exec app php artisan db:seed
```

## Serviços

Após realizada finalizada a instalação, o ambiente ficará acessível através do endereço `http://localhost:8000/`.

| Serviço | Request (URL) | Request (Data) |
|---------|-----------|------------|
| Incluir Corredor | [POST] http://localhost:8000/api/corredores | nome:`Nome`<br>cpf:`CPF`<br>data_nascimento:`Dada de nascimento (dd/mm/YYY / dd-mm-YYY / YYYY-mm-dd)` |
| Incluir Prova | [POST] http://localhost:8000/api/provas | tipo_prova_id:`Tipo de Prova`<br>data_prova:`Dada da prova (dd/mm/YYY / dd-mm-YYY / YYYY-mm-dd)` |
| Incluir Corredor em Prova | [POST] http://localhost:8000/api/inscricao | prova_id:`ID Corredor`<br>corredor_id:`ID Prova` |
| Incluir Resultado do Corredor | [POST] http://localhost:8000/api/resultados | prova_id:`ID Corredor`<br>corredor_id:`ID Prova`<br>tempo_inicio_prova:`Tempo de início (H:i:s)`<br>tempo_fim_prova:`Tempo de conclusão (H:i:s)` |
| Listagem de Classificação (Idade) | [GET] http://localhost:8000/api/classificacao_idade | - |
| Listagem de Classificação (Geral) | [GET] http://localhost:8000/api/classificacao_geral | - |
| Listagem de Tipos de Prova | [GET] http://localhost/api/tipo_provas | - |

