CREATE DATABASE mytododata;

use mytododata;
CREATE TABLE users(
    id MEDIUMINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255),
    password VARCHAR(255)
);

INSERT INTO users(username, password)
VALUES 
('bit_academy', '$2y$10$.FbHMaYJUhYSdQ0YLt/VWOXfHSgj8O3aJk0XBh20T28hUA/J2iClq'),
('markmeer', '$2y$10$.FbHMaYJUhYSdQ0YLt/VWOXfHSgj8O3aJk0XBh20T28hUA/J2iClq'),
('johndoe', '$2y$10$.FbHMaYJUhYSdQ0YLt/VWOXfHSgj8O3aJk0XBh20T28hUA/J2iClq'),
('janedoe', '$2y$10$/d6fVe/JRcs0DLEH4HWlhejTX08yOqQlFsfOlnOIwMIYqUK1jBJUi');


use mytododata;
CREATE TABLE mytodo(
    id MEDIUMINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    sequence  INT,
    username VARCHAR(255),
    todo VARCHAR(1024),
    begin DATE,
    finish DATE,
    status ENUM('new' , 'open' , 'ready', 'closed')
);

INSERT INTO mytodo(sequence, username, todo, begin, finish, status)

VALUES
(4, 'bit_academy', 'Klus Utrecht', '2022-11-01', '2022-11-15', 'new'),
(2, 'johndoe', 'Koffie voor iedereen zetten', '2022-07-01', '2022-08-01', 'new'),
(4, 'johndoe', 'Thee voor de groep zetten', '2022-08-01', '2022-09-01', 'new'),
(4, 'janedoe', 'Project Gouda', '2022-10-01', '2022-10-15', 'new');


use mytododata;
CREATE TABLE sessions(
    id MEDIUMINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255),
    token VARCHAR(255)
);
