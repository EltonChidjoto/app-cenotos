## About Cenotos ERP
...

## Learning Cenetos ERP
...

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

## Supervisão
- O middleware deixou de criar bases, executar migrations e inserir dados em cada request.
- O tenant agora vem exclusivamente de auth()->user()->tenant; o valor da sessão deixou de ser fonte de autoridade.
- Tenants inativos são bloqueados no login e no contexto tenant.
- Provisionamento separado para Artisan:

```bash
php artisan tenant:provision empresa_demo --seed
```
- Provisionamento protegido com lock para evitar duas migrations simultâneas.
- Rate limiting no login: 5 tentativas por utilizador/IP e 30 por IP.
- Seeder corrigido para não associar users a tenants inexistentes.
- Dados demo em produção ficam desativados por padrão.