-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Pát 28. dub 2023, 13:04
-- Verze serveru: 10.4.22-MariaDB
-- Verze PHP: 8.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `gopay`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `adresa`
--

CREATE TABLE `adresa` (
  `mesto` varchar(58) NOT NULL,
  `cisloPopisne` varchar(20) NOT NULL,
  `ulice` varchar(100) DEFAULT NULL,
  `PSC` varchar(5) NOT NULL,
  `id_adresy` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `adresa`
--

INSERT INTO `adresa` (`mesto`, `cisloPopisne`, `ulice`, `PSC`, `id_adresy`) VALUES
('Havířov', '10', 'Hornická', '73802', 1),
('Havířov', '27', 'Hornická', '73802', 2);

-- --------------------------------------------------------

--
-- Struktura tabulky `objednavka`
--

CREATE TABLE `objednavka` (
  `cislo` int(11) NOT NULL,
  `id_adresy` int(11) NOT NULL,
  `id_platby` int(11) NOT NULL,
  `id_zakaznika` int(11) NOT NULL,
  `castka` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `objednavka`
--

INSERT INTO `objednavka` (`cislo`, `id_adresy`, `id_platby`, `id_zakaznika`, `castka`) VALUES
(1236554, 1, 1, 1, 12365),
(13236554, 1, 1, 1, 12365);

-- --------------------------------------------------------

--
-- Struktura tabulky `zakaznici`
--

CREATE TABLE `zakaznici` (
  `jmeno` varchar(30) NOT NULL,
  `prijmeni` varchar(30) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefon` int(11) NOT NULL,
  `id_zakaznika` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `zakaznici`
--

INSERT INTO `zakaznici` (`jmeno`, `prijmeni`, `email`, `telefon`, `id_zakaznika`) VALUES
('Matěj', 'Ondo', 'm.ondo.st@spseiostrava.cz', 602479921, 1);

-- --------------------------------------------------------

--
-- Struktura tabulky `zpusobplatby`
--

CREATE TABLE `zpusobplatby` (
  `nazev` varchar(30) NOT NULL,
  `id_platby` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `zpusobplatby`
--

INSERT INTO `zpusobplatby` (`nazev`, `id_platby`) VALUES
('CARD', 1);

--
-- Indexy pro exportované tabulky
--

--
-- Indexy pro tabulku `adresa`
--
ALTER TABLE `adresa`
  ADD PRIMARY KEY (`id_adresy`);

--
-- Indexy pro tabulku `objednavka`
--
ALTER TABLE `objednavka`
  ADD PRIMARY KEY (`cislo`),
  ADD KEY `FK_zakaznik` (`id_zakaznika`),
  ADD KEY `FK_adresa` (`id_adresy`),
  ADD KEY `FK_zpusobPlatby` (`id_platby`);

--
-- Indexy pro tabulku `zakaznici`
--
ALTER TABLE `zakaznici`
  ADD PRIMARY KEY (`id_zakaznika`);

--
-- Indexy pro tabulku `zpusobplatby`
--
ALTER TABLE `zpusobplatby`
  ADD PRIMARY KEY (`id_platby`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `adresa`
--
ALTER TABLE `adresa`
  MODIFY `id_adresy` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pro tabulku `objednavka`
--
ALTER TABLE `objednavka`
  MODIFY `cislo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2147483648;

--
-- AUTO_INCREMENT pro tabulku `zakaznici`
--
ALTER TABLE `zakaznici`
  MODIFY `id_zakaznika` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pro tabulku `zpusobplatby`
--
ALTER TABLE `zpusobplatby`
  MODIFY `id_platby` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `objednavka`
--
ALTER TABLE `objednavka`
  ADD CONSTRAINT `FK_adresa` FOREIGN KEY (`id_adresy`) REFERENCES `adresa` (`id_adresy`),
  ADD CONSTRAINT `FK_zakaznik` FOREIGN KEY (`id_zakaznika`) REFERENCES `zakaznici` (`id_zakaznika`),
  ADD CONSTRAINT `FK_zpusobPlatby` FOREIGN KEY (`id_platby`) REFERENCES `zpusobplatby` (`id_platby`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
