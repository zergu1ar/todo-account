CREATE TABLE todo.users (
          `id`          INT  NOT NULL AUTO_INCREMENT  PRIMARY KEY,
          `login`       VARCHAR(200) NOT NULL,
          `password`    VARCHAR(100) NOT NULL,
          `created`     DATETIME NOT NULL,
          `updated`     DATETIME NOT NULL
);

ALTER TABLE `users` ADD INDEX(`login`);
ALTER TABLE `users` ADD INDEX(`login`, `password`);

CREATE TABLE todo.todoTask (
          `id`          INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `name`        VARCHAR(200) NOT NULL,
          `completed`   TINYINT(1) DEFAULT 0 NOT NULL,
          `ownerId`     INT NOT NULL,
          `created`     DATETIME NOT NULL,
          `updated`     DATETIME NOT NULL
);
ALTER TABLE `todoTask` ADD INDEX(`ownerId`);

CREATE TABLE todo.todoLink (
          `id`          INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `ownerId`     INT NOT NULL,
          `userId`      INT NOT NULL,
          `permission`  INT NOT NULL,
          `created`     DATETIME NOT NULL,
          `updated`     DATETIME NOT NULL
);
ALTER TABLE `todoLink` ADD INDEX(`userId`);
ALTER TABLE `todoLink` ADD INDEX(`ownerId`);

