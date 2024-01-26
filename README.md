# Documentação da API - Laravel

A documentação a seguir descreve as rotas da API Laravel, organizadas em módulos, para um sistema que envolve autenticação, gerenciamento de usuários, operações de estoque e pedidos.

**Versão da API: v1**

---

## Autenticação

### 1. Login
Realiza a autenticação do usuário e fornece um token JWT.

- **URL**: `/v1/authentication/login`
- **Método**: `POST`
- **Parâmetros do corpo da requisição**:
  - `email` (string): E-mail do usuário.
  - `password` (string): Senha do usuário.
- **Retorno bem-sucedido**:
  - Status: 200 OK
  - Corpo: Token JWT válido.

### 2. Logout
Realiza o logout do usuário, invalidando o token JWT.

- **URL**: `/v1/authentication/logout`
- **Método**: `POST`
- **Cabeçalhos**:
  - `Authorization`: Bearer [Token JWT]
- **Retorno bem-sucedido**:
  - Status: 200 OK
  - Corpo: Mensagem de logout bem-sucedido.

### 3. Verificar Token
Verifica se o token JWT fornecido é válido.

- **URL**: `/v1/authentication/check`
- **Método**: `POST`
- **Cabeçalhos**:
  - `Authorization`: Bearer [Token JWT]
- **Retorno bem-sucedido**:
  - Status: 200 OK
  - Corpo: Mensagem indicando que o token é válido.

### 4. Primeiro Acesso (Apenas com Token)
Verifica se o usuário está acessando o sistema pela primeira vez após o login.

- **URL**: `/v1/authentication/first`
- **Método**: `POST`
- **Cabeçalhos**:
  - `Authorization`: Bearer [Token JWT]
- **Retorno bem-sucedido**:
  - Status: 200 OK
  - Corpo: Mensagem indicando o status do primeiro acesso.

---

## Módulo de Administrador

### 5. Listar Usuários
Obtém uma lista de todos os usuários.

- **URL**: `/v1/administrator/search`
- **Método**: `GET`
- **Cabeçalhos**:
  - `Authorization`: Bearer [Token JWT]
- **Retorno bem-sucedido**:
  - Status: 200 OK
  - Corpo: Lista de usuários.

### 6. Registrar Usuário
Registra um novo usuário no sistema.

- **URL**: `/v1/administrator/registration`
- **Método**: `POST`
- **Cabeçalhos**:
  - `Authorization`: Bearer [Token JWT]
- **Parâmetros do corpo da requisição**:
  - `name` (string): Nome do usuário.
  - `email` (string): E-mail do usuário.
  - `password` (string): Senha do usuário.
- **Retorno bem-sucedido**:
  - Status: 200 OK
  - Corpo: Mensagem indicando o sucesso do registro.

### 7. Desativar Usuário
Desativa um usuário existente.

- **URL**: `/v1/administrator/deactivation/{id}`
- **Método**: `PUT`
- **Cabeçalhos**:
  - `Authorization`: Bearer [Token JWT]
- **Parâmetros da URL**:
  - `id` (int): ID do usuário a ser desativado.
- **Retorno bem-sucedido**:
  - Status: 200 OK
  - Corpo: Mensagem indicando a desativação bem-sucedida.

### 8. Ativar Usuário
Ativa um usuário desativado.

- **URL**: `/v1/administrator/activate/{id}`
- **Método**: `PUT`
- **Cabeçalhos**:
  - `Authorization`: Bearer [Token JWT]
- **Parâmetros da URL**:
  - `id` (int): ID do usuário a ser ativado.
- **Retorno bem-sucedido**:
  - Status: 200 OK
  - Corpo: Mensagem indicando a ativação bem-sucedida.

---

## Módulo de Almoxarifado

### 9. Listar Produtos no Estoque
Obtém uma lista de todos os produtos no estoque.

- **URL**: `/v1/stock/search`
- **Método**: `GET`
- **Cabeçalhos**:
  - `Authorization`: Bearer [Token JWT]
- **Retorno bem-sucedido**:
  - Status: 200 OK
  - Corpo: Lista de produtos no estoque.

### 10. Meu Estoque
Obtém uma lista de produtos no estoque do usuário autenticado.

- **URL**: `/v1/stock/mystock/{id}`
- **Método**: `GET`
- **Cabeçalhos**:
  - `Authorization`: Bearer [Token JWT]
- **Parâmetros da URL**:
  - `id` (int): ID do usuário autenticado.
- **Retorno bem-sucedido**:
  - Status: 200 OK
  - Corpo: Lista de produtos no estoque do usuário.

### 11. Listar Categorias
Obtém uma lista de todas as categorias de produtos no estoque.

- **URL**: `/v1/stock/category`
- **Método**: `GET`
- **Cabeçalhos**:
  - `Authorization`: Bearer [Token JWT]
- **Retorno bem-sucedido**:
  - Status: 200 OK
  - Corpo: Lista de categorias.

### 12. Registrar Produto
Registra um novo produto no estoque.

- **URL**: `/v1/stock/registration`
- **Método**: `POST`
- **Cabeçalhos**:
  - `Authorization`: Bearer [Token JWT]
- **Parâmetros do corpo da requisição**:
  - `name` (string): Nome do produto.
  - `quantity` (int): Quantidade do produto.
  - `category_id` (int): ID da categoria do produto.
- **Retorno bem-sucedido**:
  - Status: 200 OK
  - Corpo: Mensagem indicando o sucesso do registro.

### 13. Filtrar Produtos (Aprovação)
Filtra produtos no estoque que requerem aprovação.

- **URL**: `/v1/stock/filter/{id}`
- **Método**: `POST`
- **Cabeçalhos**:
  - `Authorization`: Bearer [Token JWT]
- **Parâmetros da URL**:
  - `id` (int): ID do usuário autenticado.
- **Parâmetros do corpo da requisição**:
  - `approval_status` (boolean): Status de aprovação.
- **Retorno bem-sucedido**:
  - Status: 200 OK
  - Corpo: Lista de produtos filtrados.

### 14. Aprovar Produto
Aprova a inclusão de um produto no estoque.

- **URL**: `/v1/stock/approval/{id}`
- **Método**: `PUT`
- **Cabeçalhos**:
  - `Authorization`: Bearer [Token JWT]
- **Parâmetros da URL**:
  - `id` (int): ID do produto a ser aprovado.
- **Retorno bem-sucedido**:
  - Status: 200 OK
  - Corpo: Mensagem indicando a aprovação bem-sucedida.

### 15. Reprovar Produto
Reprova a inclusão de um produto no estoque.

- **URL**: `/v1/stock/disapprove/{id}`
- **Método**: `PUT`
- **Cabeçalhos**:
  - `Authorization`: Bearer [Token JWT]
- **Parâmetros da URL**:
  - `id` (int): ID do produto a ser reprovado.
- **Retorno bem-sucedido**:
  - Status: 200 OK
  - Corpo: Mensagem indicando a reprovação bem-sucedida.

---

## Módulo de Pedidos

### 16. Buscar Pedidos
Busca pedidos com base no ID do usuário.

- **URL**: `/v1/requests/search/{id}`
- **Método**: `GET`
- **Cabeçalhos**:
  - `Authorization`: Bearer [Token JWT]
- **Parâmetros da URL**:
  - `id` (int): ID do usuário autenticado.
- **Retorno bem-sucedido**:
  - Status: 200 OK
  - Corpo: Lista de pedidos.

### 17. Buscar Todos os Pedidos (Armazém)
Busca todos os pedidos para gerenciamento de armazém.

- **URL**: `/v1/requests/searchAll/{id}`
- **Método**: `GET`
- **Cabeçalhos**:
  - `Authorization`: Bearer [Token JWT]
- **Parâmetros da URL**:
  - `id` (int): ID do usuário autenticado.
- **Retorno bem-sucedido**:
  - Status: 200 OK
  - Corpo: Lista de todos os pedidos para gerenciamento de armazém.

### 18. Obter Produtos do Pedido
Obtém a lista de produtos de um pedido específico.

- **URL**: `/v1/requests/products/{id}`
- **Método**: `GET`
- **Cabeçalhos**:
  - `Authorization`: Bearer [Token JWT]
- **Parâmetros da URL**:
  - `id` (int): ID do pedido.
- **Retorno bem-sucedido**:
  - Status: 200 OK
  - Corpo: Lista de produtos do pedido.

### 19. Registrar Pedido
Registra um novo pedido no sistema.

- **URL**: `/v1/requests/registration`
- **Método**: `POST`
- **Cabeçalhos**:
  - `Authorization`: Bearer [Token JWT]
- **Parâmetros do corpo da requisição**:
  - `user_id` (int): ID do usuário que fez o pedido.
  - `products` (array): Lista de produtos no pedido.
- **Retorno bem-sucedido**:
  - Status: 200 OK
  - Corpo: Mensagem indicando o sucesso do registro.

---

**Observações:**
- Todas as rotas são prefixadas com `/v1`.
- A autenticação JWT é necessária para acessar as rotas protegidas.
- Alguns endpoints possuem validações adicionais, como validação de ID e status de aprovação/disaprovação.
- Certifique-se de incluir o token JWT válido nos cabeçalhos das requisições protegidas.

Esta documentação fornece uma visão geral das principais operações da API. Certifique-se de consultar a lógica de negócios em seus controladores para obter detalhes específicos sobre a implementação.
