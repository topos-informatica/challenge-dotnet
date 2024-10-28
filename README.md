# Projeto de Moderação de Comentários

## Objetivo

O objetivo deste projeto é criar um sistema automatizado de moderação de comentários para identificar e classificar conteúdos inadequados, como mensagens de ódio, spam, violência, e outros tipos de comportamento prejudicial ou indesejado. A aplicação não só modera esses comentários, mas também os classifica e, se necessário, os reporta para revisão adicional.

## Tecnologias Utilizadas

O projeto utiliza as seguintes tecnologias:

- **.NET**: A aplicação foi desenvolvida usando .NET, permitindo uma construção robusta e escalável para serviços de API.
- **HttpClient**: Ferramenta para realizar requisições HTTP e integrar com APIs externas, como a da OpenAI.
- **Newtonsoft.Json**: Biblioteca para manipulação e serialização de dados JSON.
- **OpenAI API**: Integração com a API da OpenAI para acessar modelos de linguagem e realizar tarefas de moderação e classificação de texto.

## Modelo de Linguagem (LLM) Utilizado

O modelo **GPT-3.5-turbo** foi escolhido para a moderação dos comentários. Este modelo, disponibilizado pela OpenAI, foi selecionado por:

- **Precisão e versatilidade** em entender o contexto e identificar nuances em comentários que possam ser inapropriados.
- **Capacidade de classificação e moderação de conteúdo** com respostas consistentes e contextualizadas, facilitando a criação de um sistema que realiza análises de forma eficiente.
- **Tempo de resposta otimizado**, tornando-o adequado para um sistema de moderação que precisa avaliar rapidamente comentários em tempo real.

## API de Moderação de Comentários

O projeto inclui um endpoint específico para realizar a moderação e classificação de comentários:

### Endpoint: `GET /moderation/moderate`

Este endpoint é utilizado para avaliar comentários associados a um local específico e verificar se cada comentário é adequado ou se deve ser classificado como inapropriado (SPAM, HATE, FAKE, BULLYING, VIOLENCE, SCAM, SUICIDE).

- **URL**: `http://localhost:host/api/moderation/moderate`
- **Método HTTP**: `GET`
- **Descrição**: Retorna uma lista de comentários associados a um local, onde cada comentário é moderado e classificado quanto à adequação.

#### Exemplo de Resposta da API

A resposta da API inclui informações detalhadas sobre o local e seus comentários moderados:

```json
{
    "id": "001ac02a-2906-409a-a1cb-e6628b63e8f9",
    "name": "Villa Alexandre Pousada",
    "comments": [
        {
            "id": "6a7af80e-2131-48ce-bccd-6ffbd34e20ed",
            "message": "O restaurante é incrível! Ótimo atendimento e comida deliciosa. Voltarei com certeza.",
            "place_id": "001ac02a-2906-409a-a1cb-e6628b63e8f9",
            "moderation": "This comment is positive and provides a helpful review of a restaurant, highlighting good service and delicious food. It is appropriate and adds value by sharing a personal experience.",
            "reason": "APPROPRIATE"
        }
    ]
}

```

```id```: Identificador único do comentário.

```message```: O conteúdo do comentário.

```place_id```: Identificador único do local associado ao comentário.

```moderation```: Descrição da análise de moderação, indicando se o comentário é adequado ou problemático.

```reason```: Classificação final do comentário, como APPROPRIATE para comentários adequados.


### Uso do Endpoint
Para utilizar o endpoint /moderation/moderate, envie uma requisição GET à URL para recuperar os comentários moderados. A resposta incluirá o conteúdo do comentário, sua avaliação e o motivo da classificação atribuída.


