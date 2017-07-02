CREATE TABLE todo.users (
          `id`          INT  NOT NULL AUTO_INCREMENT  PRIMARY KEY,
          `login`       VARCHAR(200) NOT NULL,
          `password`    VARCHAR(100) NOT NULL,
          `created`     DATETIME NOT NULL,
          `updated`     DATETIME NOT NULL
);

CREATE TABLE todo.todoList (
          `id`          INT  NOT NULL AUTO_INCREMENT  PRIMARY KEY,
          `name`        VARCHAR(200) NOT NULL,
          `ownerId`     INT NOT NULL,
          `created`     DATETIME NOT NULL,
          `updated`     DATETIME NOT NULL
);

CREATE TABLE todo.todoTask (
          `id`          INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `name`        VARCHAR(200) NOT NULL,
          `listId`      INT NOT NULL,
          `created`     DATETIME NOT NULL,
          `updated`     DATETIME NOT NULL
);

CREATE TABLE todo.todoLink (
          `listId`      INT NOT NULL,
          `userId`      INT NOT NULL,
          `permission`  INT NOT NULL,
          `created`     DATETIME NOT NULL,
          `updated`     DATETIME NOT NULL
);

