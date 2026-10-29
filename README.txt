PROJETO: CADASTRO DE ALUNOS

Requisitos:
- PHP 8 ou superior recomendado.
- Não utiliza SGBD nem sessões PHP.
- Os dados ficam armazenados no arquivo alunos.json.

COMO EXECUTAR COM O SERVIDOR EMBUTIDO DO PHP

1. Extraia a pasta cadastro-alunos.
2. Abra um terminal dentro dessa pasta.
3. Execute:
   php -S localhost:8000
4. Acesse no navegador:
   http://localhost:8000/index.php

COMO EXECUTAR COM XAMPP

1. Copie a pasta cadastro-alunos para a pasta htdocs do XAMPP.
2. Inicie o Apache pelo painel do XAMPP.
3. Acesse:
   http://localhost/cadastro-alunos/index.php

IMPORTANTE SOBRE O ARQUIVO alunos.json
O PHP precisa ter permissão de escrita na pasta para gravar os cadastros.
O arquivo é criado no projeto e começa vazio. Faça backup desse arquivo se
quiser preservar os cadastros.

FUNCIONALIDADES
- Cadastro de nome, RA, sexo, idade, endereço, telefone e e-mail.
- Validação de campos e formato de e-mail.
- Impede o cadastro de dois alunos com o mesmo RA.
- Lista todos os alunos por RA crescente.
- Menu lateral responsivo.
- Escape de conteúdo exibido para reduzir risco de injeção de HTML.
