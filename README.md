# Testello

## Menu

- [Descrição](#descrição)
- [Tecnologias Utilizadas](#tecnologias-utilizadas)
- [Requisitos do Sistema](#requisitos-do-sistema)
- [Configuração do Ambiente](#configuração-do-ambiente)
- [Estrutura do Banco de Dados](#estrutura-do-banco-de-dados)
- [Importação de CSV](#importação-de-csv)
- [Rotas](#rotas)

## Descrição

<details>
<summary>Descrição</summary>
Solução para Importação de Tabelas de Frete para a Testello, uma transportadora que presta serviços para múltiplos clientes. A aplicação permite a importação eficiente de arquivos CSV contendo tabelas de frete de um ou mais clientes, suportando um grande volume de registros (até 300 mil linhas) sem causar timeout HTTP.
</details>

## Tecnologias Utilizadas

<details>
<summary>Tecnologias Utilizadas</summary>

- **Back-end**: PHP 8.2, Laravel
- **Banco de Dados**: MySQL

</details>

## Requisitos do Sistema

<details>
<summary>Requisitos do Sistema</summary>

- PHP 8.2+
- Node.js 18+
- MySQL 5.7+
- Composer
- Docker
- Configurações para utilizar o Laravel Sail, que a depender do sistema operacional utilizado, pode ser diferente. As configurações necessárias para rodar o Sail podem ser conferidas na documentação oficial:
- https://laravel.com/docs/11.x/sail#installation

</details>

## Configuração do Ambiente

<details>
<summary>Configuração do Ambiente</summary>

#### Clonar o Repositório

```bash
git clone https://github.com/albuquerque-lucas/testello.git
cd testello
```

#### Instalar dependências do Back-End

```bash
composer install
```

#### Configurar o Arquivo `.env`

Adicione um arquivo `.env` na raiz do projeto com os mesmos dados do arquivo .env.example, trocando a parte com o seguinte conteúdo:

```env
DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=
```


por esta:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password
```

#### Subir os contêineres

```bash
vendor/bin/sail up
```

#### Rodar as Migrations

Se os contêineres estiverem funcionando corretamente, você pode rodar as migrations:

```bash
vendor/bin/sail artisan migrate --seed
```

O comando acima irá rodar as migrações e os seeders do banco de dados, populando as tabelas branches e customers.


#### Testes
O arquivo de ambiente de teste vem previamente configurado. Também deverá ser rodada a migration para ele:

```bash
vendor/bin/sail artisan migrate --env=testing
```

Para rodar os testes, chamar o comando:
```bash
vendor/bin/sail artisan test
```

</details>

## Estrutura do Banco de Dados

<details>
<summary>Estrutura do Banco de Dados</summary>

### Tabelas Necessárias

A estrutura do banco de dados para a aplicação Testello inclui três tabelas principais: `customers`, `branches` e `freight_tables`. Abaixo está a descrição detalhada de cada uma dessas tabelas.

#### Tabela `customers`

Armazena informações sobre os clientes que utilizam os serviços da Testello.

- `id`: Identificador único do cliente.
- `name`: Nome do cliente.
- `timestamps`: Marcas de tempo de criação e atualização do registro.

#### Tabela `branches`

Armazena informações sobre as filiais da Testello.

- `id`: Identificador único da filial.
- `name`: Nome da filial.
- `location`: Localização da filial.
- `timestamps`: Marcas de tempo de criação e atualização do registro.

#### Tabela `freight_tables`

Armazena as tabelas de frete para cada cliente, contendo informações detalhadas sobre as tarifas de frete. Vale notar que a coluna branch_id é adicionada em uma migration posterior à migration principal.

- `id`: Identificador único da tabela de frete.
- `customer_id`: Chave estrangeira que referencia o cliente ao qual a tabela de frete pertence.
- `branch_id`: Chave estrangeira que referencia uma filial.
- `from_postcode`: Código postal de origem.
- `to_postcode`: Código postal de destino.
- `from_weight`: Peso inicial da faixa de frete.
- `to_weight`: Peso final da faixa de frete.
- `cost`: Custo do frete para a faixa de peso especificada.
- `timestamps`: Marcas de tempo de criação e atualização do registro.

</details>

## Importação de CSV

<details>
<summary>Importação de CSV</summary>

A importação de arquivos CSV na aplicação Testello é realizada através de uma rota específica que aceita arquivos CSV enviados pelos usuários. Este processo é gerido pela rota `POST /upload-freight-csv`, que aciona o método `uploadCSV` no `FreightTableController`.

### Processo de Importação

1. **Recebimento dos Arquivos CSV**:
   - O usuário seleciona e envia um ou mais arquivos CSV através da rota mencionada.
   - O método `uploadCSV` armazena temporariamente esses arquivos no servidor.

2. **Armazenamento Temporário**:
   - Cada arquivo CSV é salvo em uma pasta temporária.
   - Os caminhos dos arquivos são armazenados em um array para processamento posterior.

3. **Desencadeamento de um Job na Fila**:
   - Após o armazenamento temporário, um job chamado `ProcessFreightTableCsv` é colocado na fila de processamento.
   - Este job é responsável por processar os arquivos CSV de forma assíncrona, garantindo que o processo de importação não cause timeout no HTTP.

4. **Processamento dos Arquivos CSV**:
   - O job `ProcessFreightTableCsv` lê cada arquivo CSV e converte os dados para um formato apropriado.
   - Os registros são processados em chunks de 1000 linhas para otimizar a inserção no banco de dados e evitar sobrecarga.

5. **Inserção no Banco de Dados**:
   - Cada chunk de dados processado é inserido na tabela `freight_tables` do banco de dados.
   - A conversão de valores decimais é realizada para assegurar a precisão dos dados de frete.

### Evitando Timeout HTTP

Para evitar timeout HTTP durante a importação de grandes volumes de dados, o processo é realizado de forma assíncrona utilizando jobs na fila. Isso significa que, após o upload dos arquivos CSV, o usuário recebe uma resposta imediata confirmando que os arquivos estão sendo processados. O job `ProcessFreightTableCsv` cuida do processamento real dos dados, permitindo que a aplicação permaneça responsiva.

### Rodando Jobs em Ambiente de Desenvolvimento ou Produção

Para que os jobs sejam executados em um ambiente de desenvolvimento ou produção, é necessário rodar o seguinte comando:

```bash
vendor/bin/sail artisan queue:work
```

Em um ambiente de produção, pode ser necessário configurar Cron Jobs ou, dependendo do tipo de hospedagem, utilizar o Supervisor para manter o worker rodando continuamente. Isso garante que os jobs na fila sejam processados de maneira confiável e oportuna.

</details>

## Rotas

<details>
<summary>Rotas</summary>

A aplicação Testello possui as seguintes rotas disponíveis para interagir com os dados de frete, clientes e filiais:

- **Rota para Upload de CSV**:
  - `POST /upload-freight-csv`
    - Controlador: `FreightTableController@uploadCSV`
    - Descrição: Rota para fazer o upload de arquivos CSV contendo tabelas de frete.

- **Rota para Deleção em Massa de Tabelas de Frete**:
  - `POST /freight-tables/bulkDelete`
    - Controlador: `FreightTableController@bulkDelete`
    - Descrição: Rota para deletar múltiplas entradas de tabelas de frete de uma vez.

- **Rota para Deletar uma Tabela de Frete**:
  - `POST /freight-tables/delete`
    - Controlador: `FreightTableController@destroy`
    - Descrição: Rota para deletar uma tabela de frete específica.

- **Rotas para Tabelas de Frete**:
  - `GET /freight-tables`
    - Controlador: `FreightTableController@index`
    - Descrição: Rota para listar todas as tabelas de frete.
  - `POST /freight-tables`
    - Controlador: `FreightTableController@store`
    - Descrição: Rota para criar uma nova tabela de frete.
  - `GET /freight-tables/{id}`
    - Controlador: `FreightTableController@show`
    - Descrição: Rota para exibir uma tabela de frete específica.
  - `PUT /freight-tables/{id}`
    - Controlador: `FreightTableController@update`
    - Descrição: Rota para atualizar uma tabela de frete específica.

- **Rotas para Clientes**:
  - `GET /customers`
    - Controlador: `CustomerController@index`
    - Descrição: Rota para listar todos os clientes.
  - `POST /customers`
    - Controlador: `CustomerController@store`
    - Descrição: Rota para criar um novo cliente.
  - `GET /customers/{id}`
    - Controlador: `CustomerController@show`
    - Descrição: Rota para exibir um cliente específico.
  - `PUT /customers/{id}`
    - Controlador: `CustomerController@update`
    - Descrição: Rota para atualizar um cliente específico.
  - `DELETE /customers/{id}`
    - Controlador: `CustomerController@destroy`
    - Descrição: Rota para deletar um cliente específico.

- **Rotas para Filiais**:
  - `GET /branches`
    - Controlador: `BranchController@index`
    - Descrição: Rota para listar todas as filiais.
  - `POST /

branches`
    - Controlador: `BranchController@store`
    - Descrição: Rota para criar uma nova filial.
  - `GET /branches/{id}`
    - Controlador: `BranchController@show`
    - Descrição: Rota para exibir uma filial específica.
  - `PUT /branches/{id}`
    - Controlador: `BranchController@update`
    - Descrição: Rota para atualizar uma filial específica.
  - `DELETE /branches/{id}`
    - Controlador: `BranchController@destroy`
    - Descrição: Rota para deletar uma filial específica.

Essas rotas permitem a manipulação eficiente dos dados essenciais para a operação da Testello, facilitando a integração e a manutenção do sistema.

### Hospedagem da API

A API encontra-se hospedada atualmente para fins de demonstração no domínio pessoal [www.albuquerqueincode.com](http://www.albuquerqueincode.com), de onde as rotas poderão ser acessadas.

</details>