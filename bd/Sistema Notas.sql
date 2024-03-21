CREATE TABLE `Etapa` (
  `idEtapa` int PRIMARY KEY AUTO_INCREMENT,
  `nombreEtapa` varchar(255)
);

CREATE TABLE `Cuatrimestre` (
  `idCuatrimestre` int PRIMARY KEY AUTO_INCREMENT,
  `nombreCuatrimestre` varchar(200),
  `idEtapa` int
);

CREATE TABLE `Materia` (
  `idMateria` int PRIMARY KEY AUTO_INCREMENT,
  `nombreMateria` varchar(500),
  `idCuatrimestre` int
);

CREATE TABLE `Ciclo` (
  `idCiclo` int PRIMARY KEY AUTO_INCREMENT,
  `nombreCiclo` varchar(200),
  `fechaInicio` date,
  `fechaFinalizacion` date
);

CREATE TABLE `Docente` (
  `idDocente` int PRIMARY KEY AUTO_INCREMENT,
  `nombreDocente` varchar(250),
  `apellidoDocente` varchar(250),
  `duiDocente` varchar(11),
  `numeroTelefono` varchar(9),
  `correoDocente` text
);

CREATE TABLE `TitulosDocente` (
  `idDetalleTitulo` int PRIMARY KEY AUTO_INCREMENT,
  `idDocente` int,
  `tituloDocente` varchar(250)
);

CREATE TABLE `MateriasDocente` (
  `idDetalle` int PRIMARY KEY AUTO_INCREMENT,
  `idDocente` int,
  `idMateria` int
);

CREATE TABLE `Estudiante` (
  `idEstudiante` int PRIMARY KEY AUTO_INCREMENT,
  `nombreEstudiante` varchar(500),
  `apellidoEstudiante` varchar(500),
  `fechaNacimiento` date,
  `duiEstudiante` varchar(10),
  `anioingreso` date,
  `cum` float,
  `fechaBautismo` date,
  `fechaConfirmacion` date,
  `parroquia` varchar(250),
  `direccion` text,
  `numeroTelefonicoCasa` varchar(9),
  `numeroMovil` varchar(9),
  `nombrePadre` varchar(250),
  `nombreMadre` varchar(250),
  `enfermedades` text
);

CREATE TABLE `Evaluacion` (
  `idEvaluacion` int PRIMARY KEY AUTO_INCREMENT,
  `nombreEvaluacion` varchar(500),
  `idMateria` int,
  `fechaInicio` date,
  `fechaFinalizacion` date
);

CREATE TABLE `Grupo` (
  `idGrupo` int PRIMARY KEY AUTO_INCREMENT,
  `idMateria` int,
  `nombreGrupo` varchar(500),
  `anio` date,
  `idDocente` int,
  `idCiclo` int,
  `estadoFinalizacion` int DEFAULT 1 COMMENT '1-activo, 0-finalizado'
);

CREATE TABLE `Nota` (
  `idNota` int PRIMARY KEY AUTO_INCREMENT,
  `idGrupo` int,
  `idEstudiante` int,
  `idEvaluacion` int,
  `nota` float
);

CREATE TABLE `Horario` (
  `idHorario` int PRIMARY KEY AUTO_INCREMENT,
  `horaInicio` time,
  `horaFinalizacion` time,
  `idGrupo` int
);

CREATE TABLE `DetalleEstudianteGrupo` (
  `idDetalle` int PRIMARY KEY AUTO_INCREMENT,
  `idEstudiante` int,
  `idGrupo` int
);

CREATE TABLE `Usuario` (
  `idUsuario` int PRIMARY KEY,
  `usuario` varchar(500),
  `password` binary,
  `nivel` int
);

CREATE TABLE `HistorialEstudiante` (
  `idHistorial` int PRIMARY KEY AUTO_INCREMENT,
  `idEstudiante` int,
  `idMateria` int,
  `idCiclo` int,
  `promedio` float
);

ALTER TABLE `Cuatrimestre` ADD FOREIGN KEY (`idEtapa`) REFERENCES `Etapa` (`idEtapa`);

ALTER TABLE `Materia` ADD FOREIGN KEY (`idCuatrimestre`) REFERENCES `Cuatrimestre` (`idCuatrimestre`);

ALTER TABLE `TitulosDocente` ADD FOREIGN KEY (`idDocente`) REFERENCES `Docente` (`idDocente`);

ALTER TABLE `MateriasDocente` ADD FOREIGN KEY (`idDocente`) REFERENCES `Docente` (`idDocente`);

ALTER TABLE `MateriasDocente` ADD FOREIGN KEY (`idMateria`) REFERENCES `Materia` (`idMateria`);

ALTER TABLE `Evaluacion` ADD FOREIGN KEY (`idMateria`) REFERENCES `Materia` (`idMateria`);

ALTER TABLE `Grupo` ADD FOREIGN KEY (`idMateria`) REFERENCES `Materia` (`idMateria`);

ALTER TABLE `Grupo` ADD FOREIGN KEY (`idDocente`) REFERENCES `Docente` (`idDocente`);

ALTER TABLE `Grupo` ADD FOREIGN KEY (`idCiclo`) REFERENCES `Ciclo` (`idCiclo`);

ALTER TABLE `Nota` ADD FOREIGN KEY (`idGrupo`) REFERENCES `Grupo` (`idGrupo`);

ALTER TABLE `Nota` ADD FOREIGN KEY (`idEstudiante`) REFERENCES `Estudiante` (`idEstudiante`);

ALTER TABLE `Nota` ADD FOREIGN KEY (`idEvaluacion`) REFERENCES `Evaluacion` (`idEvaluacion`);

ALTER TABLE `Horario` ADD FOREIGN KEY (`idGrupo`) REFERENCES `Grupo` (`idGrupo`);

ALTER TABLE `DetalleEstudianteGrupo` ADD FOREIGN KEY (`idEstudiante`) REFERENCES `Estudiante` (`idEstudiante`);

ALTER TABLE `DetalleEstudianteGrupo` ADD FOREIGN KEY (`idGrupo`) REFERENCES `Grupo` (`idGrupo`);

ALTER TABLE `HistorialEstudiante` ADD FOREIGN KEY (`idEstudiante`) REFERENCES `Estudiante` (`idEstudiante`);

ALTER TABLE `HistorialEstudiante` ADD FOREIGN KEY (`idMateria`) REFERENCES `Materia` (`idMateria`);

ALTER TABLE `HistorialEstudiante` ADD FOREIGN KEY (`idCiclo`) REFERENCES `Ciclo` (`idCiclo`);
