#Project Info and API Documentation

###Introduction
The Library Management System is an automated software solution designed to streamline the management of a library. It provides functionalities to add books, their authors and the publishers.

Welcome to the documentation for the API. This API allows you to perform basic operations of library management system.

## Installation

Run the below command to install & run the project

- ######Create a database with the name of 'library'
- ######Composer install
- ######php artisan migrate
- ######php artisan serve

## Getting Started

##### Publish Book 

- **Endpoint:** '/api/publish-book'
- **Method:** POST
- **Request Body:** ```{ "book_title": "Book Name", "book_publication_at": "Book Published Date", "name": ["First Publisher Name", "Second Publisher Name"], "author_name": ["First Author Name", "Second Author Name"]}```

### Author Module

##### Create
- **Endpoint:** '/api/author'
- **Method:** POST
- **Request Body:** ```{ "name": "Author Name" }```

##### List
- **Endpoint:** '/api/author'
- **Method:** GET
- **Request Body:** ```{  }```

##### Read
- **Endpoint:** '/api/author/:id'
- **Method:** GET
- **Request Body:** ```{  }```

##### Update
- **Endpoint:** '/api/author/:id'
- **Method:** PUT|PATCH
- **Request Body:** ```{ "name": "Updated Author Name" }```

##### Delete
- **Endpoint:** '/api/author/:id'
- **Method:** DELETE
- **Request Body:** ```{  }```

### Book Module

##### Create
- **Endpoint:** '/api/book'
- **Method:** POST
- **Request Body:** ```{ "title": "Book Title", "publication_at": "2023-12-01" }```

##### List
- **Endpoint:** '/api/book'
- **Method:** GET
- **Request Body:** ```{  }```

##### Read
- **Endpoint:** '/api/book/:id'
- **Method:** GET
- **Request Body:** ```{  }```

##### Update
- **Endpoint:** '/api/book/:id'
- **Method:** PUT|PATCH
- **Request Body:** ```{ "title": "Updated Book Title" }```

##### Delete
- **Endpoint:** '/api/book/:id'
- **Method:** DELETE
- **Request Body:** ```{  }```

### Publisher Module

##### Create
- **Endpoint:** '/api/publisher'
- **Method:** POST
- **Request Body:** ```{ "name": "Publisher Name" }```

##### List
- **Endpoint:** '/api/publisher'
- **Method:** GET
- **Request Body:** ```{  }```

##### Read
- **Endpoint:** '/api/publisher/:id'
- **Method:** GET
- **Request Body:** ```{  }```

##### Update
- **Endpoint:** '/api/publisher/:id'
- **Method:** PUT|PATCH
- **Request Body:** ```{ "name": "Updated Publisher Name" }```

##### Delete
- **Endpoint:** '/api/publisher/:id'
- **Method:** DELETE
- **Request Body:** ```{  }```
