# Projeto de Moderação de Comentários com IA

Este projeto tem como objetivo implementar a moderação de comentários em uma aplicação Laravel usando IA para filtrar comentários tóxicos. Utilizei a API de IA **unitary/toxic-bert** para moderação

## Tecnologias Utilizadas

- **Laravel**: Framework PHP utilizado para desenvolvimento da API.
- **unitary/toxic-bert**: "".
- **Google Translate API**: Usado para traduzir textos para inglês antes de passar pela moderação.

## Instalação

Siga os passos abaixo para rodar o projeto localmente:

1. **Clone o repositório**:
   ```bash
   git clone https://github.com/seu-usuario/seu-projeto.git

2. **Instale as dependências do Laravel:**
    cd seu-projeto
    composer install

3. **Variaveis Adicionais do .env**
    EXTERNAL_API_URL="https://go-tour-bahia.onrender.com/"  
    HUGGING_FACE_API_KEY="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"

4. **Instale a dependência de tradução**
    composer require stichoza/google-translate-php

5. **Iniciar o servidor**
    php artisan serve


## IA Utilizada para Moderação
    A moderação de comentários é feita utilizando a API /unitary/toxic-bert hospedada no HuggingFace. O serviço de moderação foi integrado ao Laravel por meio do serviço HuggingFaceModerationService. Este serviço consome a API de moderação de textos para detectar se um comentário é tóxico, utilizando o modelo unitary/toxic-bert, que é especializado em identificar linguagem tóxica e imprópria em textos.

## Detalhes sobre como os endpoints foram utilizados e integrados

### Endpoint: `api/place`

    Esta rota permite obter locais com comentários associados. Ela retorna uma lista de lugares que possuem comentários registrados no sistema.

### Endpoint: `/comments`

    rota que exibe os comentários ja moderados, e na sua inicialização, além do processo de moderação, também utiliza
    da lista de comentarios toxicos encontrados para automatizar a denúncia de comentários inadequados utilizando o endpoint /reports da api fornecida

## Como funciona a moderação de comentários?
    A moderação dos comentários é realizada pela API do HuggingFace utilizando o modelo unitary/toxic-bert, que foi projetado para identificar comentários com conteúdos tóxicos, incluindo insultos, discriminação, e linguagem prejudicial. Para comentários em português, é feito um processo de tradução para o inglês antes de enviá-los à API de moderação para maior
    precisão