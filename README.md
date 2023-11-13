# Clonagem do projeto
- $ git clone https://github.com/AndersonRezende/MagazordTest.git

# Configuração de banco de dados
## Criando o banco
- $ mysql -u seu_usuario -p
- $ CREATE DATABASE magazord;
- $ exit;
## Restaurando o banco
- $ mysql -u seu_usuario -p magazord < caminho/do/projeto/database/magazord.sql

# Configurações de ambiente e execução
- Renomear o arquivo .env.example para .env
- Configurar as variáveis do arquivo .env de acordo com as configurações de banco do seu usuário
- Rodar o comando: $ composer install && composer-dump autoload
- Subir o projeto com o comando: $ php -S localhost:8000
- Acessar o navegador no endereço localhost:8000