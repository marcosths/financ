DROP TABLE IF EXISTS Centro_Custo
;
DROP TABLE IF EXISTS Conta_Usuar
;
DROP TABLE IF EXISTS Favorecido
;
DROP TABLE IF EXISTS Movimentos
;

CREATE TABLE Centro_Custo
(
	ds_centro_custo VARCHAR(50),
	id_centro_custo INTEGER NOT NULL,
	id_conta_usuar INTEGER NOT NULL,
	nm_abrev_centro_custo VARCHAR(15) NOT NULL,
	PRIMARY KEY (id_centro_custo, id_conta_usuar),
	UNIQUE (nm_abrev_centro_custo)
) 
;


CREATE TABLE Conta_Usuar
(
	ds_bairro VARCHAR(25),
	ds_endereco VARCHAR(40),
	ds_senha VARCHAR(50) NOT NULL,
	id_conta_usuar INTEGER NOT NULL AUTO_INCREMENT,
	nm_cidade VARCHAR(30) NOT NULL,
	nm_conta_usuar VARCHAR(15) NOT NULL,
	nm_usuar VARCHAR(50) NOT NULL,
	PRIMARY KEY (id_conta_usuar),
	UNIQUE (nm_conta_usuar)
) 
;


CREATE TABLE Favorecido
(
	id_conta_usuar INTEGER NOT NULL,
	id_favorecido INTEGER NOT NULL,
	nm_abrev_favorecido VARCHAR(20) NOT NULL,
	nm_favorecido VARCHAR(50),
	PRIMARY KEY (id_conta_usuar, id_favorecido),
	UNIQUE (nm_abrev_favorecido)
) 
;


CREATE TABLE Movimentos
(
	dt_movimento DATE NOT NULL,
	fg_compensado CHAR(1),
	id_centro_custo INTEGER NOT NULL,
	id_conta_usuar INTEGER NOT NULL,
	id_favorecido INTEGER NOT NULL,
	id_movimento INTEGER NOT NULL,
	nr_documento INTEGER,
	vl_movimento DOUBLE NOT NULL,
	PRIMARY KEY (id_conta_usuar, id_movimento)
) 
;
