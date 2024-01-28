
## API Reference

### Register

```http
  POST /api/v1/auth/register
```

**Body Parameters :**
| parameter | type     | description                |
| :-------- | :------- | :------------------------- |
| `name` | `string` | **required**. |
| `email` | `email` | **required**. |
| `password` | `string` | **required**. |
| `password_confirmation` | `string` | **required**. |
| `role` | `string` | **required**. |

**Example Responses :**

- Code: `200`
```
{
  "status": true,
  "message": "OK!",
  "data": {
    "token": "example token"
  }
}
```

- Code: `400`
```
{
  "status": true,
  "message": "Password confirmation not match!",
}
```

### Login

```http
  POST /api/v1/auth/login
```

**Body Parameters :**
| name | type     | description                       |
| :-------- | :------- | :-------------------------------- |
| `email`      | `email` | **required**. |
| `password`      | `string` | **required**. |

**Example Responses :**
- Code: `200`
```
{
  "status": true,
  "message": "OK!",
  "data": {
    "token": "example token"
  }
}
```

- Code: `400`
```
{
  "status": true,
  "message": "Invalid credentials!",
}
```

### Logout

```http
  POST /api/v1/auth/logout
```

**Header Parameters :**
| name | type     | description                       |
| :-------- | :------- | :-------------------------------- |
| `Authorization`      | `string` | **required**. |

**Example Responses :**
- Code: `200`
```
{
  "status": true,
  "message": "OK!"
}
```

### Create Kost

```http
  POST /api/v1/owner/kost
```

**Header Parameters :**
| name | type     | description                       |
| :-------- | :------- | :-------------------------------- |
| `Authorization`      | `string` | **required**. |

**Body Parameters :**
| name | type     | description                       |
| :-------- | :------- | :-------------------------------- |
| `name`      | `string` | **required**. |
| `price`      | `number` | **required**. |
| `location`      | `string` | **required**. |
| `image`      | `file` | - |

**Example Responses :**
- Code: `200`
```
{
  "status": true,
  "message": "OK!",
  "data": {
    "name": "example name of kost",
    "price": 1000,
    "location": "East Java",
    "image": "http://localhost/default-image.jpg"
  }
}
```

- Code: `400`
```
{
  "status": false,
  "message": [
    "name": "attribute name is required"
  ],
```

- Code: `403`
```
{
  "status": false,
  "message": "Forbidden",
}
```

### Update Kost

```http
  PUT /api/v1/owner/kost
```

**Header Parameters :**
| name | type     | description                       |
| :-------- | :------- | :-------------------------------- |
| `Authorization`      | `string` | **required**. |

**Body Parameters :**
| name | type     | description                       |
| :-------- | :------- | :-------------------------------- |
| `name`      | `string` | **required**. |
| `price`      | `number` | **required**. |
| `location`      | `string` | **required**. |
| `image`      | `file` | - |

**Example Responses :**
- Code: `200`
```
{
  "status": true,
  "message": "OK!",
  "data": {
    "name": "example update name of kost",
    "price": 2000,
    "location": "Surabaya",
    "image": "http://localhost/default-image.jpg"
  }
}
```

- Code: `400`
```
{
  "status": false,
  "message": {
    "name": "attribute name is required"
  }
}
```

- Code: `403`
```
{
  "status": false,
  "message": "Forbidden",
}
```

### Delete Kost

```http
  DELETE /api/v1/kost/{id}
```

**Header Parameters :**
| name | type     | description                       |
| :-------- | :------- | :-------------------------------- |
| `Authorization`      | `string` | **required**. |

**Parameters :**
| name | type     | description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | **required**. |

**Example Responses :**
- Code: `200`
```
{
  "status": true,
  "message": "OK!"
}
```

- Code: `400`
```
{
  "status": false,
  "message": "kost not found",
}
```

- Code: `403`
```
{
  "status": false,
  "message": "Forbidden",
}
```

### Show All Kost By Owner ID

```http
  GET /api/v1/kost
```

**Header Parameters :**
| name | type     | description                       |
| :-------- | :------- | :-------------------------------- |
| `Authorization`      | `string` | **required**. |

**Example Responses :**
- Code: `200`
```
{
  "status": true,
  "message": "OK!",
  "data": [
    {
      "name": "example name of kost",
      "price": 2000,
      "location": "Surabaya",
      "image": "http://localhost/default-image.jpg"
    },
    {
      "name": "example name of kost 2",
      "price": 1000,
      "location": "Jakarta",
      "image": "http://localhost/default-image.jpg"
    }
  ]
}
```

- Code: `400`
```
{
  "status": false,
  "message": "Bad Request",
}
```

- Code: `401`
```
{
  "status": false,
  "message": "Unauthorized User",
}
```

### Show All Kost

```http
  GET /api/v1/kost
```

**Header Parameters :**
| name | type     | description                       |
| :-------- | :------- | :-------------------------------- |
| `Authorization`      | `string` | **required**. |

**Example Responses :**
- Code: `200`
```
{
  "status": true,
  "message": "OK!",
  "data": [
    {
      "name": "example name of kost",
      "price": 2000,
      "location": "Surabaya",
      "image": "http://localhost/default-image.jpg"
    },
    {
      "name": "example name of kost 2",
      "price": 1000,
      "location": "Jakarta",
      "image": "http://localhost/default-image.jpg"
    }
  ]
}
```

- Code: `400`
```
{
  "status": false,
  "message": "Bad Request",
}
```

- Code: `401`
```
{
  "status": false,
  "message": "Unauthorized User",
}
```

### Show Detail Kost

```http
  GET /api/v1/kost/{id}
```

**Header Parameters :**
| name | type     | description                       |
| :-------- | :------- | :-------------------------------- |
| `Authorization`      | `string` | **required**. |

**Header Parameters :**
| name | type     | description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | **required**. |

**Example Responses :**
- Code: `200`
```
{
  "status": true,
  "message": "OK!",
  "data": [
    {
      "name": "example name of kost",
      "price": 2000,
      "location": "Surabaya",
      "image": "http://localhost/default-image.jpg",
      "facility": [
        "K. Mandi", 
        "AC"
      ],
      "discussion": [
        {
          "id": "random-string",
          "user_id: "random-string",
          "message": "content"
        }
      ]
    },
  ]
}
```

- Code: `400`
```
{
  "status": false,
  "message": "Bad Request",
}
```

- Code: `401`
```
{
  "status": false,
  "message": "Unauthorized User",
}
```

### Ask / Discussion About Kost

```http
  POST /api/v1/kost/{id}/discussion
```

**Header Parameters :**
| name | type     | description                       |
| :-------- | :------- | :-------------------------------- |
| `Authorization`      | `string` | **required**. |

**Parameters :**
| name | type     | description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | **required**. |

**Body Parameters :**
| name | type     | description                       |
| :-------- | :------- | :-------------------------------- |
| `message`      | `string` | **required**. |

**Example Responses :**
- Code: `200`
```
{
  "status": true,
  "message": "OK!",
  "data": {
    "id": "random-string",
    "user_id: "random-string",
    "message": "content"
  }
}
```

- Code: `400`
```
{
  "status": false,
  "message": "Bad Request",
}
```

- Code: `401`
```
{
  "status": false,
  "message": "Unauthorized User",
}
```
