# RCP Sistema de Concursos

## Visão Geral

O RCP Sistema de Concursos é uma plataforma web robusta desenvolvida para gerenciamento e disponibilização de conteúdo preparatório para concursos públicos. O sistema integra funcionalidades de videoaulas, banco de questões, simulações de provas e criação de cronogramas de estudo personalizados.

O projeto foca em uma arquitetura modular em PHP, utilizando princípios de Programação Orientada a Objetos (POO) e padrão Singleton para gerenciamento de banco de dados.

## Funcionalidades Principais

### Gestão de Estudos
- **Videoaulas**: Sistema completo de organização e visualização de aulas por disciplina e tema.
- **Editais**: Ferramenta para upload e análise de editais (PDF) para extração de conteúdo programático.
- **Cronograma Inteligente**: Gerador automático de cronogramas de estudo baseado na disponibilidade do usuário e nos tópicos do edital.
- **Certificados**: Geração de certificados de conclusão de curso.

### Avaliação e Desempenho
- **Simulados**: Criação de provas simuladas com temporizador e correção automática.
- **Banco de Questões**: Repositório de questões categorizadas para prática individual.
- **Dashboard de Desempenho**: Visualização de métricas de progresso, incluindo sistema de níveis e gamificação.

### Controle de Acesso
- **Autenticação**: Sistema seguro de login e registro.
- **Perfis de Usuário**: Gestão de dados pessoais e preferências de estudo.

## Requisitos do Sistema

- **Servidor Web**: Apache ou Nginx.
- **PHP**: Versão 7.4 ou superior.
- **Banco de Dados**: MySQL 8.0 ou superior.
- **Extensões PHP**: PDO, GD (para imagens/certificados).

## Instalação e Configuração

### 1. Configuração do Banco de Dados

1. Crie um banco de dados MySQL (ex: `concursos`).
2. Importe o esquema inicial localizado em:
   `storage/database/banco_completo.sql`
3. Execute scripts adicionais se necessário (encontrados em `storage/database/`).

### 2. Configuração da Conexão

Navegue até a pasta `config` e crie um arquivo chamado `database_config.php` com o seguinte conteúdo, ajustando as credenciais conforme seu ambiente:

```php
<?php
return [
    'host' => 'localhost',
    'db'   => 'concursos',
    'user' => 'seu_usuario',
    'pass' => 'sua_senha'
];
```

### 3. Execução

Configure seu servidor web (como Apache no XAMPP ou Laragon) para apontar para o diretório raiz do projeto. O ponto de entrada da aplicação é o arquivo `index.php`.

## Estrutura de Diretórios

- `app/Classes`: Contém as classes de lógica de negócios (User, Simulado, Questao, etc.).
- `assets`: Recursos estáticos (CSS, JS, Imagens).
- `config`: Arquivos de configuração do sistema.
- `storage`: Armazenamento de arquivos (uploads, backups de banco de dados).
- `includes`: Componentes de UI reutilizáveis (cabeçalhos, rodapés, barras laterais).

## Desenvolvimento

Para novas implementações, recomenda-se seguir o padrão estabelecido na pasta `app/Classes`, utilizando o Singleton `Database::getInstance()` para interações com o banco de dados, em detrimento de conexões diretas ou código legado.
