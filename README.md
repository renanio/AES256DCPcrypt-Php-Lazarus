# Projeto de Criptografia AES e HMAC com PHP e Lazarus

## Descrição

Este projeto tem como objetivo fornecer um meio de criptografar mensagens em PHP e descriptografá-las em uma aplicação desktop feita com Lazarus (Free Pascal). Ele utiliza o padrão de criptografia AES (Advanced Encryption Standard) em conjunto com HMAC (Hash-based Message Authentication Code) para garantir tanto a criptografia quanto a integridade dos dados.

## Índice

- [Requisitos](#requisitos)
- [Como Utilizar](#como-utilizar)
- [Funcionamento Interno](#funcionamento-interno)
- [Limitações e Considerações](#limitações-e-considerações)
- [Contribuições](#contribuições)
- [Licença](#licença)

## Requisitos

### Para o script PHP

- PHP 7.x ou superior
- Extensão OpenSSL para PHP

### Para a aplicação Lazarus

- Lazarus IDE
- Pacote DCPcrypt2 (para a implementação do AES e SHA-256)

## Como Utilizar

### Criptografia em PHP

O script PHP contém uma função `encryptAES`, responsável por realizar a criptografia AES. Para utilizar esta função, você precisa fornecer o texto que deseja criptografar (`$plaintext`) e uma chave secreta (`$key`).

**Exemplo de uso:**

```php
$encryptedText = encryptAES("Texto a ser criptografado", "ChaveSecreta");
```

## Descriptografia em Lazarus

1. Abra o projeto no Lazarus IDE.
2. Compile e execute o aplicativo.
3. Utilize os campos de texto para inserir o texto criptografado e a chave de criptografia.
4. Clique no botão "Decrypt" para descriptografar o texto.

## Funcionamento Interno

### PHP

A função `encryptAES` realiza as seguintes etapas:

1. Gera um Initialization Vector (IV) aleatório.
2. Utiliza o OpenSSL para criptografar o texto fornecido.
3. Cria um HMAC para garantir a integridade dos dados.
4. Concatena o IV, o HMAC e o texto criptografado e retorna a string em Base64.

### Lazarus

O aplicativo em Lazarus utiliza as seguintes funções principais:

- `btnDecryptClick`: Acionada quando o usuário clica no botão "Decrypt". Essa função coleta os dados inseridos, chama a função `decryptAES` e exibe o texto descriptografado.

- `decryptAES`: Esta função descriptografa o texto usando AES. Ela realiza a extração do IV e do HMAC, realiza a descriptografia e verifica a integridade do texto usando o HMAC.

## Limitações e Considerações

- A chave de criptografia é armazenada como texto simples nos exemplos, o que não é recomendado para ambientes de produção.
- Não há interface para a geração segura ou armazenamento seguro de chaves.

## Contribuições

Contribuições são bem-vindas! Sinta-se à vontade para abrir issues ou Pull Requests.

## Licença

Este projeto está licenciado sob a licença MIT.
