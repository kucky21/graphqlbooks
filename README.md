Tento projekt je testovací Symfony aplikace, která implementuje GraphQL API pomocí Overblog GraphQLBundle, Doctrine ORM, PostgreSQL, Nginx, PHP-FPM a Docker Compose.

Aplikace splňuje zadání:

Mutace pro vytvoření knihy (autor, název, cena, ukázka, žánr)

Žánr jako ENUM

Validace: nic nesmí být prázdné, cena > 0

GraphiQL UI

http://localhost:8080/graphiql/graphiql

GraphQL endpoint

http://localhost:8080/graphql

Spuštění aplikace

    docker compose up -d --build

Query pro vytvoření knihy: 

    mutation {
  createBook(input:{
    author: "John",
    title: "My First Book",
    price: 299.9,
    excerpt: "Sample text",
    genre: FANTASY
  }) {
    id
    title
  }
}


Query 1: filtrování podle 2 parametrů (autor, žánr)

    query {
  books(author: "John", genre: FANTASY) {
    id
    title
    author
    genre
  }
}


Query 2: detail knihy podle ID

query {
  book(id: 1) {
    id
    title
    author
    price
    genre
  }
}

