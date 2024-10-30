# Documentação dos Comandos Laravel Utils

Esta documentação fornece instruções sobre como usar os comandos que desenvolvi para facilitar o trabalho com o Laravel. Os comandos estão disponíveis para projetos que utilizam **PHP 8.3** e **Laravel 11**.

## Comandos Disponíveis

### 1. Exportar Rotas para Postman

**Descrição:** Este comando extrai as rotas definidas no arquivo `api.php` e gera um arquivo que pode ser importado diretamente no Postman, além de criar um ambiente configurado automaticamente.

**Uso:**

```bash
php artisan app:export-postman-routes
```

**Passos:**

1. Execute o comando acima no terminal na raiz do seu projeto Laravel.
2. O arquivo de exportação será gerado e as pastas já estarão configuradas com o padrão do Laravel (store, index, etc.).
3. Abra o Postman e importe o arquivo gerado.

### 2. Gerar Documentação de APIs

**Descrição:** Este comando cria uma documentação básica das rotas existentes na aplicação, seguindo os padrões do Laravel. Ele permite a exportação em dois formatos: Markdown ou HTML.

**Uso:**

```bash
php artisan generate:api-docs --format [markdown|html]
```

**Parâmetros:**

- `--format`: Especifique o formato da documentação a ser gerada. Opções disponíveis:
  - `markdown`: Gera um arquivo em formato Markdown.
  - `html`: Gera um arquivo em formato HTML.

**Passos:**

1. Execute o comando acima no terminal, especificando o formato desejado.
2. A documentação será gerada e salva no diretório padrão de documentação da sua aplicação.

### 3. Relatório de Código Não Utilizado

**Descrição:** Este comando gera um relatório que lista todo o código não utilizado na aplicação, permitindo que você identifique trechos que podem ser excluídos, refatorados ou investigados.

**Uso:**

```bash
php artisan app:report-unused-code
```

**Passos:**

1. Execute o comando acima no terminal na raiz do seu projeto Laravel.
2. O relatório será gerado e exibido no console, mostrando os arquivos e as linhas que não estão sendo utilizados.

## Contribuições

Se você deseja contribuir com melhorias ou novas funcionalidades, sinta-se à vontade para abrir uma *issue* ou *pull request* no meu repositório.

## Licença

Este projeto está licenciado sob a [Licença MIT](LICENSE).
