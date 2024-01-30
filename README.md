
# Laravel MamiKos Test API



![Logo](https://guidelines.mamikos.com/images/logo/mamikos-logo.png)


## Features

- Authentication
  - Register as regular user, premium user, owner.
  - Login as regular user, premium user, owner.
  - Logout
- CRUD Kost / Room.
- Search Kost / Room by : name, location and price
- Sorting Kost / Room by price : asc / des
- Create and view room discussion. 


## Documentation

- [Design Database](https://github.com/OxiCuza/laravel-test/tree/master/documentation) or inside folder documentation


## Requirement 

```
- PHP 8.1 or newest
- MySQL or MariaDB
- Composer 2.1
```


## Run Locally

Clone the project

```bash
  git clone https://github.com/OxiCuza/laravel-test.git
```

Go to the project directory
```bash
  cd laravel-test
```

Install composer dependencies
```bash
  composer install
```

Copy **.env.example** as **.env**

```bash
  cp .env.example .env // or just copy manually via file manager
```

Edit database configuration in file **.env**

```bash
  DB_CONNECTION=mysql
  DB_HOST=fill_with_your_db_host
  DB_PORT=fill_with_your_db_port
  DB_DATABASE=fill_with_your_name_db
  DB_USERNAME=fill_with_your_username_db
  DB_PASSWORD=fill_with_your_password_db
```

Generate Key PHP

```bash
  php artisan key:generate
```

Run migration and seeding

```bash
  php artisan migrate --seed
```

You will have data master of role

| id | name     |
| :-------- | :------- |
| `1`      | `PREMIUM` |
| `2`      | `OWNER` |
| `3`      | `REGULAR` |

Run Laravel

```bash
  php artisan serve
```

If you need run scheduled command for recharge credit user :

```bash
  php artisan app:credit-recharge
```
**OR**

```bash
  php artisan scheduled:test
```

## API Reference

### Register

```http
  POST /api/v1/auth/register
```

**Body Parameters :**
| parameter | type     | example                |
| :-------- | :------- | :------------------------- |
| `name` | `string` | oxicusa |
| `email` | `email` | oxicusa@gmail.com |
| `password` | `string` | passwordpassword |
| `password_confirmation` | `string` | passwordpassword |
| `role` | `string` | 1 (as user PREMIUM) |

**Example Responses :**

- Code: `200`
```
{
    "status": true,
    "message": "OK!",
    "data": {
        "id": "9b34d729-a8e2-4d25-b208-712e028653bc",
        "name": "oxicusa",
        "email": "oxicusa@gmail.com",
        "credit": 40
    }
}
```

- Code: `400`
```
{
    "success": false,
    "message": "Validation errors",
    "data": {
        "password": [
            "The password field confirmation does not match."
        ]
    }
}
```

### Login

```http
  POST /api/v1/auth/login
```

**Body Parameters :**
| name | type     | example                       |
| :-------- | :------- | :-------------------------------- |
| `email`      | `email` | oxicusa@gmail.com |
| `password`      | `string` | passwordpassword |

**Example Responses :**
- Code: `200`
```
{
    "status": true,
    "message": "OK!",
    "data": {
        "id": "9b3386b6-16b8-4cb5-86c3-0714cec438ff",
        "token": "34|tuhcXt6KiDnkJKPfjpckeaQABSa50kRSTxalolbsac482a8b",
        "name": "oxicusa",
        "email": "oxicusa@gmail.com",
        "credit": 40
    }
}
```

- Code: `400`
```
{
    "status": false,
    "message": "Invalid credentials",
    "data": null
}
```

### Logout

```http
  POST /api/v1/auth/logout
```

**Header Parameters :**
| name | type     | example                       |
| :-------- | :------- | :-------------------------------- |
| `Authorization`      | `string` | Bearer 33cQEHAWhmp3sZxJnszgdbAK4lVoF8AvGLpFZL0kewbfb204cd |

**Example Responses :**
- Code: `200`
```
{
    "status": true,
    "message": "OK!",
    "data": null
}
```

### Create Kost (only Owner)

```http
  POST /api/v1/rooms
```

**Header Parameters :**
| name | type     | example                       |
| :-------- | :------- | :-------------------------------- |
| `Authorization`      | `string` | Bearer 33cQEHAWhmp3sZxJnszgdbAK4lVoF8AvGLpFZL0kewbfb204cd |

**Body Parameters :**
| name | type     | example                       |
| :-------- | :------- | :-------------------------------- |
| `name`      | `string` | room 1 |
| `price`      | `integer` | 1000 |
| `location`      | `string` | Jawa Timur |
| `details`      | `array of object` | [ { "name": "K. Mandi Dalam" } ] |

**Example Responses :**
- Code: `200`
```
{
    "status": true,
    "message": "OK!",
    "data": {
        "id": "9b3691fb-01e6-4ca1-ab09-acdbcdf75dbe",
        "name": "room 5",
        "location": "malang",
        "price": 2000,
        "created_at": "2024-01-30T03:53:16.000000Z"
    }
}
```

- Code: `400`
```
{
    "success": false,
    "message": "Validation errors",
    "data": {
        "details": [
            "The details field is required."
        ]
    }
}
```

- Code: `401`
```
{
    "status": false,
    "message": "Unauthorized",
    "data": null
}
```

### Update Kost (only Owner)

```http
  PUT /api/v1/rooms
```

**Header Parameters :**
| name | type     | example                       |
| :-------- | :------- | :-------------------------------- |
| `Authorization`      | `string` | Bearer 33cQEHAWhmp3sZxJnszgdbAK4lVoF8AvGLpFZL0kewbfb204cd |

**Body Parameters :**
| name | type     | example                       |
| :-------- | :------- | :-------------------------------- |
| `name`      | `string` | room 2 |
| `price`      | `number` | 15000 |
| `location`      | `string` | Jawa Tengah |
| `details`      | `array of object` | [ { "id": "9b3691fb-04ec-4cde-980a-d16c1a6272f1", "name": "K. Mandi Dalam" } ] |

**Example Responses :**
- Code: `200`
```
{
    "status": true,
    "message": "OK!",
    "data": {
        "id": "9b3691fb-01e6-4ca1-ab09-acdbcdf75dbe",
        "name": "room 2",
        "location": "Jawa Tengah",
        "price": 15000,
        "created_at": "2024-01-30T03:53:16.000000Z",
    }
}
```

- Code: `400`
```
{
    "success": false,
    "message": "Validation errors",
    "data": {
        "name": [
            "attribute name is required"
        ]
    }
}
```

### Delete Kost (only Owner)

```http
  DELETE /api/v1/rooms/{id}
```

**Header Parameters :**
| name | type     | example                       |
| :-------- | :------- | :-------------------------------- |
| `Authorization`      | `string` | Bearer 33cQEHAWhmp3sZxJnszgdbAK4lVoF8AvGLpFZL0kewbfb204cd |

**Parameters :**
| name | type     | example                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | 9b3691fb-01e6-4ca1-ab09-acdbcdf75dbe |

**Example Responses :**
- Code: `200`
```
{
    "status": true,
    "message": "OK!",
    "data": null
}
```

- Code: `404`
```
{
    "status": false,
    "message": "Kost / Room Not Found",
    "data": null
}
```

### Show All Kost (By Owner ID / User Premium and Regular)

```http
  GET /api/v1/rooms
```

**Header Parameters :**
| name | type     | example                       |
| :-------- | :------- | :-------------------------------- |
| `Authorization`      | `string` | Bearer 33cQEHAWhmp3sZxJnszgdbAK4lVoF8AvGLpFZL0kewbfb204cd |

**Query Parameters :**
| name | type     | example                       |
| :-------- | :------- | :-------------------------------- |
| `name`      | `string` | room 5 |
| `location`      | `string` | pasuruan |
| `price`      | `integer` | 2000 |
| `sort`      | `string` | desc |

**Example Responses :**
- Code: `200`
```
{
    "status": true,
    "message": "OK!",
    "data": [
        {
            "id": "9b34e0d8-dbd0-4534-8df9-916e50175e82",
            "name": "room 5",
            "location": "pasuruan",
            "price": 2000,
            "created_at": "2024-01-29T07:42:09.000000Z",
            "facilities": [
                {
                    "id": "91d6acb2-be88-11ee-96c0-0242ac120003",
                    "name": "Kulkas"
                },
                {
                    "id": "91d6b6b6-be88-11ee-96c0-0242ac120003",
                    "name": "K. Mandi Dalam"
                }
            ]
        }
    ],
    "links": {
        "first": "http://example.test/api/v1/rooms?page=1",
        "last": "http://example.test/api/v1/rooms?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http://example.test/api/v1/rooms?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "path": "http://example.test/api/v1/rooms",
        "per_page": 10,
        "to": 7,
        "total": 7
    }
}
```

### Show Detail Kost (By Owner ID / User Premium and Regular)

```http
  GET /api/v1/rooms/{id}
```

**Header Parameters :**
| name | type     | example                       |
| :-------- | :------- | :-------------------------------- |
| `Authorization`      | `string` | Bearer 33cQEHAWhmp3sZxJnszgdbAK4lVoF8AvGLpFZL0kewbfb204cd |

**Header Parameters :**
| name | type     | example                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | 9b34e0d8-dbd0-4534-8df9-916e50175e82 |

**Example Responses :**
- Code: `200`
```
{
    "status": true,
    "message": "OK!",
    "data": {
        "id": "9b34e0d8-dbd0-4534-8df9-916e50175e82",
        "name": "room 5",
        "location": "pasuruan",
        "price": 2000,
        "created_at": "2024-01-29T07:42:09.000000Z",
        "owner": {
            "id": "9b34cf89-a473-4098-abad-84297a159fe7",
            "name": "oxicusa",
            "email": "goxicuza@gmail.com",
            "credit": 0
        },
        "facilities": [
            {
                "id": "91d6acb2-be88-11ee-96c0-0242ac120003",
                "name": "Kulkas"
            },
            {
                "id": "91d6b6b6-be88-11ee-96c0-0242ac120003",
                "name": "K. Mandi Dalam"
            }
        ],
        "discussions": []
    }
}
```

- Code: `403`
```
{
    "status": false,
    "message": "You are not the owner of this room.",
    "data": null
}
```

### Ask / Discussion About Kost / Room

```http
  POST /api/v1/rooms/{id}/discussions
```

**Header Parameters :**
| name | type     | description                       |
| :-------- | :------- | :-------------------------------- |
| `Authorization`      | `string` | Bearer 33cQEHAWhmp3sZxJnszgdbAK4lVoF8AvGLpFZL0kewbfb204cd |

**Parameters :**
| name | type     | description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | 9b34e0d8-dbd0-4534-8df9-916e50175e82 |

**Body Parameters :**
| name | type     | description                       |
| :-------- | :------- | :-------------------------------- |
| `message`      | `string` | coba message dari user |

**Example Responses :**
- Code: `200`
```
{
    "status": true,
    "message": "OK!",
    "data": {
        "id": "9b3564c9-76e9-46da-a31b-15ad0b997878",
        "message": "coba message dari user",
        "created_at": "2024-01-29T13:51:04.000000Z"
    }
}
```

### List Discussion By Kost ID

```http
  GET /api/v1/rooms/{id}/discussions
```

**Header Parameters :**
| name | type     | description                       |
| :-------- | :------- | :-------------------------------- |
| `Authorization`      | `string` | Bearer 33cQEHAWhmp3sZxJnszgdbAK4lVoF8AvGLpFZL0kewbfb204cd |

**Parameters :**
| name | type     | description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | 9b34e0d8-dbd0-4534-8df9-916e50175e82 |

**Example Responses :**
- Code: `200`
```
{
    "status": true,
    "message": "OK!",
    "data": [
        {
            "id": "9b3564c9-76e9-46da-a31b-15ad0b997878",
            "message": "coba message dari user",
            "created_at": "2024-01-29T13:51:04.000000Z",
            "user": {
                "id": "9b34cf89-a473-4098-abad-84297a159fe7",
                "name": "oxicusa",
                "email": "oxicusa@gmail.com",
                "credit": 40
            }
        }
    ]
}
```

## Feedback

If you have any feedback, please reach out to me at oxicusa@gmail.com


## Authors

- Oxicusa Gugi Housman. - [@OxiCuza](https://github.com/OxiCuza)
