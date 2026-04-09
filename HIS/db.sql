-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS laboratorio;
USE laboratorio;

-- Crear la tabla pacientes
CREATE TABLE pacientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    expediente VARCHAR(20) NOT NULL UNIQUE,
    nombre VARCHAR(100) NOT NULL,
    afiliacion VARCHAR(50),
    fecha_nacimiento DATE
);

-- Insertar registros de ejemplo
INSERT INTO pacientes (expediente, nombre, afiliacion, fecha_nacimiento) VALUES
('EXP001', 'Juan Pérez', 'IMSS', '1985-06-15'),
('EXP002', 'María López', 'ISSSTE', '1990-09-22'),
('EXP003', 'Carlos Ramírez', 'Privado', '1978-12-03'),
('EXP004', 'Ana Torres', 'IMSS', '2000-03-10'),
('EXP005', 'Luis Hernández', 'Seguro Popular', '1982-07-25');

