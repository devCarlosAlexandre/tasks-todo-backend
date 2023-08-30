# Api John Task List 👽
## Presentaio video
https://www.youtube.com/watch?v=sMFhpgi1HNs
### Story
João is tired of forgetting his tasks that he need to do daily. Your goal is to create a
tool that helps João to solve his problem in a simple way.

### Solution
Implemented a RESTful API Laravel with docker for a task management application (to-do list).
### Acess aplication in url 💻: 
- <a href="http://137.184.125.187:8989/api/tasks"> --> Acess project production <-- </a> <br>
- <a href="http://137.184.125.187:8989/request-docs"> --> Documentation <-- </a>
### Features

- [x] Task:
   - [x] Create
   - [x] Update
   - [x] Read
   - [x] Delete
   - [x] Restore
- [x] User:
   - [x] Create
   - [x] Update
   - [x] Read
   - [x] Delete
- [x] Auth:
   - [x] Login
   - [x] Register
   - [x] Logout
   - [x] Validate Token
    
## Presentation project: 

## Technologies utilized✨🚀: 

<img align="" alt="devCarlosAlexandre-Flutter" height="250"  src="https://github.com/devCarlosAlexandre/tasks-todo-backend/assets/63679873/81c464bf-9296-4401-a74d-d8c1bb1060e8">
<img align="" alt="devCarlosAlexandre-Flutter" height="250"   src="https://github.com/devCarlosAlexandre/tasks-todo-backend/assets/63679873/7305e130-78e2-4644-8e4a-e648b7dc4fe6">


# ER diagram 📃
``` mermaid
erDiagram
    USER {
        int id
        string nome
        string email
        string password
        string token
        date created_at
        date updated_at
    }
    
    TASK {
        int id
        int user_id
        string title
        string description
        enum status
        date date_done
        bolean active
        date created_at
        date updated_at
    }

    ATTACHMENTS{
    int id
    string path
    int task_id
    int user_id
    date created_at
    date updated_at
    }
    
    USER ||--o{ ATTACHMENTS : "have many"
    TASK ||--o{ ATTACHMENTS : "have many"
    USER ||--o{ TASK : "have many"
```

## Steps run project wih docker🚢: 
Upload the project containers
```sh
docker-compose up -d
```
Access the container
```sh
docker-compose exec app bash
```
Install depency the project
```sh

composer install
```

Generete key
```sh
php artisan key:generate
```

### Author
---

