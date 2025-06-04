# Vendas

Sistema de gestão de vendedores e vendas com cálculo de comissão e envio de relatórios por e-mail.

## 🚀 Como executar o projeto

### 1. Clone o repositório e acesse a pasta do projeto
```bash
git clone https://github.com/Jesonilton/vendas.git
cd vendas
```

### 2. Suba os containers
```bash
docker-compose up -d
```

### 3. Configure o ambiente no container Laravel
```bash
docker exec -it laravel_app bash
cp .env.example .env
php artisan migrate
php artisan db:seed
exit
```

### 4. Reinicie o serviço do Laravel

O Supervisor é executado no container Laravel. Como o container é criado antes da execução das migrations, é recomendado reiniciar o serviço Laravel:

```bash
docker-compose down app
docker-compose up -d app
```

### 5. Configure credenciais de e-mail

Configure as variáveis de e-mail no arquivo `.env`. Exemplo usando [Mailtrap](https://mailtrap.io):

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=seu_usuario
MAIL_PASSWORD=sua_senha
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=mailer@example.com
MAIL_FROM_NAME="Laravel Mailtrap"
```

### 6. Teste o envio de e-mails

```bash
docker exec -it laravel_app bash
php artisan tinker
```

Cole o código abaixo e pressione Enter:

```php
Mail::raw('Este é um teste de e-mail no Laravel', function ($message) {
    $message->to('seugmail@gmail.com')
            ->subject('Teste de e-mail');
});
```

Verifique se o e-mail chegou na sua caixa de entrada.

### 7. Acesse o sistema

Abra no navegador:
```
http://localhost:5173
```

O usuário padrão é criado pela seed. Basta clicar em **"Entrar"** na tela inicial.
