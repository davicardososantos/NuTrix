# 🌬️ 02 - Instalação do Laravel Breeze

## 📌 Objetivo

Adicionar autenticação básica ao projeto utilizando o Laravel Breeze com stack Blade.

---

# 📦 1️⃣ Instalar o Breeze

Executar dentro do container:

```bash
docker compose exec app composer require laravel/breeze --dev
```

---

# ⚙️ 2️⃣ Instalar a stack Blade

```bash
docker compose exec app php artisan breeze:install
```

Selecionar:

```
Blade
```

E:

```
No (sem dark mode)
```

---

# 🖥️ 3️⃣ Instalar dependências do frontend

Entrar na pasta `src`:

```bash
cd src
```

Instalar dependências:

```bash
npm install
```

Gerar build:

```bash
npm run build
```

---

# 🗄️ 4️⃣ Rodar migrations

O Breeze cria tabelas adicionais.

```bash
docker compose exec app php artisan migrate
```

---

# 🔐 5️⃣ Configuração de sessão

No arquivo `.env` garantir:

```env
SESSION_DRIVER=database
```

Se necessário, gerar a tabela de sessões:

```bash
docker compose exec app php artisan session:table
docker compose exec app php artisan migrate
```

---

# 🛠️ 6️⃣ Ajuste de permissões (Docker)

Em ambiente Docker pode ocorrer erro de escrita em `storage`.

Corrigir com:

```bash
docker compose exec app chown -R www-data:www-data storage bootstrap/cache
docker compose exec app chmod -R 775 storage bootstrap/cache
docker compose restart
```

---

# 🧹 7️⃣ Limpar caches (se ocorrer erro 500)

```bash
docker compose exec app php artisan config:clear
docker compose exec app php artisan cache:clear
docker compose exec app php artisan view:clear
docker compose exec app php artisan route:clear
```

---

# 🌐 8️⃣ Testar autenticação

Acessar no navegador:

```
http://localhost/register
```

Se a tela de registro abrir corretamente, a instalação foi concluída com sucesso.

---

# ✅ Resultado Esperado

* Rotas de autenticação criadas
* Telas de login e registro funcionando
* Middleware `auth` disponível
* Sistema pronto para proteger rotas
