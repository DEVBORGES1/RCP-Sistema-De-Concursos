# Manual Técnico - RCP Sistema de Concursos

Este documento fornece detalhes técnicos sobre a arquitetura e implementação do Sistema de Concursos RCP. Destina-se a desenvolvedores que desejam manter ou expandir o sistema.

## 1. Arquitetura do Sistema

O sistema é construído sobre uma arquitetura PHP estruturada, migrando gradualmente de scripts procedurais para uma arquitetura Orientada a Objetos (POO). A lógica de negócio reside principalmente no diretório `app/Classes`.

### Padrões de Projeto
- **Singleton**: Utilizado na classe `Database` para garantir uma única instância de conexão com o banco de dados por requisição, otimizando recursos.
- **Service Layer (parcial)**: Algumas lógicas de negócio estão encapsuladas em classes de serviço/gerenciadoras como `GeradorCronograma` e `AnalisadorEdital`.

## 2. Estrutura de Classes (`app/Classes`)

Abaixo estão descritas as responsabilidades das principais classes do sistema:

### Núcleo
- **Database.php**: Gerencia a conexão PDO com o MySQL. Carrega configurações de `config/database_config.php`.
- **User.php**: Gerencia autenticação, registro e recuperação de dados do usuário.

### Módulo de Estudos
- **AnalisadorEdital.php**: Processa arquivos de editais (provavelmente PDF/texto) para extrair disciplinas e tópicos relevantes.
- **GeradorCronograma.php**: Algoritmo que distribui o conteúdo programático em slots de tempo definidos pelo usuário.
- **GeradorPDFCronograma.php**: Responsável pela exportação do cronograma gerado para formato PDF.

### Módulo de Avaliação
- **Simulado.php**: Gerencia a criação e o estado de uma sessão de simulado.
- **Questao.php**: Manipula a recuperação de questões do banco, verificação de respostas e cálculo de pontuação.

### Gamificação e Progresso
- **Gamificacao.php** / **GamificacaoRefatorada.php**: Lógica para atribuição de pontos (XP), níveis e conquistas aos usuários baseado em suas atividades.
- **SistemaProgressoAvancado.php**: Rastreia o progresso detalhado do aluno em disciplinas e temas específicos.
- **GeradorCertificado.php**: Cria certificados em PDF após a conclusão de cursos ou trilhas.

## 3. Banco de Dados

O banco de dados utiliza MySQL. A conexão é definida centralizadamente pelo arquivo `config/database_config.php`.

### Tabelas Principais (Inferidas pelas classes)
- `users`: Dados de cadastro e login.
- `videoaulas`: Metadados das aulas disponíveis.
- `questoes`: Banco de questões com enunciado, alternativas e resposta correta.
- `simulados`: Registro de tentativas de simulados.
- `cronogramas`: Salva as configurações de agenda de estudos dos usuários.

## 4. Frontend

O frontend não utiliza um framework SPA (como React/Vue), baseando-se em HTML/PHP renderizado no servidor com CSS e JavaScript puros (Vanilla).

- **CSS**: Arquivos de estilo localizados em `assets/css`.
- **JavaScript**: Scripts de interação localizados em `assets/js`.
- **UI Components**: Elementos repetitivos (menus, headers) estão em `includes/` para facilitar a manutenção.

## 5. Fluxo de Autenticação

1. O usuário submete o formulário em `login.php`.
2. O script valida as credenciais contra a tabela `users`.
3. Em caso de sucesso, uma sessão PHP é iniciada (`session_start()`).
4. O usuário é redirecionado para `dashboard.php` ou `dashboard_avancado.php`.

## 6. Procedimentos de Manutenção

### Adicionar Novas Tabelas
Ao criar novas funcionalidades que requerem persistência:
1. Crie o arquivo SQL de migração em `storage/database/`.
2. Execute-o no banco de dados.
3. Atualize ou crie a classe correspondente em `app/Classes` para manipular os novos dados.

### Modificar Configurações
Variáveis de ambiente e segredos de conexão não devem ser hardcoded. Utilize sempre arquivos de configuração dentro de `config/` e adicione-os ao `.gitignore`.
