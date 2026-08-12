# Notificações de calibração

O sistema envia Web Push quando uma ferramenta muda para `Atenção`, `Crítica` ou `Vencida`.
Cada usuário ativa ou desativa as notificações em cada aparelho. Os administradores inscritos recebem as mudanças de todas as ferramentas. Quando a ferramenta está ligada a uma pessoa, o responsável inscrito também recebe o aviso.

## Configuração na Hostinger

No terminal, entre no Laravel:

```bash
cd ~/domains/lippel.ind.br/laravel-controle
```

Instale as dependências do `composer.lock` usando o PHP 8.3:

```bash
COMPOSER_BIN=$(command -v composer2 || command -v composer)
/opt/alt/php83/usr/bin/php "$COMPOSER_BIN" install --no-dev --optimize-autoloader
```

Gere as chaves apenas uma vez:

```bash
/opt/alt/php83/usr/bin/php artisan notifications:vapid-keys
```

Copie as três linhas exibidas para o `.env`. O assunto deve ficar assim:

```dotenv
APP_URL=https://lippel.ind.br/controle
APP_TIMEZONE=America/Sao_Paulo
VAPID_SUBJECT=https://lippel.ind.br/controle
VAPID_PUBLIC_KEY=chave_publica_gerada
VAPID_PRIVATE_KEY=chave_privada_gerada
```

Não publique a chave privada no GitHub. Depois execute:

```bash
/opt/alt/php83/usr/bin/php artisan migrate --force
/opt/alt/php83/usr/bin/php artisan optimize:clear
```

## Cron Job

No hPanel, crie um Cron Job para executar a cada minuto:

```bash
cd /home/u899807498/domains/lippel.ind.br/laravel-controle && /opt/alt/php83/usr/bin/php artisan schedule:run >> /dev/null 2>&1
```

O Laravel fará a verificação diária às 07:00 no horário de São Paulo.

Na primeira execução, o sistema apenas registra o estado atual para não disparar notificações antigas. Para também avisar os estados de alerta que já existem, execute uma vez:

```bash
/opt/alt/php83/usr/bin/php artisan notifications:check-calibration-statuses --notify-current
```

Depois, cada pessoa deve abrir o aplicativo no celular e tocar em `Ativar neste aparelho` na Minha Área. Administradores encontram o mesmo controle na tela Cadastros.

Para confirmar a inscrição do celular sem alterar ferramentas, envie um teste usando o nome de login:

```bash
/opt/alt/php83/usr/bin/php artisan notifications:test admin
```
