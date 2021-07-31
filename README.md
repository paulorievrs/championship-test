## Sobre o projeto

Foi criada uma aplicação para o Processo Seletivo | Spot b - HUB de tecnologia educacional do Bernoulli

A aplicação funciona basicamente para registro de partidas de futebol de um cameponato com algumas regras de negocio.


## Como iniciar o projeto

Para utilizar o projeto deve-se ter previamente instalado em sua máquina:
- **[Php](https://www.php.net/downloads.php)**
- **[Composer](https://getcomposer.org/)**
- **[Mysql](https://www.mysql.com/)** (ou qualquer outro banco de dados, mas com suas devidas adaptações no arquivo .env)
- **[Git](https://git-scm.com/downloads)**


Para baixar o projeto utilize em seu terminal:

``
    $ git clone https://github.com/paulorievrs/championship-test
``

Após a finalização utilize o seguinte comando para entrar na pasta do projeto:

``
    $ cd championship-test
``

Ao entrar na pasta baixe as dependências do projeto:

``
    $ composer install
``

Após a instalação utilize:

``
    $ cp .env.example .env
``

Abra o arquivo .env que foi copiado do exemplo e o preencha com as informações do seu banco de dados.

Gere uma key para a aplicação:

``
   $ php artisan key:generate
``

Popule o banco de dados com dados de times aleátorios

``
    $ php artisan db:seed
``

Rode o projeto:

``
  $ php artisan serve
``

Acesse a aplicação em: http://localhost:8000


## Vídeo

Um vídeo da aplicação está disponível em: https://youtube.com
