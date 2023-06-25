# Sistema de Estacionamento "parking-system"

O "parking-system" é um sistema de estacionamento desenvolvido em PHP, JavaScript e Laravel 8, com a utilização das bibliotecas DataTables, AdminLTE, Ladda, entre outras. Esse sistema oferece uma variedade de recursos para o gerenciamento eficiente de serviços, vagas, clientes, reservas, envio de e-mails e geração de relatórios em formato PDF e Excel.

## Recursos Principais

O "parking-system" possui os seguintes recursos principais:

1. Gerenciamento de Serviços:
   - Possibilidade de criar e gerenciar serviços avulsos oferecidos pelo estacionamento.
   - Suporte para serviços oferecidos a clientes cadastrados no sistema.
   - Sistema de envio de recibo por e-mail em pdf(download).
   
2. Gerenciamento de Vagas:
   - Funcionalidade para criar e gerenciar vagas disponíveis no estacionamento.
   - Acompanhamento em tempo real das vagas ocupadas e disponíveis.
   
3. Gerenciamento de Clientes:
   - Capacidade de cadastrar e gerenciar informações de clientes que utilizam o estacionamento.
   - Histórico de serviços utilizados por cada cliente, proporcionando um melhor controle financeiro.
   
4. Reservas:
   - Opção para os clientes realizarem reservas de vagas antecipadamente.
   
5. Relatórios:
   - Geração de relatórios em formato PDF e Excel, permitindo uma análise de alguns dados do estacionamento.
   - Relatórios de ocupação de vagas e outras estatísticas relevantes.
   
## Tecnologias Utilizadas

O sistema "parking-system" foi desenvolvido utilizando as seguintes tecnologias e bibliotecas:

- PHP: Linguagem de programação utilizada para a implementação das funcionalidades do sistema.
- JavaScript: Utilizado para tornar a interface do usuário interativa e responsiva.
- Laravel 8: Framework PHP de alto desempenho e elegante utilizado para o desenvolvimento do sistema.
- DataTables: Biblioteca JavaScript que permite a exibição e manipulação de dados em tabelas de forma avançada e interativa.
- AdminLTE: Template de painel administrativo responsivo e moderno que oferece uma interface de usuário intuitiva e agradável.
- Ladda: Biblioteca JavaScript que fornece animações de carregamento para melhorar a experiência do usuário durante a interação com o sistema.

## Requisitos de Instalação

Para executar o "parking-system" em seu ambiente local, certifique-se de cumprir os seguintes requisitos:

- Servidor web (como Apache ou Nginx)
- PHP 7.4 ou superior
- Banco de dados MySQL
- Composer (gerenciador de dependências para o PHP)
- Git (opcional, caso queira clonar o repositório diretamente)

## Instalação

Siga as etapas abaixo para instalar o sistema "parking-system" em seu ambiente local:

1. Clone o repositório (ou faça o download dos arquivos) para o seu servidor web:

```
git clone https://github.com/Jonnibones/parking-system.git
```

2. Acesse o diretório do projeto:

```
cd parking-system
```

3. Instale as dependências do Composer:

```
composer install
```

4. Crie um arquivo de ambiente (.env) a partir do exemplo fornecido (.env.example):

```
cp .env.example .env
```

5. Configure o arquivo .env com as informações do seu banco de dados:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nome-do-banco-de-dados
DB_USERNAME=usuario-do-banco-de-dados
DB_PASSWORD=senha-do-banco-de-dados
```

6. Gere uma chave de aplicativo:

```
php artisan key:generate
```

7. Inicie o servidor local:

```
php artisan serve
```

8. Acesse o sistema no seu navegador usando o endereço fornecido pelo comando anterior.

## Conclusão

O sistema de estacionamento "parking-system" oferece uma solução completa para o gerenciamento eficiente de serviços, vagas, clientes, reservas e geração de relatórios. Com uma interface intuitiva e recursos avançados, esse sistema simplifica a administração do estacionamento e fornece uma visão abrangente das atividades e finanças relacionadas ao negócio. Sinta-se à vontade para explorar o código-fonte e personalizar o sistema de acordo com suas necessidades específicas.