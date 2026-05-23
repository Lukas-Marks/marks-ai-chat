# Marks AI Chat

Um chat minimalista feito em **PHP + OpenAI API**, com interface moderna, histórico de conversa via sessão e reset rápido.

---

## 🚀 Funcionalidades

* Chat em tempo real com OpenAI
* Histórico de conversa usando `$_SESSION`
* Contexto mantido entre mensagens
* Loader animado enquanto a IA responde
* Botão de reset de conversa
* Scroll automático
* Layout dark minimalista
* Sem banco de dados
* Estrutura simples (ideal para estudos e deploy rápido)

---

## 🛠 Tecnologias

* PHP 8+
* Composer
* OpenAI API
* HTML5
* CSS3
* JavaScript
* Sessions (`$_SESSION`)

---

## 📂 Estrutura do projeto

```bash
Marks-AI-Chat/
│
├── index.php
├── .env
├── .gitignore
├── composer.json
├── vendor/
└── README.md
```

---

## 📦 Instalação

Clone o projeto:

```bash
git clone https://github.com/seu-usuario/marks-ai-chat.git
```

Entre na pasta:

```bash
cd marks-ai-chat
```

Instale dependências:

```bash
composer install
```

---

## 🔑 Configuração da chave da OpenAI

Crie um arquivo `.env` na raiz:

```env
OPENAI_API_KEY=sua_chave_aqui
```

Exemplo:

```env
OPENAI_API_KEY=sk-proj-xxxxxxxxxxxxxxxx
```

---

## ⚠ Segurança

Nunca suba sua chave no GitHub.

Adicione no `.gitignore`:

```bash
.env
vendor/
node_modules/
```

---

## ▶ Como rodar localmente

Se usa XAMPP:

Coloque o projeto dentro de:

```bash
htdocs/
```

Exemplo:

```bash
C:\xampp\htdocs\marks-ai-chat
```

Depois abra:

```bash
http://localhost/marks-ai-chat
```

---

## 💬 Como funciona o histórico

O projeto usa `$_SESSION['chat']`.

Cada mensagem enviada:

* usuário → salva na sessão
* IA → salva na sessão
* histórico inteiro → enviado para API

Isso permite contexto contínuo.

Exemplo:

Usuário:

```bash
Meu nome é Mark
```

Depois:

```bash
Qual meu nome?
```

IA lembra do contexto.

---

## 🔄 Reset de conversa

Botão `↺`:

* limpa sessão
* remove histórico
* reinicia chat

---

## 🎨 Interface

Design minimalista:

* dark mode
* balões de conversa
* loader com animação
* rolagem automática
* input moderno
* botões arredondados

---

## 📌 Modelo usado

Atualmente:

```php
gpt-4o-mini
```

Pode trocar em:

```php
'model' => 'gpt-4o-mini'
```

---

## 🚀 Deploy

Pode subir normalmente para:

* GitHub
* Hostinger
* VPS
* cPanel
* Apache
* XAMPP
* Servidores PHP

Só garanta:

* PHP 8+
* Composer instalado
* extensão cURL ativa
* `.env` no servidor

---

## ❌ Sem banco de dados

O projeto foi pensado para ser simples.

Usa:

```php
$_SESSION
```

Sem:

* MySQL
* PostgreSQL
* MongoDB

---

## 📄 Licença

MIT License

---

## Autor

**Mark**
Projeto: **Marks AI Chat**
