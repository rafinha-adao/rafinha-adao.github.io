CREATE TABLE `groups` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(50) NOT NULL,
    `description` varchar(150) DEFAULT NULL,
    `image` mediumblob DEFAULT NULL,
    `idUser` int(11) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
CREATE TABLE `messages` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `content` varchar(300) NOT NULL,
    `date` datetime NOT NULL,
    `idUser` int(11) NOT NULL,
    `idGroup` int(11) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
CREATE TABLE `users` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(50) NOT NULL,
    `tagName` varchar(50) NOT NULL,
    `birthday` date DEFAULT NULL,
    `bio` varchar(100) DEFAULT NULL,
    `email` varchar(75) NOT NULL,
    `idGroup` int(11) NULL DEFAULT NULL,
    `hasGroup` tinyint(4) NOT NULL,
    `pass` varchar(100) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB AUTO_INCREMENT = 2 DEFAULT CHARSET = utf8mb4;
ALTER TABLE groups
ADD CONSTRAINT fk_group_idUser FOREIGN KEY (idUser) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE messages
ADD CONSTRAINT fk_messages_idUser FOREIGN KEY (idUser) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE messages
ADD CONSTRAINT fk_messages_idGroup FOREIGN KEY (idGroup) REFERENCES groups(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE users
ADD CONSTRAINT fk_users_idGroup FOREIGN KEY (idGroup) REFERENCES groups(id) ON UPDATE CASCADE ON DELETE CASCADE;