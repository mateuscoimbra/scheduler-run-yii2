# scheduler-run-yii2

Uma biblioteca de agendamento de tarefas simples e poderosa para o Yii2, inspirada no Laravel Scheduler.

## Instalação

A forma mais fácil de instalar a biblioteca é usando o Composer.

```bash
composer require sua-lib/scheduler-run-yii2
````

## Configuração

Após a instalação, você precisa configurar o componente e o controller na sua aplicação console do Yii2.

1.  Abra o arquivo de configuração de console da sua aplicação (geralmente em `config/console.php`).

2.  Adicione a seguinte configuração ao array de `components`:

    ```php
    'components' => [
        // ... outros componentes
        'scheduler' => [
            'class' => \SuaLib\SchedulerRunYii2\components\Scheduler::class,
            'schedule' => function (\SuaLib\SchedulerRunYii2\schedule\Schedule $schedule) {
                // Defina suas tarefas aqui!
            }
        ],
    ],
    ```

3.  Adicione o controller do scheduler ao array de `controllerMap`:

    ```php
    'controllerMap' => [
        'schedule' => \SuaLib\SchedulerRunYii2\console\controllers\ScheduleController::class,
    ],
    ```

## Definindo Tarefas

Dentro da função `schedule` que você acabou de configurar, você pode agendar comandos de console, comandos do sistema e funções anônimas.

### 1\. Comandos de Console do Yii2

Use o método `command()` para agendar comandos de console do seu projeto.

```php
$schedule->command('migrate')->daily();
```

### 2\. Comandos do Sistema

Use o método `exec()` para agendar qualquer comando do sistema operacional.

```php
$schedule->exec('php /var/www/seu-projeto/artisan queue:work')->everyMinute();
```

### 3\. Funções Anônimas (Closures)

Use o método `call()` para agendar uma função anônima com qualquer lógica PHP.

```php
$schedule->call(function () {
    Yii::info('Esta tarefa foi executada!');
})->hourly();
```

## Frequências de Agendamento

Você pode usar os métodos de frequência para definir o horário de execução.

  * `everyMinute()`: Executa a cada minuto.
  * `hourly()`: Executa no minuto zero de cada hora.
  * `daily()`: Executa à meia-noite (00:00).
  * `cron('* * * * *')`: Define uma expressão cron personalizada.

## Configurando o Cron Job

Para que o agendador funcione, você precisa configurar um cron job no seu servidor para executar o comando `schedule/run` a cada minuto.

1.  Abra o crontab do seu servidor com o comando:

    ```bash
    crontab -e
    ```

2.  Adicione a seguinte linha ao final do arquivo, ajustando o caminho para o seu projeto:

    ```bash
    * * * * * /usr/bin/php /caminho/para/o/seu/projeto/yii schedule/run --interactive=0 1>> /dev/null 2>&1
    ```

> **Atenção:**
>
>   * Substitua `/usr/bin/php` pelo caminho completo do seu binário PHP.
>   * Substitua `/caminho/para/o/seu/projeto` pelo caminho absoluto para o diretório raiz do seu projeto Yii2.
>   * O `--interactive=0` evita que o comando pare para pedir confirmações.
>   * O `1>> /dev/null 2>&1` redireciona a saída e os erros para o "nada", evitando a criação de logs de cron indesejados.

## Contribuições

Sinta-se à vontade para contribuir\! Abra uma issue ou um pull request no nosso repositório.

```

---

### **Próximos Passos**

1.  **Crie o projeto e os arquivos:** Siga a estrutura de diretórios e crie os arquivos com os conteúdos que forneci.
2.  **Rode `composer install`:** No terminal, no diretório raiz do seu projeto (`scheduler-run-yii2`), execute `composer install`. Isso instalará a dependência `cron-expression`.
3.  **Publique no GitHub:** Crie um repositório no GitHub e envie seu código. Isso permitirá que outras pessoas usem sua biblioteca.
4.  **Publique no Packagist:** Depois de ter seu repositório no GitHub, você pode submeter o pacote para o Packagist, tornando-o fácil de ser instalado com `composer require`.

Com essa estrutura e documentação, você terá uma base sólida para um projeto open-source que, com certeza, será muito útil para a comunidade Yii2! Se tiver mais alguma dúvida, pode perguntar.
```