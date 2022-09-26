-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- 主機: localhost
-- 產生時間： 2021 年 06 月 21 日 01:31
-- 伺服器版本: 5.7.33-0ubuntu0.16.04.1
-- PHP 版本： 7.0.33-0ubuntu0.16.04.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `a1083305`
--

-- --------------------------------------------------------

--
-- 資料表結構 `Artist`
--

CREATE TABLE `Artist` (
  `aname` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `birthplace` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `age` int(11) NOT NULL,
  `style` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 資料表的匯出資料 `Artist`
--

INSERT INTO `Artist` (`aname`, `birthplace`, `age`, `style`) VALUES
('Albert Gleizes', 'France', 72, 'Cubism'),
('Andre Derain', 'France', 74, 'Fauvism'),
('Andy Warhol', 'U.S.', 58, 'Pop Art'),
('Diego Rivera', 'Mexico', 70, 'Mexican muralism'),
('Ernst Ludwig Kirchner', 'German Empire', 58, 'Expressionism'),
('Fernand Leger', 'France', 74, 'Mechanistic Cubism'),
('Francis Picabia', 'France', 74, 'Dadaism'),
('Frida Kahlo', 'Mexico', 47, 'Surrealism'),
('Georges Braque', 'France', 81, 'Cubism'),
('Gustav Klimt', 'Austrian Empire', 55, 'Symbolism'),
('Henri Matisse', 'France', 85, 'Fauvism'),
('Leonardo da Vinci', 'Republic of Florence', 67, 'Renaissance'),
('Marcel Duchamp', 'France', 81, 'Dadaism'),
('Pablo Picasso', 'Spanish', 91, 'Surrealism'),
('Paul Gauguin', 'France', 54, 'Post-Impressionism'),
('Rembrandt', 'Dutch', 63, 'Baroque'),
('Robert Rauschenberg', 'America', 59, 'Pop Art'),
('Rufino Tamayo', 'Mexico', 92, 'Expressionism'),
('Vincent van Gogh', 'Netherlands', 37, 'Post-Impressionism'),
('William Bliss Baker', 'America', 27, 'Naturalism');

-- --------------------------------------------------------

--
-- 資料表結構 `Artwork`
--

CREATE TABLE `Artwork` (
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `aname` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `year` varchar(20) NOT NULL,
  `type` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `price` float NOT NULL,
  `gname` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 資料表的匯出資料 `Artwork`
--

INSERT INTO `Artwork` (`title`, `aname`, `year`, `type`, `price`, `gname`) VALUES
('Au Lapin Agile', 'Pablo Picasso', '1904', 'Painting', 85, 'Character'),
('El Picador', 'Pablo Picasso', '1909', 'Painting', 599.9, 'Works by Pablo Picasso'),
('Fallen Monarchs', 'William Bliss Baker', '1886', 'Painting', 0.36, 'Landscape'),
('Fountain', 'Marcel Duchamp', '1917', 'Sculpture', 1.7625, 'Works by Marcel Duchamp'),
('Japonaiserie Oiran', 'Vincent van Gogh', '1887', 'Painting', 0.003, 'Works by Vincent van Gogh'),
('L. H. O. O. Q.', 'Marcel Duchamp', '1919', 'Painting', 1.205, 'Found object'),
('La Columna Rota', 'Frida Kahlo', '1944', 'Painting', 0.55, 'Self-portrait'),
('La Danse', 'Henri Matisse', '1910', 'Painting', 0.025, 'Character'),
('La femme au chapeau', 'Henri Matisse', '1905', 'Painting', 80.75, 'Works of the 20th century'),
('Las Dos Fridas', 'Frida Kahlo', '1939', 'Painting', 0.001, 'Works by Frida Kahlo'),
('Mona Lisa', 'Leonardo da Vinci', '1503', 'Painting', 2676.6, 'Works by Leonardo da Vinci'),
('Motherhood Angelina and the Child Diego', 'Diego Rivera', '1916', 'Painting', 0.02, 'Character'),
('Nude, Green Leaves and Bust', 'Pablo Picasso', '1932', 'Painting', 126.4, 'Love'),
('Otahi', 'Paul Gauguin', '1893', 'Painting', 133.3, 'Works by Paul Gauguin'),
('Pendant portraits of Maerten Soolmans', 'Rembrandt', '1634', 'Painting', 197, 'Works of the 17th century'),
('Portrait of a lady aged 62', 'Rembrandt', '1632', 'Painting', 19.8, 'Works by Rembrandt'),
('Portrait of Adele Bloch-Bauer I', 'Gustav Klimt', '1907', 'Painting', 173.3, 'Portrait'),
('Portrait of Dr. Gachet', 'Vincent van Gogh', '1890', 'Painting', 163.4, 'History'),
('Portrait of Joseph Roulin', 'William Bliss Baker', '1889', 'Painting', 125.3, 'Works of the 19th century'),
('Salvator Mundi', 'Leonardo da Vinci', '1500', 'Painting', 475.4, 'Works of the 16th century'),
('The Rivals', 'Diego Rivera', '1931', 'Painting', 9.76, 'Painting Genres'),
('Wasserschlangen II', 'Gustav Klimt', '1907', 'Painting', 204.2, 'Works by Gustav Klimt'),
('When Will You Marry', 'Paul Gauguin', '1892', 'Painting', 229, 'Love');

-- --------------------------------------------------------

--
-- 資料表結構 `Customer`
--

CREATE TABLE `Customer` (
  `cname` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `amount` float NOT NULL,
  `address` varchar(70) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 資料表的匯出資料 `Customer`
--

INSERT INTO `Customer` (`cname`, `amount`, `address`) VALUES
('Berta Armstrong', 130.9, '1994 Audrey Drive Washington, DC 20008'),
('Chance Mueller', 0.02, '222 Elmer Knoll Apt. 263 Plymouth, UT 84330'),
('Chester Berge', 54.2, '7152 Gulgowski Gateway Suite 218 Keaau, HI 96749'),
('Eufemia Riva', 1.31, 'Piazza Ione 103 Appartamento 11De Angelis lido, 66751 Trento (AL)'),
('Eusebio Huel', 0.03, '71461 Sporer Lights Suite 284 El Paso, TX 79926'),
('Franck Schneider', 86.44, '94, avenue Yves Voisin98 007 Descamps-les-Bains'),
('Gabriela Dietrich', 95.92, 'Beate-Brandl-Ring 24477406 Meppen'),
('Jacinthe Lang', 66.99, '89711 Freda Pike Suite 764New Franciscoville, YT L8C 3S9'),
('Keeley Matthews', 113.98, 'Studio 45zDennis ThroughwayJennifervilleTR16 6EU'),
('Lucie Goulet', 71.3, '80190 Chemin Hugues, Ste-Vincent-de-Taillon, NB M4I7E9'),
('Maiya Stanton', 33.71, '39692 Mertz Rapid Apt. 573 Sykesville, PA 15865'),
('Mikayla VonRueden', 24.96, '806 McGlynn Corners Willow Lake, SD 57278'),
('Mossie Parisian', 3.68, '685 Micaela Well Waterloo, OH 45688'),
('Rafael Martin', 32.45, 'Diethelm-Seeger-Platz 54b84950 Celle'),
('Thea Marchetti', 2.05, 'Rotonda Lombardi 0 Piano 1Sesto Terzo ligure, 03074 Bari (NO)'),
('Thibault Martins', 13.78, '389, rue de Gaudin44368 Monnier-les-Bains'),
('Tomasa Fisher', 36.46, '8A Kerluke PlazaNorth Otis, WA 3114'),
('Vanessa Robinson', 78.23, 'Studio 91Carter MeadowSouth ElliottmouthLE3 0BF'),
('Veda Mohr', 25.44, '7286 Israel Hill North Egremont, MA 01252'),
('Vida Hettinger', 0.09, '80898 Corkery Summit Oolitic, IN 47451');

-- --------------------------------------------------------

--
-- 資料表結構 `Customer_Like`
--

CREATE TABLE `Customer_Like` (
  `cname` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `gname` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `aname` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 資料表的匯出資料 `Customer_Like`
--

INSERT INTO `Customer_Like` (`cname`, `gname`, `aname`) VALUES
('Berta Armstrong', 'Character', 'Georges Braque'),
('Chance Mueller', 'Landscape', 'William Bliss Baker'),
('Chester Berge', 'Character', 'Francis Picabia'),
('Eufemia Riva', 'Site-specific', 'Marcel Duchamp'),
('Eusebio Huel', 'Self-portrait', 'Frida Kahlo'),
('Franck Schneider', 'Portrait', 'William Bliss Baker'),
('Gabriela Dietrich', 'Landscape', 'William Bliss Baker'),
('Jacinthe Lang', 'Works by Leonardo da Vinci', 'Leonardo da Vinci'),
('Keeley Matthews', 'Works of the 16th century', 'Leonardo da Vinci'),
('Lucie Goulet', 'Portrait', 'Vincent van Gogh'),
('Maiya Stanton', 'Portrait', 'Rembrandt'),
('Mikayla VonRueden', 'Works of the 16th century', 'Leonardo da Vinci'),
('Mossie Parisian', 'Landscape', 'Gustav Klimt'),
('Rafael Martin', 'Character', 'Gustav Klimt'),
('Thea Marchetti', 'Works of the 16th century', 'Leonardo da Vinci'),
('Thibault Martins', 'Self-portrait', 'Diego Rivera'),
('Tomasa Fisher', 'Found object', 'Marcel Duchamp'),
('Vanessa Robinson', 'Landscape', 'Paul Gauguin'),
('Veda Mohr', 'Character', 'Henri Matisse'),
('Vida Hettinger', 'Works by Picasso', 'Pablo Picasso');

-- --------------------------------------------------------

--
-- 資料表結構 `Group_Name`
--

CREATE TABLE `Group_Name` (
  `gname` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 資料表的匯出資料 `Group_Name`
--

INSERT INTO `Group_Name` (`gname`) VALUES
('Character'),
('Found object'),
('Genre painting'),
('History'),
('Landscape'),
('Love'),
('Portrait'),
('Self-portrait'),
('Works by Frida Kahlo'),
('Works by Gustav Klimt'),
('Works by Leonardo da Vinci'),
('Works by Marcel Duchamp'),
('Works by Pablo Picasso'),
('Works by Paul Gauguin'),
('Works by Rembrandt'),
('Works by Vincent van Gogh'),
('Works of the 16th century'),
('Works of the 17th century'),
('Works of the 19th century'),
('Works of the 20th century');

--
-- 已匯出資料表的索引
--

--
-- 資料表索引 `Artist`
--
ALTER TABLE `Artist`
  ADD PRIMARY KEY (`aname`);

--
-- 資料表索引 `Artwork`
--
ALTER TABLE `Artwork`
  ADD PRIMARY KEY (`title`);

--
-- 資料表索引 `Customer`
--
ALTER TABLE `Customer`
  ADD PRIMARY KEY (`cname`);

--
-- 資料表索引 `Customer_Like`
--
ALTER TABLE `Customer_Like`
  ADD PRIMARY KEY (`cname`,`gname`,`aname`);

--
-- 資料表索引 `Group_Name`
--
ALTER TABLE `Group_Name`
  ADD PRIMARY KEY (`gname`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
