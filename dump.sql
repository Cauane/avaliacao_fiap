CREATE DATABASE avaliacao_fiap;

CREATE TABLE  `aluno` ( 
    `ID_Aluno` INT(9) NOT NULL AUTO_INCREMENT, 
    `sNome` VARCHAR(250) NOT NULL, 
    `dtNasc` DATE NOT NULL, 
    `sUser` VARCHAR(100) NOT NULL, 
    PRIMARY KEY (`ID_Aluno`)
) ENGINE = InnoDB;

CREATE TABLE `turma` (
    `ID_Turma` INT(9) NOT NULL AUTO_INCREMENT,
    `sNome` VARCHAR(250) NOT NULL,
    `sDesc` TEXT NOT NULL,
    `iTipo` TINYINT(1) NOT NULL COMMENT '1: Normal | 2:Dependência | 3: Extracurricular | 4:Temporária', 
    PRIMARY KEY (`ID_Turma`)
) ENGINE = InnoDB;

CREATE TABLE `matricula` (
    `IDE_Aluno` INT(9) NOT NULL,
    `IDE_Turma` INT(9) NOT NULL
) ENGINE = InnoDB;

ALTER TABLE `matricula` 
    ADD UNIQUE `idx_alun_turm` (`IDE_Aluno`, `IDE_Turma`);

CREATE TABLE `tipo_turma` (
    `ID_Tipo` INT(9) NOT NULL AUTO_INCREMENT,
    `sNome` VARCHAR(250) NOT NULL,
    PRIMARY KEY (`ID_Tipo`)
) ENGINE = InnoDB;

INSERT INTO `tipo_turma` (`sNome`) VALUES ('Normal'), ('Dependência'), ('Extracurricular'), ('Temporária');