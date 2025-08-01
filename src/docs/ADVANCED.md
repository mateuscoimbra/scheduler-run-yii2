### **Documentação Técnica para Desenvolvedores Iniciantes**

Olá\! Se você está lendo este documento, provavelmente quer entender melhor como esta biblioteca de agendamento de tarefas para o Yii2 funciona por baixo dos panos. O objetivo aqui é ir além do "como usar" e mostrar o "porquê" de cada decisão técnica.

#### **Estrutura da Biblioteca: Onde Cada Coisa Mora**

A estrutura do projeto foi pensada para seguir o padrão de pacotes do Composer, que é como o PHP gerencia bibliotecas.

```
scheduler-run-yii2/
├── src/                      # Todo o código PHP da biblioteca
│   ├── components/           # Componentes do Yii2
│   │   └── Scheduler.php     # Classe que gerencia a configuração de agendamento
│   ├── console/              # Comandos de console do Yii2
│   │   └── controllers/
│   │       └── ScheduleController.php # Comando 'schedule/run'
│   └── schedule/             # Onde a lógica principal de agendamento vive
│       ├── Event.php         # Representa uma única tarefa agendada
│       └── Schedule.php      # Coletor de eventos e gerenciador de execução
├── composer.json             # O arquivo que o Composer lê
└── docs/                     # Documentação mais detalhada (como este arquivo)
```

**Por que essa estrutura?**

  * **Separação de responsabilidades:** Cada pasta tem um propósito claro. O código do Yii2 fica em `src/`, a configuração do Composer em `composer.json`, e a documentação em `docs/`. Isso facilita a manutenção e a compreensão do projeto.
  * **Padrão PSR-4:** O `composer.json` define a regra `psr-4` que mapeia o namespace `SuaLib\SchedulerRunYii2\` para o diretório `src/`. Isso significa que o Composer sabe exatamente onde encontrar cada classe da sua biblioteca. Por exemplo, a classe `SuaLib\SchedulerRunYii2\console\controllers\ScheduleController` está no arquivo `src/console/controllers/ScheduleController.php`.

#### **O Fluxo de Execução: Como o Cron Inicia as Tarefas**

Para entender como a mágica acontece, siga o fluxo de execução a partir do comando que você configura no cron do servidor:

1.  **O Cron Dispara o Comando:** A cada minuto, o cron executa `php yii schedule/run`.

2.  **O Yii2 Carrega o Controller:** O Yii2 identifica que o comando `schedule` corresponde à classe `SuaLib\SchedulerRunYii2\console\controllers\ScheduleController.php`.

3.  **O `ScheduleController` Entra em Ação:**

      * No método `init()`, ele busca o componente `scheduler` configurado na sua aplicação Yii2.
      * No método `actionRun()`, ele chama o método `$this->scheduler->getTasks()`.

4.  **O `Scheduler` Coleta as Tarefas:**

      * O componente `Scheduler.php` cria uma nova instância da classe `Schedule.php`.
      * Ele chama o método `schedule()` que, na sua aplicação, executa a função anônima que você definiu. É aqui que todos os seus `.everyMinute()`, `.daily()`, etc., são registrados.
      * O componente devolve a instância de `Schedule` com todas as tarefas configuradas.

5.  **A Classe `Schedule` Executa as Tarefas:**

      * O método `run()` da classe `Schedule` é chamado.
      * Ele percorre a lista de todas as tarefas que foram agendadas.
      * Para cada tarefa, ele verifica se é a hora de executá-la usando o método `$event->isDue()`.
      * Se for o momento, ele chama o método `$event->run()`, que executa o comando ou a função anônima da tarefa.

#### **Entendendo as Classes e a Lógica**

  * **`Schedule.php`**: Pense nesta classe como um "coletor" de tarefas. Ela tem métodos como `command()`, `exec()` e `call()` que não executam nada, apenas criam objetos `Event` e os guardam em um array.
  * **`Event.php`**: Esta classe é a representação de **uma única tarefa**. Ela contém o comando que precisa ser executado, a expressão `cron` que define a frequência e o método `isDue()` que decide se a tarefa deve ser executada ou não. A biblioteca `dragonmantank/cron-expression` é usada aqui para fazer a mágica de verificar o horário.
  * **`Scheduler.php`**: Este é o ponto de conexão entre a sua aplicação e a biblioteca. Ele é um componente do Yii2, o que significa que ele pode ser configurado e acessado de qualquer lugar, seguindo o padrão do framework.

**Dicas para Iniciantes:**

  * **Namespaces são importantes:** Sempre preste atenção aos `use` no topo dos arquivos. Eles são essenciais para que o PHP saiba onde encontrar as classes. Se você tiver um erro de classe não encontrada, o problema quase sempre está no namespace ou no `use`.
  * **Padrão Fluent Interface:** A sintaxe `->daily()->at('10:00')` é conhecida como **"fluent interface"**. Ela permite encadear chamadas de métodos, tornando o código mais legível e parecido com uma frase em inglês. Na prática, cada método retorna a própria instância (`return $this;`), permitindo a próxima chamada.
  * **Cron e Servidores:** Lembre-se que o cron job não é parte do PHP ou do Yii2. Ele é um serviço do seu sistema operacional. A sua biblioteca apenas cria o "caminho" para que o cron possa executar o comando PHP necessário.

Com esta documentação, esperamos que você se sinta mais confiante para não apenas usar esta biblioteca, mas também para aprimorá-la e, quem sabe, criar a sua própria no futuro. Se houver algo que ainda não está claro, não hesite em perguntar\!