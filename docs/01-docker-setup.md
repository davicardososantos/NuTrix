# 🚀 01 - Setup com Docker

## 📦 Pré-requisitos

* Docker
* Docker Compose

---

# 🔧 1️⃣ Clonar o projeto

```bash
git clone https://github.com/davicardososantos/NuTrix.git
cd NuTrix
```

---

# 🐳 2️⃣ Subir os containers

Build inicial:

```bash
docker compose up -d --build
```

Se precisar rebuildar do zero:

```bash
docker compose down -v
docker compose up -d --build
```

---

# ⚙️ 3️⃣ Criar o arquivo .env

Entrar na pasta src:

```bash
cd src
```

Copiar o arquivo padrão:

```bash
cp .env.example .env
```

(Windows PowerShell)

```powershell
copy .env.example .env
```

---

# 🗄️ 4️⃣ Configuração do .env

Configurar as variáveis principais:

```env
APP_NAME=NuTrix
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=laravel

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

---

# 🔑 5️⃣ Gerar chave da aplicação

```bash
docker compose exec app php artisan key:generate
```

---

# 🗃️ 6️⃣ Rodar migrations

```bash
docker compose exec app php artisan migrate
```

---

# 🔐 7️⃣ Ajustar permissões (recomendado)

```bash
docker compose exec app chmod -R 775 storage bootstrap/cache
```

---

# 📦 8️⃣ Instalar dependências (se necessário)

```bash
docker compose exec app composer install
```

---

# 🌐 9️⃣ Acessar o projeto

Abrir no navegador:

```
http://localhost
```

---

# 🛑 Comandos úteis

Parar containers:

```bash
docker compose down
```

Ver logs:

```bash
docker compose logs -f
```

Acessar container:

```bash
docker compose exec app sh
```

---

# 🧱 Estrutura do Projeto Inicial

```
NuTrix/
 ├── docker/
 ├── docker-compose.yml
 ├── Dockerfile
 ├── src/
 │    ├── app/
 │    ├── routes/
 │    ├── artisan
 │    ├── composer.json
 │    └── .env
```
