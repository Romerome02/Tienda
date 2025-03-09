CREATE DATABASE  IF NOT EXISTS `tienda` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */; -- Crear la base de datos 'tienda' si no existe
USE `tienda`; -- Usar la base de datos 'tienda'

-- MySQL dump 10.13  Distrib 8.0.41, for Win64 (x86_64)
-- Host: 127.0.0.1    Database: tienda
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */; -- Guardar la configuración actual de juego de caracteres del cliente
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */; -- Guardar la configuración actual de resultados de juego de caracteres
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */; -- Guardar la configuración actual de collation de conexión
/*!50503 SET NAMES utf8 */; -- Establecer el juego de caracteres a utf8
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */; -- Guardar la configuración actual de zona horaria
/*!40103 SET TIME_ZONE='+00:00' */; -- Establecer la zona horaria a UTC
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */; -- Deshabilitar las verificaciones de unicidad
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@OLD_FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */; -- Deshabilitar las verificaciones de claves foráneas
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */; -- Guardar el modo SQL actual y establecer NO_AUTO_VALUE_ON_ZERO
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */; -- Deshabilitar las notas SQL

-- Table structure for table `productos`
DROP TABLE IF EXISTS `productos`; -- Eliminar la tabla 'productos' si existe
/*!40101 SET @saved_cs_client     = @@character_set_client */; -- Guardar la configuración actual de juego de caracteres del cliente
/*!50503 SET character_set_client = utf8mb4 */; -- Establecer el juego de caracteres del cliente a utf8mb4
CREATE TABLE `productos` (
  `id` varchar(20) NOT NULL AUTO_INCREMENT, -- Columna 'id' con tipo varchar y auto_increment
  `nombre` varchar(255) NOT NULL, -- Columna 'nombre' con tipo varchar
  `descripcion` text DEFAULT NULL, -- Columna 'descripcion' con tipo text
  `precio` decimal(10,2) NOT NULL, -- Columna 'precio' con tipo decimal
  `imagen` varchar(255) DEFAULT NULL, -- Columna 'imagen' con tipo varchar
  `estado` varchar(45) DEFAULT NULL, -- Columna 'estado' con tipo varchar
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(), -- Columna 'fecha_creacion' con tipo timestamp y valor por defecto current_timestamp
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(), -- Columna 'fecha_actualizacion' con tipo timestamp y valor por defecto current_timestamp, se actualiza en cada modificación
  `categoria_id` int(11) DEFAULT NULL, -- Agregar columna 'categoria_id' a la tabla 'productos'
  PRIMARY KEY (`id`) -- Establecer 'id' como clave primaria
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; -- Establecer el motor de almacenamiento a InnoDB y el juego de caracteres a utf8mb4
/*!40101 SET character_set_client = @saved_cs_client */; -- Restaurar la configuración de juego de caracteres del cliente

-- Alter table `productos` to add foreign key to `categorias`
ALTER TABLE `productos` ADD CONSTRAINT `fk_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categorias`(`id`) ON DELETE SET NULL ON UPDATE CASCADE; -- Agregar clave foránea 'fk_categoria' a la tabla 'productos'

-- Dumping data for table `productos`
LOCK TABLES `productos` WRITE; -- Bloquear la tabla 'productos' para escritura
/*!40000 ALTER TABLE `productos` DISABLE KEYS */; -- Deshabilitar las claves de la tabla 'productos'
/*!40000 ALTER TABLE `productos` ENABLE KEYS */; -- Habilitar las claves de la tabla 'productos'
UNLOCK TABLES; -- Desbloquear las tablas

-- Table structure for table `categorias`
DROP TABLE IF EXISTS `categorias`; -- Eliminar la tabla 'categorias' si existe
/*!40101 SET @saved_cs_client     = @@character_set_client */; -- Guardar la configuración actual de juego de caracteres del cliente
/*!50503 SET character_set_client = utf8mb4 */; -- Establecer el juego de caracteres del cliente a utf8mb4
CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT, -- Columna 'id' con tipo int y auto_increment
  `nombre` varchar(255) NOT NULL, -- Columna 'nombre' con tipo varchar
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(), -- Columna 'fecha_creacion' con tipo timestamp y valor por defecto current_timestamp
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(), -- Columna 'fecha_actualizacion' con tipo timestamp y valor por defecto current_timestamp, se actualiza en cada modificación
  PRIMARY KEY (`id`) -- Establecer 'id' como clave primaria
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; -- Establecer el motor de almacenamiento a InnoDB y el juego de caracteres a utf8mb4
/*!40101 SET character_set_client = @saved_cs_client */; -- Restaurar la configuración de juego de caracteres del cliente

-- Dumping data for table `usuarios`
LOCK TABLES `usuarios` WRITE; -- Bloquear la tabla 'usuarios' para escritura
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */; -- Deshabilitar las claves de la tabla 'usuarios'
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */; -- Habilitar las claves de la tabla 'usuarios'
UNLOCK TABLES; -- Desbloquear las tablas
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */; -- Restaurar la configuración de zona horaria

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */; -- Restaurar el modo SQL
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */; -- Restaurar las verificaciones de claves foráneas
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */; -- Restaurar las verificaciones de unicidad
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */; -- Restaurar la configuración de juego de caracteres del cliente
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */; -- Restaurar la configuración de resultados de juego de caracteres
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */; -- Restaurar la configuración de collation de conexión
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */; -- Restaurar las notas SQL

-- Dump completed on 2025-02-25 20:41:35
