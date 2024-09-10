Todo API Documentation
======================

Toto API slouží ke správě "todo" položek. Podporuje operace jako vytvoření, úprava, mazání a načítání jednotlivých nebo všech položek.

## Formát dat

Všechny požadavky a odpovědi používají formát JSON.

### Struktura Todo objektu
```json
{
  "id": 1,
  "title": "Buy groceries",
  "description": "Milk, Bread, Butter",
  "status": "pending"
}
```

| Pole        | Typ    | Popis                                    |
|-------------|--------|------------------------------------------|
| `id`        | int    | Unikátní identifikátor položky           |
| `title`     | string | Název položky                            |
| `description`| string| Popis položky                            |
| `status` | string   | Stav dokončení položky (`pending`, `completed`)  |

## API Endpoints

### 1. Vytvoření nové položky
#### Endpoint
```http
POST /todo
```
#### Tělo požadavku
```json
{
  "title": "Buy groceries",
  "description": "Milk, Bread, Butter",
  "status": "completed"
}
```
#### Odpověď
```json
{
  "id": 1,
  "title": "Buy groceries",
  "description": "Milk, Bread, Butter",
  "status": "completed"
}
```

### 2. Získání jedné položky podle ID
#### Endpoint
```http
GET /todo/{id}
```
#### Příklad požadavku
```http
GET /todo/1
```
#### Odpověď
```json
{
  "id": 1,
  "title": "Buy groceries",
  "description": "Milk, Bread, Butter",
  "completed": false
}
```

### 3. Vypsání všech položek
#### Endpoint
```http
GET /todo
```
#### Odpověď
```json
[
  {
    "id": 1,
    "title": "Buy groceries",
    "description": "Milk, Bread, Butter",
    "status": "pending"
  },
  {
    "id": 2,
    "title": "Go jogging",
    "description": "Run 5 kilometers",
    "status": "completed"
  }
]
```

### 4. Editace existující položky
#### Endpoint
```http
PUT /todo/{id}
```
#### Tělo požadavku
```json
{
  "title": "Buy groceries and fruits",
  "description": "Milk, Bread, Butter, Apples",
  "status": "completed"
}
```
#### Odpověď
```json
{
  "id": 1,
  "title": "Buy groceries and fruits",
  "description": "Milk, Bread, Butter, Apples",
  "status": "completed"
}
```

### 5. Smazání jedné položky
#### Endpoint
```http
DELETE /todo/{id}
```
#### Příklad požadavku
```http
DELETE /todo/1
```
#### Odpověď
Žádná, status `204 Created`.

---

### HTTP Status Kódy
- `200 OK`: Operace byla úspěšná.
- `201 Created`: Položka byla úspěšně vytvořena.
- `204 NoContent`: Úspěch, žádné další informace.
- `400 Bad Request`: Špatný formát požadavku.
- `404 Not Found`: Položka nebyla nalezena.
- `500 Internal Server Error`: Nastala chyba na serveru.

## Příklad Použití s cURL

1. **Vytvoření nové položky:**
   ```bash
   curl -X POST http://localhost:3000/todo \
   -H "Content-Type: application/json" \
   -d '{"title": "Buy groceries", "description": "Milk, Bread, Butter", "status": "completed"}'
   ```

2. **Získání jedné položky podle ID:**
   ```bash
   curl -X GET http://localhost:3000/todo/1
   ```

3. **Vypsání všech položek:**
   ```bash
   curl -X GET http://localhost:3000/todo
   ```

4. **Editace existující položky:**
   ```bash
   curl -X PUT http://localhost:3000/todo/1 \
   -H "Content-Type: application/json" \
   -d '{"title": "Buy groceries and fruits", "description": "Milk, Bread, Butter, Apples", "status": "completed"}'
   ```

5. **Smazání jedné položky:**
   ```bash
   curl -X DELETE http://localhost:3000/todo/1
   ```

---

Tímto způsobem je možno provádět základní CRUD operace s Todo API.
