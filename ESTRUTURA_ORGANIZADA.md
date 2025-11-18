# Estrutura Organizada do Projeto

## 📁 Estrutura de Pastas

```
CODIGOTESTE/
├── app/                          # Lógica da aplicação
│   └── Classes/                  # Classes PHP
│       ├── AnalisadorEdital.php
│       ├── Database.php
│       ├── Gamificacao.php
│       ├── GamificacaoRefatorada.php
│       ├── GeradorCertificado.php
│       ├── GeradorCronograma.php
│       ├── GeradorPDFCronograma.php
│       ├── Questao.php
│       ├── Simulado.php
│       ├── SistemaProgressoAvancado.php
│       └── User.php
│
├── assets/                       # Recursos estáticos
│   └── css/                      # Arquivos CSS e imagens
│       ├── style.css
│       ├── concurso.png
│       └── concurso.ico
│
├── config/                       # Arquivos de configuração
│   ├── conexao.php              # Configuração do banco de dados
│   └── paths.php                # Configuração de caminhos
│
├── storage/                      # Arquivos gerados e uploads
│   ├── uploads/                 # Arquivos enviados pelos usuários
│   │   ├── *.pdf               # Editais enviados
│   │   ├── cronograma_*.html   # Cronogramas gerados
│   │   └── certificado_*.html  # Certificados gerados
│   └── database/                # Scripts SQL
│       ├── banco_completo.sql
│       ├── adicionar_tabelas_videoaulas.sql
│       └── inserir_categorias_videoaulas.sql
│
├── public/                       # (Reservado para futuras melhorias)
│
└── [Arquivos PHP principais]     # Arquivos na raiz (ponto de entrada)
    ├── index.php
    ├── login.php
    ├── register.php
    ├── dashboard.php
    ├── perfil.php
    ├── questoes.php
    ├── simulados.php
    ├── editais.php
    └── ...
```

## 🔧 Mudanças Realizadas

### 1. Organização de Classes
- **Antes:** `classes/`
- **Depois:** `app/Classes/`
- Todos os `require` foram atualizados para usar `__DIR__ . '/app/Classes/...'`

### 2. Recursos Estáticos
- **Antes:** `css/`
- **Depois:** `assets/css/`
- Todos os links `href="css/..."` foram atualizados para `href="assets/css/..."`

### 3. Arquivos de Configuração
- **Antes:** `conexao.php` na raiz
- **Depois:** `config/conexao.php`
- Todos os `require 'conexao.php'` foram atualizados para `require __DIR__ . '/config/conexao.php'`

### 4. Uploads e Arquivos Gerados
- **Antes:** `uploads/`
- **Depois:** `storage/uploads/`
- Todos os caminhos foram atualizados para usar `__DIR__ . '/storage/uploads/'`

### 5. Scripts SQL
- **Antes:** Arquivos `.sql` na raiz
- **Depois:** `storage/database/`

## 📝 Padrões de Caminhos

### Para Classes PHP:
```php
require_once __DIR__ . '/app/Classes/NomeClasse.php';
```

### Para Configuração:
```php
require __DIR__ . '/config/conexao.php';
```

### Para CSS/Assets:
```html
<link rel="stylesheet" href="assets/css/style.css">
```

### Para Uploads (caminho físico):
```php
$filepath = __DIR__ . '/storage/uploads/' . $filename;
```

### Para Uploads (URL web):
```php
$url = '/storage/uploads/' . $filename;
```

## ⚠️ Importante

1. **Permissões:** Certifique-se de que a pasta `storage/uploads/` tem permissões de escrita (chmod 755 ou 775)

2. **Servidor Web:** Se estiver usando Apache, pode ser necessário criar um `.htaccess` em `storage/uploads/` para permitir acesso aos arquivos:
   ```apache
   Options -Indexes
   AllowOverride None
   ```

3. **Segurança:** A pasta `storage/uploads/` deve ser acessível via web apenas para arquivos específicos. Considere implementar um sistema de controle de acesso.

## 🔄 Compatibilidade

- Todos os caminhos foram atualizados usando `__DIR__` para garantir compatibilidade independente de onde o script é executado
- Os links HTML usam caminhos relativos que funcionam a partir da raiz do projeto
- As classes mantêm compatibilidade com o código existente

## 📚 Próximos Passos Recomendados

1. Implementar autoloader PSR-4 para classes
2. Mover arquivos PHP principais para `public/` e configurar o servidor web
3. Implementar sistema de rotas
4. Adicionar validação de tipos de arquivo em uploads
5. Implementar sistema de cache para assets

