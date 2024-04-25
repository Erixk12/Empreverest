# create database empreverest;
use empreverest;

create table Usuarios (
	id int primary key auto_increment not null, 
	correoelectronico varchar(30), 
    contraseña varchar(100), 
    nombre varchar(30), 
    apellido varchar(30),
    genero varchar(10),
    tipocuenta varchar(10),
    fecha date, 
    estado boolean);
    
create table cambiarContraseña (
	idsolcon int primary key auto_increment not null, 
	iduser int, 
    codigo varchar(10),
    email varchar(50));
    
drop table cambiarContraseña;
    
INSERT INTO Usuarios (correoelectronico, contraseña, nombre) VALUES
("isaac@gmail.com", SHA1("isaac123"), "isaac"),
("pedro@gmail.com", SHA1("pedro123"), "pedro"),
("erick@gmail.com", SHA1("erick123"), "erick"),
("diego@gmail.com", SHA1("diego123"), "diego"),
("jorge@gmail.com", SHA1("jorge123"), "jorge");

INSERT INTO cambiarContraseña (iduser, codigo, email) VALUES (2, '1a2b3c4d5e', 'pedro@gmail.com');

select * from Usuarios;
select * from cambiarContraseña
