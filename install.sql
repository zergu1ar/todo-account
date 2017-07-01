CREATE TABLE todo.users (
          `id`          INT  NOT NULL AUTO_INCREMENT  PRIMARY KEY,
          `login`       VARCHAR(200) NOT NULL,
          `password`    VARCHAR(100) NOT NULL,
          `created`     DATETIME NOT NULL,
          `updated`     DATETIME NOT NULL
);
