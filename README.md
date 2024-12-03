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

2 - Executar as migrations do MySQL/Doctrine
```bash
php bin/console doctrine:migrations:migrate -n
```

3 - Executar as fixtures (dados falsos para testes) do banco de dados
```bash
php bin/console doctrine:fixtures:load -n
```

4 - Gerar as chaves de autenticação
```bash
php bin/console lexik:jwt:generate-keypair
```

### Uso

Depois que tudo estiver configurado e as dependências instaladas, você pode acessar sua aplicação Symfony em [http://localhost:8080](http://localhost:8080).

A documentação com os endpoints se encontra em <http://localhost:8080/docs/index.html>

#### Usuário padrão
Há alguns usuarios que você pode utilizar para fins de teste:

<table>
<tr>
<th>email</th>
<th>senha</th>
</tr>
<tr>
<td>chiquim@example.com</td>
<td>123456</td>
</tr>
<tr>
<td>maria@example.com</td>
<td>1q2w3e</td>
</tr>
<tr>
<td>zezim@example.com</td>
<td>112233</td>
</tr>
</table>

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
    S <--> V{Validator}
    S <--valid?--> RP[Repository]
    RP <==ORM/Doctrine==> D[(Database)]
    E((Entity)) <--schema--> RP
    CA --JsonResponse--> HC

```

</details>

