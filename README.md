# Clube do Vinho

API para gerenciamento de uma loja virtual de vinhos

## Tecnologias

- **PHP** 8.3
- **PostGres** 16
- **Symfony** 7.2 

## Instalação 
<details>
<summary>Passo a passo</summary>

### Clonar o Repositório

Primeiro, clone o repositório usando SSH ou HTTPS:

```bash
git clone git@github.com:alessandrofeitoza/clubedovinho.git
```
ou
```bash
git clone https://github.com/alessandrofeitoza/clubedovinho.git
```

### Navegar para o Diretório do Projeto
Mude para o diretório do projeto:

```bash
cd clubedovinho
```

---
>
> O jeito mais fácil é rodar o comando `make setup`, isso já vai executar todos os passos necessários e deixar a aplicação rodando em <http://localhost:8080>
>
Mas se preferir, pode fazer o passo a passo abaixo

---

### Iniciar o Docker com seus contêineres
Precisar ter o `docker compose` instalado/configurado:
```bash
docker compose up -d
```

### Instalar Dependências (Composer)
Antes de mais nada entre no contêiner PHP:
```bash
docker compose exec -it php bash
```
**Agora é necessário executar outros passos, sequencialmente:**

1 - Instalação das dependências do PHP:
```bash
composer install
```

2 - Executar as migrations do PostGres/Doctrine
```bash
php bin/console doctrine:migrations:migrate -n
```

3 - Executar as fixtures (dados falsos para testes) do banco de dados
```bash
php bin/console doctrine:fixtures:load -n
```


### Uso

Depois que tudo estiver configurado e as dependências instaladas, você pode acessar sua aplicação Symfony em [http://localhost:8080](http://localhost:8080).

A documentação com os endpoints se encontra em <http://localhost:8080/docs/index.html>

<img src="./public/docs/assets/img/docs.gif" alt="Docs">

</details>


## Desenvolvimento
<details>
<summary>Arquitetura e Decisões técnicas</summary>

Estamos utilizando o Symfony e o seu ecossistma de bibliotecas, porém a arquitetura é baseada em camadas e trata-se de um monolítico com a metodologia API First

```mermaid
flowchart TD
    HC((HttpClient)) --JsonRequest<--> R[Routes]
    R --> CA[[ControllerApi]]
    CA <--> S[Service]
    RP <==ORM/Doctrine==> D[(Database)]
    S <--> RP[Repository]
    CA --> ES
    ES(EventSubscriber) --JsonResponse--> HC
    RP <--schema--> E((Entity))

```


#### Logs
Estamos salvando os logs de cada persistencia na base, optamos por fazer esse controle através da camada `Service`, como mostra a figura a seguir:

```mermaid
flowchart TD
    Controller --> Service
    Service ==> A([AuditLogger])
    A ==> f{{/var/log/audit.log}}
    Service <--> Repository
    Repository <--> D[(Database)]

```

#### Response Headers
Através de uma camada de `EventSubscriber` estamos adicionando um custom header em cada Response

<table>
<tr>
<th colspan="2">HEADERS</th>
</tr>
<tr>
<td>X-REQUEST-INFO</td>
<td>2</td>
</tr>
</table>

</details>

