# README #

## Instalação local e execução

Para instalar e executar o projeto localmente, execute os seguintes comandos:

```bash
cd ./www
nvm use
npm install
composer install
composer run dev
```

Instalação e manutenção no servidor de produção:

```bash
cd ./www
nvm use
npm install
composer install
php artisan cache:clear
php artisan view:clear
php artisan migrate
php artisan db:seed
npm run build
```
