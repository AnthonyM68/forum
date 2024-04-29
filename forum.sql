CREATE TABLE User(
   id_user INT NOT NULL AUTO_INCREMENT,
   username VARCHAR(50) NOT NULL,
   password VARCHAR(255) NOT NULL,
   email VARCHAR(255) NOT NULL,
   token VARCHAR(255) NOT NULL,
   token_validity VARCHAR(255) NOT NULL,
   dateRegister DATETIME NOT NULL,
   role json,
   PRIMARY KEY(id_user)
);

CREATE TABLE Category(
   id_category INT NOT NULL AUTO_INCREMENT,
   name VARCHAR(50) NOT NULL,
   PRIMARY KEY(id_category)
);

CREATE TABLE Topic(
   id_topic INT NOT NULL AUTO_INCREMENT,
   title VARCHAR(255) NOT NULL,
   dateCreation DATETIME NOT NULL,
   category_id INT NOT NULL,
   user_id INT NOT NULL,
   PRIMARY KEY(id_topic),
   FOREIGN KEY(category_id) REFERENCES Category(id_category),
   FOREIGN KEY(user_id) REFERENCES User(id_user)
);

CREATE TABLE Post(
   id_message INT NOT NULL AUTO_INCREMENT,
   content TEXT NOT NULL,
   dateCreation DATETIME NOT NULL,
   topic_id INT NOT NULL,
   PRIMARY KEY(id_message),
   FOREIGN KEY(topic_id) REFERENCES Topic(id_topic)
);
