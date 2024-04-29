create database empreverest;
use empreverest;

create table Usuarios (
	id int primary key auto_increment not null, 
	correoelectronico varchar(30), 
    contraseña varchar(100), 
    estado boolean,
    fecha datetime, 
    codigo varchar(10));
    
create table personas(
	idpeople int primary key auto_increment not null, 
    iduser int, 
	nombre varchar(30), 
    apellido varchar(30),
    genero varchar(10),
    tipocuenta enum('Mentor', 'Estudiante', 'Admin'),
    codigo_alumno varchar(9),
    centro enum('CUALTOS', 'CUCEA'),
    FOREIGN KEY (iduser) REFERENCES Usuarios(id)
    );    
    
create table cambiarContraseña (
	idsolcon int primary key auto_increment not null, 
	iduser int, 
    codigo varchar(10),
    email varchar(50));
    
drop table cambiarContraseña;
    
INSERT INTO Usuarios (correoelectronico, contraseña) VALUES
("isaac@gmail.com", SHA1("isaac123")),
("pedro@gmail.com", SHA1("pedro123")),
("erick@gmail.com", SHA1("erick123")),
("diego@gmail.com", SHA1("diego123")),
("jorge@gmail.com", SHA1("jorge123"));


select * from Usuarios;
select * from personas;
select * from cambiarContraseña;
drop table personas;
drop table Usuarios;