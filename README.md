# Desafio Técnico: Moderação de Comentários Usando Inteligência Artificial
 
## Descrição do Desafio
 
O objetivo deste desafio é desenvolver uma API em **.NET** que consuma endpoints de uma API pré-existente e utilize uma solução de **Inteligência Artificial (IA)** para moderar comentários sobre lugares (restaurantes, pontos turísticos, locais religiosos, etc.). O sistema deve identificar comentários inadequados, utilizando a IA de sua escolha.
 
Você terá **5 dias** para completar o desafio e deverá entregar o código em um repositório público no **GitHub**.
 
## Endpoints Disponíveis
 
Você terá acesso aos seguintes endpoints:
 
1. **Pegar lugares com comentários**  
   - **Descrição**: Recupera uma lista de lugares e seus comentários.
   - **Método HTTP**: `GET`
   - **Endpoint**: `/places`
 
2. **Criar um comentário**  
   - **Descrição**: Envia um novo comentário para um determinado lugar.
   - **Método HTTP**: `POST`
   - **Endpoint**: `/comments`
 
3. **Criar uma denúncia**  
   - **Descrição**: Envia uma denúncia para um comentário considerado inadequado.
   - **Método HTTP**: `POST`
   - **Endpoint**: `/reports`
   - **Tipos de Reporte**: ` 
           SPAM,
           HATE,
           FAKE,
           BULLYING,
           VIOLENCE,
           SCAM,
           SUICIDE,
           DRUG e
           OTHER
     `
 
A documentação completa da API está disponível no Swagger: [Api - Challenge](https://go-tour-bahia.onrender.com/swagger)
 
## Requisitos Técnicos
 
1. **API em .NET**:
   - Desenvolver a API utilizando o framework **.NET**.
   - A API deve consumir corretamente os endpoints fornecidos.
 
2. **Moderação de Comentários com IA**:
   - Escolha e integre uma solução de **IA** que será responsável pela análise dos comentários.
   - A IA deve identificar e reportar comentários impróprios, justificando a denúncia.
 
3. **Denúncia de Comentários**:
   - A API deve automatizar a denúncia de comentários inadequados utilizando o endpoint `/reports`.
 
4. **Repositório no GitHub**:
   - O código deverá ser disponibilizado em um Pull Request neste repositório.
   - Inclua um arquivo **README** ou edite este, com:
     - Instruções detalhadas de como executar o projeto.
     - Descrição da IA utilizada para moderação dos comentários.
     - Detalhes sobre como os endpoints foram utilizados e integrados.
 
## Entrega
 
O repositório no GitHub deve conter:
- Código-fonte da API em **.NET**.
- Arquivo **README** com as instruções de execução e detalhes sobre a IA usada.
- A API não precisa de um **frontend**; o foco é na funcionalidade e moderação.
 
## Critérios de Avaliação
 
1. **Corretude**: A API consome corretamente os endpoints e faz a moderação conforme esperado.
2. **IA para Moderação**: Eficácia e criatividade na escolha da IA para moderar os comentários.
3. **Qualidade do Código**: Boa organização e estrutura do código.
4. **Documentação**: A clareza e qualidade das instruções no **README**.
5. **Boas Práticas**: Implementação de boas práticas de desenvolvimento, como tratamento de erros, modularidade e legibilidade.
 
Boa sorte!
