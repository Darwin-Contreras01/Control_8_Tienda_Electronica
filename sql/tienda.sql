SET FOREIGN_KEY_CHECKS=0;

DROP DATABASE IF EXISTS TIENDA;
CREATE DATABASE TIENDA CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE TIENDA;

SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE PRODUCTO(
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion VARCHAR(250) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0
)ENGINE=InnoDB;

CREATE TABLE CLIENTE(
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL,
    direccion VARCHAR(200) NOT NULL
)ENGINE=InnoDB;

CREATE TABLE COMPRA(
    id_compra INT AUTO_INCREMENT PRIMARY KEY,
    cantidad INT NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_producto INT NOT NULL,
    id_cliente INT NOT NULL,
    CONSTRAINT fk_compra_producto FOREIGN KEY(id_producto)
        REFERENCES PRODUCTO(id_producto)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT fk_compra_cliente FOREIGN KEY(id_cliente)
        REFERENCES CLIENTE(id_cliente)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
)ENGINE=InnoDB;

CREATE TABLE RESENA(
    id_resena INT AUTO_INCREMENT PRIMARY KEY,
    cliente VARCHAR(100) NOT NULL,
    id_producto INT NOT NULL,
    calificacion TINYINT NOT NULL,
    comentario TEXT NOT NULL,
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_resena_producto FOREIGN KEY(id_producto)
        REFERENCES PRODUCTO(id_producto)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
)ENGINE=InnoDB;

INSERT INTO PRODUCTO(nombre,descripcion,precio,stock) VALUES
('Notebook Lenovo IdeaPad','Notebook 15 pulgadas',650000,15),
('Mouse Logitech M185','Mouse inalámbrico',19990,40),
('Teclado Redragon Kumara','Teclado mecánico RGB',45990,25),
('Monitor Samsung 24','Monitor Full HD',169990,18),
('SSD Kingston 1TB','Unidad SSD SATA',79990,30);

INSERT INTO CLIENTE(nombre,email,direccion) VALUES
('Juan Pérez','juan@gmail.com','Santiago'),
('María González','maria@gmail.com','Valparaíso'),
('Pedro Soto','pedro@gmail.com','Concepción'),
('Ana Morales','ana@gmail.com','La Serena'),
('Carlos Díaz','carlos@gmail.com','Temuco');

INSERT INTO COMPRA(cantidad,total,id_producto,id_cliente) VALUES
(1,650000,1,1),
(2,39980,2,1),
(1,45990,3,1),
(1,169990,4,2),
(2,159980,5,2),
(1,19990,2,3),
(1,650000,1,3),
(3,59970,2,4),
(2,91980,3,4),
(1,79990,5,5),
(1,169990,4,5),
(2,39980,2,5);

INSERT INTO RESENA(cliente,id_producto,calificacion,comentario) VALUES
('Juan Pérez',1,5,'Excelente notebook.'),
('María González',2,4,'Muy buen mouse.'),
('Pedro Soto',3,5,'Excelente teclado.'),
('Ana Morales',4,4,'Muy buena imagen.'),
('Carlos Díaz',5,5,'Muy rápido.');

SELECT * FROM PRODUCTO;
SELECT * FROM CLIENTE;
SELECT * FROM COMPRA;
SELECT * FROM RESENA;

SELECT
    c.id_cliente,
    c.nombre,
    c.email,
    COUNT(co.id_compra) AS total_compras,
    SUM(co.total) AS monto_total
FROM CLIENTE c
INNER JOIN COMPRA co ON c.id_cliente=co.id_cliente
GROUP BY c.id_cliente,c.nombre,c.email
HAVING COUNT(co.id_compra)>2
ORDER BY total_compras DESC,monto_total DESC;
