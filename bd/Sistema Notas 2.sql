CREATE DATABASE sistemanotas;
USE sistemanotas;

CREATE TABLE `Etapa`
(
  `idEtapa` int PRIMARY KEY AUTO_INCREMENT,
  `nombreEtapa` varchar(255) NOT NULL,
  `duracionanios` int NOT NULL,
  `anioinicio` int NOT NULL,
  `aniofinalizacion` int NOT NULL
);

CREATE TABLE `Materia`
(
  `idMateria` int PRIMARY KEY AUTO_INCREMENT,
  `nombreMateria` varchar(500) NOT NULL,
  `idEtapa` int NOT NULL,
  `anio` int NOT NULL,
  `cuatrimestre` varchar(100) NOT NULL,
  `estadoEliminacion` int DEFAULT "1" NOT NULL,
  `nivel` int NOT NULL,
  FOREIGN KEY(`idEtapa`) REFERENCES `Etapa`(`idEtapa`)
);

CREATE TABLE `Ciclo`
(
  `idCiclo` int PRIMARY KEY AUTO_INCREMENT,
  `nombreCiclo` varchar(200) NOT NULL,
  `fechaInicio` date NOT NULL,
  `fechaFinalizacion` date NOT NULL,
  `estado` int NOT NULL COMMENT '0-Pendiente 1-Activo 2-Finalizado'
);

CREATE TABLE `Docente`
(
  `idDocente` int PRIMARY KEY AUTO_INCREMENT,
  `nombreDocente` varchar(250) NOT NULL,
  `apellidoDocente` varchar(250) NOT NULL,
  `duiDocente` varchar(11) NOT NULL,
  `numeroTelefono` varchar(9) NOT NULL,
  `correoDocente` text NOT NULL,
  `estadoEliminacion` int DEFAULT "1" NOT NULL
);

CREATE TABLE `TitulosDocente`
(
  `idDetalleTitulo` int PRIMARY KEY AUTO_INCREMENT,
  `idDocente` int,
  `tituloDocente` varchar(250) NOT NULL,
  FOREIGN KEY(`idDocente`) REFERENCES `Docente`(`idDocente`)
);

CREATE TABLE `MateriasDocente`
(
  `idDetalle` int PRIMARY KEY AUTO_INCREMENT,
  `idDocente` int NOT NULL,
  `idMateria` int NOT NULL,
  FOREIGN KEY(`idDocente`) REFERENCES `Docente`(`idDocente`),
  FOREIGN KEY(`idMateria`) REFERENCES `Materia`(`idMateria`)
);

CREATE TABLE `Estudiante`
(
  `idEstudiante` int PRIMARY KEY AUTO_INCREMENT,
  `nombreEstudiante` varchar(500) NOT NULL,
  `apellidoEstudiante` varchar(500) NOT NULL,
  `fechaNacimiento` date NOT NULL,
  `duiEstudiante` varchar(10) NOT NULL,
  `fechaIngreso` date NOT NULL,
  `cum` float DEFAULT 0,
  `fechaBautismo` date NOT NULL,
  `fechaConfirmacion` date NOT NULL,
  `parroquia` varchar(250) DEFAULT "No se ha asignado",
  `direccion` text DEFAULT "No se ha asignado",
  `numeroTelefonicoCasa` varchar(9) DEFAULT "0000-0000",
  `numeroMovil` varchar(9) DEFAULT "0000-0000",
  `nombrePadre` varchar(250) DEFAULT "No se ha asignado",
  `nombreMadre` varchar(250) DEFAULT "No se ha asignado",
  `enfermedades` text DEFAULT "No se ha asignado descripción",
  `correoEstudiante` varchar(255) NOT NULL,
  `estadoAceptacion` int DEFAULT "1" NOT NULL,
  `estadoEliminacion` int DEFAULT "1" NOT NULL
);


CREATE TABLE `Grupo`
(
  `idGrupo` int PRIMARY KEY AUTO_INCREMENT,
  `idMateria` int NOT NULL,
  `nombreGrupo` varchar(500) NOT NULL,
  `anio` date NOT NULL,
  `idDocente` int NOT NULL,
  `idCiclo` int NOT NULL,
  `estadoFinalizacion` int DEFAULT 1 COMMENT '1-activo, 0-finalizado',
  FOREIGN KEY(`idMateria`) REFERENCES `Materia`(`idMateria`),
  FOREIGN KEY(`idDocente`) REFERENCES `Docente`(`idDocente`),
  FOREIGN KEY(`idCiclo`) REFERENCES `Ciclo`(`idCiclo`)
);

CREATE TABLE `Evaluacion`
(
  `idEvaluacion` int PRIMARY KEY AUTO_INCREMENT,
  `nombreEvaluacion` varchar(500) NOT NULL,
  `descripcion` text NULL,
  `idGrupo` int NOT NULL,
  `porcentaje` float NOT NULL,
  FOREIGN KEY(`idGrupo`) REFERENCES `Grupo`(`idGrupo`)
);

CREATE TABLE `Nota`
(
  `idNota` int PRIMARY KEY AUTO_INCREMENT,
  `idGrupo` int NOT NULL,
  `idEstudiante` int NOT NULL,
  `idEvaluacion` int NOT NULL,
  `nota` float NOT NULL,
  `porcentajeGanado` float NOT NULL,
  FOREIGN KEY(`idGrupo`) REFERENCES `Grupo`(`idGrupo`),
  FOREIGN KEY(`idEstudiante`) REFERENCES `Estudiante`(`idEstudiante`),
  FOREIGN KEY(`idEvaluacion`) REFERENCES `Evaluacion`(`idEvaluacion`)
);

CREATE TABLE `Horario`
(
  `idHorario` int PRIMARY KEY AUTO_INCREMENT,
  `horaInicio` time NOT NULL,
  `horaFinalizacion` time NOT NULL,
  `idGrupo` int NOT NULL,
  FOREIGN KEY (`idGrupo`) REFERENCES `Grupo`(`idGrupo`)
);

CREATE TABLE `DetalleEstudianteGrupo`
(
  `idDetalle` int PRIMARY KEY AUTO_INCREMENT,
  `idEstudiante` int NOT NULL,
  `idGrupo` int NOT NULL,
  FOREIGN KEY(`idEstudiante`) REFERENCES `Estudiante`(`idEstudiante`),
  FOREIGN KEY(`idGrupo`) REFERENCES `Grupo`(`idGrupo`)
);

CREATE TABLE `Usuario`
(
  `idUsuario` varchar(10) PRIMARY KEY,
  `usuario` varchar(500) NOT NULL,
  `password` text NOT NULL,
  `nivel` int
);

CREATE TABLE `HistorialEstudiante`
(
  `idHistorial` int PRIMARY KEY AUTO_INCREMENT,
  `idEstudiante` int NOT NULL,
  `idMateria` int NOT NULL,
  `anio` int NOT NULL,
  `promedio` float,
  `convocatoria` text NOT NULL, --Ordinaria o Extraordinaria
  FOREIGN KEY(`idEstudiante`) REFERENCES `Estudiante`(`idEstudiante`),
  FOREIGN KEY(`idMateria`) REFERENCES `Materia`(`idMateria`)
);

CREATE TABLE `MateriaAuxiliar`
(
  `idMateriaAuxiliar` int PRIMARY KEY AUTO_INCREMENT,
  `idMateria` int NOT NULL,
  `idEstudiante` int NOT NULL,
  FOREIGN KEY(`idMateria`) REFERENCES `Materia`(`idMateria`),
  FOREIGN KEY(`idEstudiante`) REFERENCES `Estudiante`(`idEstudiante`)
);

CREATE TABLE `administradores`
(
  `idAdministrador` int PRIMARY KEY AUTO_INCREMENT,
  `nombreAdministrador` varchar(255) NOT NULL,
  `apellidoAdministrador` varchar(255) NOT NULL,
  `duiAdministrador` varchar(10) NOT NULL,  
  `telefonoAdministrador` varchar(9) NOT NULL,
  `correoAdministrador` text NULL,
  `fechaIngreso` date NOT NULL,
  `estadoEliminacion` int NOT NULL DEFAULT 1
);

CREATE TABLE `solicitudes`
(
  `idSolicitud` int PRIMARY KEY AUTO_INCREMENT,
  `idUsuario` varchar(10) NOT NULL,
  `fecha` date NOT NULL,
  `estado` int NOT NULL DEFAULT 1,
  FOREIGN KEY(`idUsuario`) REFERENCES `Usuario`(`idUsuario`)
);


CREATE TABLE `estudiantesReprobados`
(
  `idDetalle` int PRIMARY KEY AUTO_INCREMENT,
  `idEstudiante` int NOT NULL,
  `idGrupo` int NOT NULL, 
  `promedio` float NOT NULL,  
  `estadoReprobado` int NOT NULL DEFAULT 1, --Si el estado es 1 es que se encuetra reprobado, si el estado es 0 es que ha supero lo reprobado
  FOREIGN KEY (`idEstudiante`) REFERENCES `estudiante`(`idEstudiante`),
  FOREIGN KEY (`idGrupo`) REFERENCES `grupo`(`idGrupo`)
);

CREATE TABLE `actividadesExtraordinarias`
(
  `idActividad` int PRIMARY KEY AUTO_INCREMENT,
  `idDetalle` int NOT NULL,
  `actividad` text NOT NULL,
  `descripcion` text NOT NULL DEFAULT 'No se ha añadido descripción',
  `porcentaje` float NOT NULL,
  `nota` float NULL DEFAULT 0,
  `porcentajeGanado` float NULL DEFAULT 0,
  `estadoFinalizacion` int NOT NULL DEFAULT 1,
  FOREIGN KEY(`idDetalle`) REFERENCES `estudiantesReprobados`(`idDetalle`)
);
