-- phpMyAdmin SQL Dump
-- version 4.1.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Giu 15, 2021 alle 20:37
-- Versione del server: 8.0.21
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `my_t3l3m4t1c4`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `Campionario_Prodotti`
--

CREATE TABLE IF NOT EXISTS `Campionario_Prodotti` (
  `ID_Fattura` int NOT NULL,
  `Cod_Prodotto` int DEFAULT NULL,
  `Quantità` int DEFAULT NULL,
  `Prezzo_Totale` int DEFAULT NULL,
  PRIMARY KEY (`ID_Fattura`),
  KEY `Cod_Prodotto` (`Cod_Prodotto`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `Campionario_Prodotti`
--

INSERT INTO `Campionario_Prodotti` (`ID_Fattura`, `Cod_Prodotto`, `Quantità`, `Prezzo_Totale`) VALUES
(1, 567, 12, 100000000),
(2, 1011, 2, 640),
(3, 7674, 1, 65),
(4, 1011, 1, 320),
(5, 987, 2, 40),
(6, 65463, 1, 100),
(7, 1011, 1, 26),
(8, 3452, 1, 30);

-- --------------------------------------------------------

--
-- Struttura della tabella `Categoria_Prodotti`
--

CREATE TABLE IF NOT EXISTS `Categoria_Prodotti` (
  `Cod_Categoria` int NOT NULL,
  `Nome` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`Cod_Categoria`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `Categoria_Prodotti`
--

INSERT INTO `Categoria_Prodotti` (`Cod_Categoria`, `Nome`) VALUES
(1, 'computer ed elettronica'),
(2, 'elettrodomestici'),
(3, 'giochi e videogiochi'),
(4, 'film e musica'),
(5, 'libri e cultura'),
(6, 'sport'),
(7, 'casa'),
(8, 'alimentari'),
(9, 'bellezza e salute'),
(10, 'arte');

-- --------------------------------------------------------

--
-- Struttura della tabella `Fattura_Al_Cliente`
--

CREATE TABLE IF NOT EXISTS `Fattura_Al_Cliente` (
  `ID` int NOT NULL,
  `Cod_Cliente` int DEFAULT NULL,
  `ID_Fattura` int DEFAULT NULL,
  `Data_Ordine` date DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `Cod_Cliente` (`Cod_Cliente`),
  KEY `ID_Fattura` (`ID_Fattura`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `Fattura_Al_Cliente`
--

INSERT INTO `Fattura_Al_Cliente` (`ID`, `Cod_Cliente`, `ID_Fattura`, `Data_Ordine`) VALUES
(1, 1000, 20343, '2018-01-01'),
(2, 1003, 453564, '2018-01-09'),
(3, 1004, 346, '2018-01-03'),
(6345, 1003, 9911, '2018-04-12'),
(23454, 1001, 99, '2018-02-02'),
(74323, 1004, 993323, '2018-03-02'),
(9876543, 1002, 9932456, '2018-09-28'),
(12398765, 1004, 993563, '2018-07-26');

-- --------------------------------------------------------

--
-- Struttura della tabella `Fattura_Dal_Fornitore`
--

CREATE TABLE IF NOT EXISTS `Fattura_Dal_Fornitore` (
  `ID_Fattura` int NOT NULL,
  `Cod_Fornitore` int DEFAULT NULL,
  `Data_Ordine` date DEFAULT NULL,
  `Totale` int DEFAULT NULL,
  PRIMARY KEY (`ID_Fattura`),
  KEY `Cod_Fornitore` (`Cod_Fornitore`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `Fattura_Dal_Fornitore`
--

INSERT INTO `Fattura_Dal_Fornitore` (`ID_Fattura`, `Cod_Fornitore`, `Data_Ordine`, `Totale`) VALUES
(1, 453464, '2018-01-02', 120),
(2, 12345, '2018-01-03', 100000000),
(3181265, 10003, '2018-01-01', 3000);

-- --------------------------------------------------------

--
-- Struttura della tabella `Fornitore`
--

CREATE TABLE IF NOT EXISTS `Fornitore` (
  `Cod_Fornitore` int NOT NULL,
  `Nome_Ditta` varchar(30) DEFAULT NULL,
  `Nickname` varchar(25) DEFAULT NULL,
  `Password` varchar(40) NOT NULL,
  `Email` varchar(30) DEFAULT NULL,
  `Indirizzo` varchar(40) DEFAULT NULL,
  `Paese` varchar(25) DEFAULT NULL,
  `Telefono` int DEFAULT NULL,
  `Carrello` varchar(8192) NOT NULL,
  PRIMARY KEY (`Cod_Fornitore`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `Fornitore`
--

INSERT INTO `Fornitore` (`Cod_Fornitore`, `Nome_Ditta`, `Nickname`, `Password`, `Email`, `Indirizzo`, `Paese`, `Telefono`, `Carrello`) VALUES
(1, 'UNIPR s.r.l ', 'UNIPR', '299765', 'unipr.vendit@gmail.com', 'via Parma 7', 'Italia', 334326589, ''),
(2, 'samsung', 'ihateapple', 'melamarcia', 'samsung@samsung.it', 'Via del galaxy 22', 'America', 34353453, ''),
(3, 'Louvre', 'Love_Paris', 'napoleone', 'louvre@gmail.com', 'Via Parigi 123', 'Francia', 96532323, ''),
(4, 'Eminflex', 'eminflex_1344', 'mastrota65', 'eminflex@azienda.it', 'Via Emin 22', 'Italia', 52133543, ''),
(5, 'apple', 'apple_store', 'melamorsicata', 'apple@applemail.it', 'Via della mela', 'America', 35252563, ''),
(6, 'Unipr SPA', 'Unipr', 'ingegneria', 'unipr@ingegneria.com', 'Via Dell''informazione', 'Italia', 23432342, '');

-- --------------------------------------------------------

--
-- Struttura della tabella `Fornitura`
--

CREATE TABLE IF NOT EXISTS `Fornitura` (
  `ID_Fattura` int NOT NULL AUTO_INCREMENT,
  `Cod_Fornitore` int DEFAULT NULL,
  `Cod_Prodotto` int DEFAULT NULL,
  `Quantita` int DEFAULT NULL,
  PRIMARY KEY (`ID_Fattura`),
  KEY `Cod_Fornitore` (`Cod_Fornitore`),
  KEY `Cod_Prodotto` (`Cod_Prodotto`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3181266 ;

--
-- Dump dei dati per la tabella `Fornitura`
--

INSERT INTO `Fornitura` (`ID_Fattura`, `Cod_Fornitore`, `Cod_Prodotto`, `Quantita`) VALUES
(1, 5, 987, 10),
(2, 3, 567, 1),
(3181265, 2, 100, 10);

-- --------------------------------------------------------

--
-- Struttura della tabella `Prodotto`
--

CREATE TABLE IF NOT EXISTS `Prodotto` (
  `Cod_Prodotto` int NOT NULL,
  `Cod_Categoria` int DEFAULT NULL,
  `Nome` varchar(25) DEFAULT NULL,
  `Prezzo_Vendita` int DEFAULT NULL,
  `Scorte_Magazzino` int DEFAULT NULL,
  PRIMARY KEY (`Cod_Prodotto`),
  KEY `Cod_Categoria` (`Cod_Categoria`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `Prodotto`
--

INSERT INTO `Prodotto` (`Cod_Prodotto`, `Cod_Categoria`, `Nome`, `Prezzo_Vendita`, `Scorte_Magazzino`) VALUES
(100, 1, 'Computer HP CLA01234', 650, 110),
(234, 8, 'kebab', 5, 13),
(454, 9, 'RiRi Parfum', 40, 13),
(567, 10, 'La Gioconda ORIGINAL', 100000000, 1),
(987, 7, 'Pentole Eminflex', 20, 100),
(1011, 2, 'Lavatrice samsung X1X2', 320, 35),
(3439, 6, 'Guanti boxe nike PLUS', 40, 200),
(3452, 3, 'Mass Effect 1/2/3 Trilogy', 30, 310),
(3663, 3, 'devil may cry 1-2-3', 10, 34),
(4656, 5, 'Il Piccolo Principe', 20, 12),
(7674, 3, 'pacman', 2, 10),
(45444, 1, 'Cuffie LG', 40, 8),
(56346, 4, 'metallica album', 10, 1),
(65463, 8, 'pollo allo spiedo', 4, 64);

-- --------------------------------------------------------

--
-- Struttura della tabella `Utenti`
--

CREATE TABLE IF NOT EXISTS `Utenti` (
  `Cod_Cliente` int NOT NULL,
  `Nome` varchar(25) DEFAULT NULL,
  `Cognome` varchar(25) DEFAULT NULL,
  `Nickname` varchar(25) DEFAULT NULL,
  `Password` varchar(40) NOT NULL,
  `Amministratore` tinyint(1) NOT NULL,
  `Email` varchar(90) DEFAULT NULL,
  `Indirizzo` varchar(40) DEFAULT NULL,
  `Paese` varchar(25) DEFAULT NULL,
  `Telefono` int DEFAULT NULL,
  `Carrello` varchar(4096) DEFAULT NULL,
  PRIMARY KEY (`Cod_Cliente`),
  UNIQUE KEY `Nickname` (`Nickname`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `Utenti`
--

INSERT INTO `Utenti` (`Cod_Cliente`, `Nome`, `Cognome`, `Nickname`, `Password`, `Amministratore`, `Email`, `Indirizzo`, `Paese`, `Telefono`, `Carrello`) VALUES
(1000, 'Giuseppe', 'Dimonte', 'GiuseppeUNIPR', '266765', 1, 'giuseppe.dimonte@studenti.unipr.it', 'Via degli olmi 7 ', 'Italia', 12345678, 'SELECT Cod_Prodotto, Cod_Categoria, Nome, Prezzo_Vendita, Scorte_Magazzino FROM Prodotto WHERE Cod_Prodotto = 234 OR Cod_Prodotto = 0 OR Cod_Prodotto = 7674 OR Cod_Prodotto = 65463 OR Cod_Prodotto = 100 OR Cod_Prodotto = 4656 OR Cod_Prodotto = 454 '),
(1001, 'Chiara', 'Ferrari', 'ChiaraUNIPR', '294933', 0, 'chiara.ferrari26@studenti.unipr.it', 'Via ', 'Italia', 36985489, 'SELECT Cod_Prodotto, Cod_Categoria, Nome, Prezzo_Vendita, Scorte_Magazzino FROM Prodotto WHERE Cod_Prodotto = 234 OR Cod_Prodotto = 987 OR Cod_Prodotto = 3439 OR Cod_Prodotto = 56346 OR Cod_Prodotto = 65463 OR Cod_Prodotto = 0 '),
(1002, 'Michele ', 'Mochi', 'MicheleUNIPR', '295846', 0, 'michele.mochi@studenti.unipr.it', 'Via Piacenza', 'Italia', 392345678, 'SELECT Cod_Prodotto, Cod_Categoria, Nome, Prezzo_Vendita, Scorte_Magazzino FROM Prodotto WHERE Cod_Prodotto = 3439 OR Cod_Prodotto = 0 OR Cod_Prodotto = 100 OR Cod_Prodotto = 1011 '),
(1007, 'Giuseppe', 'Dimonte', 'Basket7', '299765', 0, 'dimogiuseppe92@gmail.com', NULL, NULL, NULL, 'SELECT Cod_Prodotto, Cod_Categoria, Nome, Prezzo_Vendita, Scorte_Magazzino FROM Prodotto WHERE Cod_Prodotto = 234 OR Cod_Prodotto = 987 OR Cod_Prodotto = 3439 OR Cod_Prodotto = 56346 OR Cod_Prodotto = 65463 OR Cod_Prodotto = 0 '),
(3454, 'root', 'root ', 'root', 'root299765', 1, 'root@rootmail.root', 'amministrazione', 'Parma ', 999999999, 'SELECT Cod_Prodotto, Cod_Categoria, Nome, Prezzo_Vendita, Scorte_Magazzino FROM Prodotto WHERE Cod_Prodotto = 234 OR Cod_Prodotto = 987 OR Cod_Prodotto = 3439 OR Cod_Prodotto = 56346 OR Cod_Prodotto = 65463 OR Cod_Prodotto = 0 ');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
