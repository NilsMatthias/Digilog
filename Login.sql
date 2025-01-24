-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: webengineering.ins.hs-anhalt.de:11012
-- Generation Time: Jan 24, 2025 at 09:09 AM
-- Server version: 8.4.0
-- PHP Version: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Login`
--

-- --------------------------------------------------------

--
-- Table structure for table `Bilder-upload`
--

CREATE TABLE `Bilder-upload` (
  `id` int NOT NULL,
  `image-path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Dateien`
--

CREATE TABLE `Dateien` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `taetigkeit_id` int NOT NULL,
  `dateiname` varchar(255) NOT NULL,
  `pfad` varchar(255) NOT NULL,
  `hochladedatum` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Durchführung`
--

CREATE TABLE `Durchführung` (
  `Durchführungs-ID` int NOT NULL,
  `Lehrer-ID` int NOT NULL,
  `User-ID` int DEFAULT NULL,
  `Tätigkeit-ID` int DEFAULT NULL,
  `Beschreibung` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `Selbstreflexion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `Bewertung` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `EPA-Bewertung` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `BÄK-Bewertung` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Abgeschlossen` tinyint(1) NOT NULL DEFAULT '0',
  `Datum` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Durchführung`
--

INSERT INTO `Durchführung` (`Durchführungs-ID`, `Lehrer-ID`, `User-ID`, `Tätigkeit-ID`, `Beschreibung`, `Selbstreflexion`, `Bewertung`, `EPA-Bewertung`, `BÄK-Bewertung`, `Abgeschlossen`, `Datum`) VALUES
(17, 34, 27, 1, 'neu', 'hh', 'Die strukturierte und präzise Erhebung der Krankengeschichte war gut durchdacht und methodisch korrekt. Es wurde ein klarer Leitfaden verfolgt, der alle relevanten Informationen einschloss, darunter: aktuelle Beschwerden, Vorerkrankungen, Medikamentenanamnese, soziale und familiäre Anamnese sowie spezifische Fragen zu Risikofaktoren.\r\nPositiv hervorzuheben ist die Fähigkeit, gezielte Rückfragen zu stellen, um Unklarheiten zu klären. Dennoch könnten in Zukunft stärkere Schwerpunkte auf das Erkennen potenzieller Red-Flags gelegt werden.', '3', '3b', 0, '2024-12-20'),
(27, 34, 27, 2, 'Instrumentarium', 'Ich habe die Dokumentation vorgenommen', 'Haben Sie gut gemacht', '1', '1', 0, '2024-12-19'),
(29, 0, 27, 4, 'Hallo', 'ja', NULL, '', '', 0, '2024-11-20'),
(30, 34, 27, 5, 'Ich habe eine Endoskopie durchgeführt', NULL, 'war ok', '1', '3a', 0, '2024-11-19'),
(31, 34, 27, 23, 'Händedesinfektion erlernt', NULL, 'War befriedigend', '1', '1', 0, '2024-11-19'),
(33, 0, 27, 6, 'durchgeführt', 'ja', NULL, '', '', 0, '2024-11-20'),
(34, 0, 27, 13, 'Ich habe einen zentralen Zugang gelegt\r\n\r\nEs war eine Herausforderung aber ich habe sie gemeistert XD', 'Überarbeitung', NULL, '', '', 0, '2024-11-21'),
(35, 0, 27, 51, 'Wurde vorbereitet', NULL, NULL, '', '', 0, '2024-11-24'),
(36, 0, 27, 47, 'visiten', 'j', NULL, '', '', 0, '2024-11-24'),
(37, 0, 27, 49, 'Kommunikation', NULL, NULL, '', '', 0, '2024-11-24'),
(38, 0, 27, 15, 'injektion', 'injektion', NULL, '', '', 0, '2024-11-24'),
(39, 0, 27, 30, 'ooo', NULL, NULL, '', '', 0, '2024-11-26'),
(40, 0, 27, 29, 'geholfen', NULL, NULL, '', '', 0, '2024-11-28'),
(41, 34, 29, 1, 'alasdfasdfjasfa gg ycvaasdfasdfasf dfadfadfasdfa', NULL, 'Das müssen Sie nochmal ausbessern. Versuchen Sie doch mal einen neuen Ansatz!', '2', '4', 0, '2024-12-03'),
(42, 34, 33, 1, 'qq', 'фф', 'Nicht genug Text, bitte noch etwas hinzu schreiben', '4', '3a', 0, '2024-12-05'),
(44, 0, 27, 33, 'sdfds', 'ahahaha', NULL, '', '', 0, '2025-01-23'),
(45, 0, 38, 1, 'war gut', 'ich würde es anders machen', NULL, '', '', 0, '2025-01-02'),
(46, 34, 40, 1, 'Ich hab och alled jemacht.', 'Fand mich jut', 'Joa ich fands och jut ich geb mal was neues ein', '4', '1', 0, '2025-01-02'),
(47, 0, 27, 50, 'asdf', NULL, NULL, NULL, NULL, 0, '2025-01-23'),
(48, 34, 27, 18, 'Habe ich gemacht, Drainagen und Stomata', 'Ich hätte mich mehr reinhängen können', 'Sehr gut', '4', '3b', 0, '2025-01-23'),
(50, 0, 38, 3, 'war gut', 'asd', NULL, NULL, NULL, 0, '2025-01-23'),
(51, 0, 27, 8, 'war echt belastend', 'habe ich aber gemeistert', NULL, NULL, NULL, 0, '2025-01-23'),
(52, 0, 27, 31, 'Aufgabe: Unterstützung bei einem minimalinvasiven Eingriff zur Behandlung von Gefäßerkrankungen.\r\n\r\n- Vorbereitung von Instrumenten und steriler Umgebung.\r\n- Assistenz bei der Gefäßpunktion, Übergabe von Materialien und Überwachung der Bildgebung.\r\n- Dokumentation und Nachbereitung des Eingriffs.\r\n\r\n\r\nhallo', 'Die Assistenz hat meine organisatorischen Fähigkeiten gestärkt und mein Verständnis für die Abläufe bei Gefäßeingriffen vertieft.\r\n\r\nStärken: Strukturierte Vorbereitung und Umsetzung der Anweisungen.\r\nVerbesserung: Mehr Übung im Umgang mit spezifischen Instrumenten und Bildgebung.', NULL, NULL, NULL, 0, '2025-01-24'),
(53, 38, 40, 20, 'asd', 'asdfasf', 'ja kann man machen', '4', '3a', 0, '2025-01-23'),
(54, 0, 40, 46, 'hallo', 'war gut', NULL, NULL, NULL, 0, '2025-01-23'),
(55, 0, 40, 30, NULL, 'haba keine boce mehre', NULL, NULL, NULL, 0, '2025-01-23'),
(56, 0, 34, 1, 'Habe Erhebung gemacht iss', NULL, NULL, NULL, NULL, 0, '2025-01-23'),
(57, 0, 27, 12, 'Dokumentation', NULL, NULL, NULL, NULL, 0, '2025-01-23'),
(58, 34, 27, 39, 'hallo', NULL, 'gut', '1', '1', 0, '2025-01-24');

-- --------------------------------------------------------

--
-- Table structure for table `Rollen`
--

CREATE TABLE `Rollen` (
  `ID-Rolle` int NOT NULL,
  `Rolle` varchar(255) NOT NULL,
  `Zugriff` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Rollen`
--

INSERT INTO `Rollen` (`ID-Rolle`, `Rolle`, `Zugriff`) VALUES
(1, 'Admin', 'Keine Einschränkung'),
(2, 'Lehrende', 'Bewerten'),
(3, 'Studierende', 'Ausführen');

-- --------------------------------------------------------

--
-- Table structure for table `Taetigkeiten`
--

CREATE TABLE `Taetigkeiten` (
  `ID` int NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Kategorie` varchar(255) DEFAULT NULL,
  `Beschreibung` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `BÄK` varchar(255) DEFAULT NULL,
  `EPA` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Taetigkeiten`
--

INSERT INTO `Taetigkeiten` (`ID`, `Name`, `Kategorie`, `Beschreibung`, `BÄK`, `EPA`) VALUES
(1, 'Erhebung der Krankengeschichte und Dokumentation in adäquater Fachterminologie', 'Mitwirkung bei der Erstellung der Diagnose und des Behandlungsplans', 'Erhebung der Krankengeschichte und Dokumentation in adäquater Fachterminologie', '3b', '5'),
(2, 'Untersuchung, auch mit Instrumentarium', 'Mitwirkung bei der Erstellung der Diagnose und des Behandlungsplans', 'Untersuchung, auch mit Instrumentarium', '3b', '5'),
(3, 'Durchführung von standardisierter Assessments, auch mittels Fragebögen', 'Mitwirkung bei der Erstellung der Diagnose und des Behandlungsplans', 'Durchführung von standardisierter Assessments, auch mittels Fragebögen', '3b', '5'),
(4, 'Symptomorientierte sonografische Untersuchungen', 'Mitwirkung bei der Erstellung der Diagnose und des Behandlungsplans', 'Symptomorientierte sonografische Untersuchungen', '3a', '4'),
(5, 'Endoskopien', 'Mitwirkung bei komplexen Untersuchungen sowie Durchführung von medizinisch-technischen Tätigkeiten', 'Endoskopien', '3a', '5'),
(6, 'Langzeitblutdruckmessungen', 'Mitwirkung bei komplexen Untersuchungen sowie Durchführung von medizinisch-technischen Tätigkeiten', 'Langzeitblutdruckmessungen', '3b', '5'),
(7, 'Ruhe- und Langzeit-EKG', 'Mitwirkung bei komplexen Untersuchungen sowie Durchführung von medizinisch-technischen Tätigkeiten', 'Ruhe- und Langzeit-EKG', '3a', '5'),
(8, 'Belastungs-EKG', 'Mitwirkung bei komplexen Untersuchungen sowie Durchführung von medizinisch-technischen Tätigkeiten', 'Belastungs-EKG', '3a', '5'),
(9, 'Funktionsdiagnostik', 'Mitwirkung bei komplexen Untersuchungen sowie Durchführung von medizinisch-technischen Tätigkeiten', 'Funktionsdiagnostik', '3a', '5'),
(10, 'Konsiluntersuchungen', 'Mitwirkung bei komplexen Untersuchungen sowie Durchführung von medizinisch-technischen Tätigkeiten', 'Konsiluntersuchungen', '3a', '4'),
(11, 'Periphere venöse und arterielle Gefäßzugänge', 'Mitwirkung bei der Ausführung eines Behandlungsplans', 'Periphere venöse und arterielle Gefäßzugänge', '3b', '5'),
(12, 'Infusionen', 'Mitwirkung bei der Ausführung eines Behandlungsplans', 'Infusionen', '3a', '5'),
(13, 'Zentrale Zugänge', 'Mitwirkung bei der Ausführung eines Behandlungsplans', 'Zentrale Zugänge', '3a', '4'),
(14, 'Periphere Injektionen', 'Mitwirkung bei der Ausführung eines Behandlungsplans', 'Periphere Injektionen', '3b', '5'),
(15, 'Zentrale Injektionen', 'Mitwirkung bei der Ausführung eines Behandlungsplans', 'Zentrale Injektionen', '3a', '5'),
(16, 'Maßnahmen zur Schmerzlinderung', 'Mitwirkung bei der Ausführung eines Behandlungsplans', 'Maßnahmen zur Schmerzlinderung', '3a', '4'),
(17, 'Wundmanagement mit Befunddokumentation in adäquater Fachterminologie', 'Mitwirkung bei der Ausführung eines Behandlungsplans', 'Wundmanagement mit Befunddokumentation in adäquater Fachterminologie', '3b', '5'),
(18, 'Drainagen und Stomata', 'Mitwirkung bei der Ausführung eines Behandlungsplans', 'Drainagen und Stomata', '3a', '4'),
(19, 'Immobilisierende und funktionelle Verbände', 'Mitwirkung bei der Ausführung eines Behandlungsplans', 'Immobilisierende und funktionelle Verbände', '3a', '5'),
(20, 'Hilfsmittel/Orthesen', 'Mitwirkung bei der Ausführung eines Behandlungsplans', 'Hilfsmittel/Orthesen', '3b', '5'),
(21, 'Steriles Abdecken', 'Mitwirkung bei Eingriffen', 'Steriles Abdecken', '3b', '5'),
(22, 'Atemwegsmanagement', 'Mitwirkung bei Eingriffen', 'Atemwegsmanagement', '3b', '3'),
(23, 'Chirurgische Händedesinfektion', 'Mitwirkung bei Eingriffen', 'Chirurgische Händedesinfektion', '3b', '5'),
(24, 'Lagern von Patienten', 'Mitwirkung bei Eingriffen', 'Lagern von Patienten', '3b', '5'),
(25, 'OP-Feld-Desinfektion', 'Mitwirkung bei Eingriffen', 'OP-Feld-Desinfektion', '3b', '5'),
(26, 'Invasive und nicht-invasive Beatmung', 'Mitwirkung bei Eingriffen', 'Invasive und nicht-invasive Beatmung', '3a', '3'),
(27, 'Narkoseeinleitung', 'Mitwirkung bei Eingriffen', 'Narkoseeinleitung', '2', '2'),
(28, 'Narkoseüberwachung', 'Mitwirkung bei Eingriffen', 'Narkoseüberwachung', '3a', '3'),
(29, 'Assistenz bei Operationen', 'Mitwirkung bei Eingriffen', 'Assistenz bei Operationen', '3b', '5'),
(30, 'Assistenz bei Endoskopien', 'Mitwirkung bei Eingriffen', 'Assistenz bei Endoskopien', '3b', '5'),
(31, 'Assistenz bei endovaskulären Eingriffen', 'Mitwirkung bei Eingriffen', 'Assistenz bei endovaskulären Eingriffen', '3b', '5'),
(32, 'Herzkatheterlabor, Deviceimplantation', 'Mitwirkung bei Eingriffen', 'Herzkatheterlabor, Deviceimplantation', '3b', '5'),
(33, 'Präparationstechniken', 'Mitwirkung bei Eingriffen', 'Präparationstechniken', '2', '5'),
(34, 'Drainagen', 'Mitwirkung bei Eingriffen', 'Drainagen', '3a', '4'),
(35, 'Blutstillung und Gerinnungsmanagement', 'Mitwirkung bei Eingriffen', 'Blutstillung und Gerinnungsmanagement', '3a', '3'),
(36, 'Osteosynthese und orthopädisch-unfallchirurgische Implantate', 'Mitwirkung bei Eingriffen', 'Osteosynthese und orthopädisch-unfallchirurgische Implantate', '2', '3'),
(37, 'Gelenkersatz', 'Mitwirkung bei Eingriffen', 'Gelenkersatz', '2', '3'),
(38, 'Gefäßersatz', 'Mitwirkung bei Eingriffen', 'Gefäßersatz', '2', '2'),
(39, 'Wundverschluss-Techniken', 'Mitwirkung bei Eingriffen', 'Wundverschluss-Techniken', '2', '5'),
(40, 'Durchführung von Wundverschlüssen', 'Mitwirkung bei Eingriffen', 'Durchführung von Wundverschlüssen', '3b', '5'),
(41, 'Kardiopulmonale Reanimation', 'Mitwirkung bei Notfallbehandlungen', 'Kardiopulmonale Reanimation', '3b', '5'),
(42, 'Erweiterte Reanimation und eCLS', 'Mitwirkung bei Notfallbehandlungen', 'Erweiterte Reanimation und eCLS', '3b', '5'),
(43, 'Triage', 'Mitwirkung bei Notfallbehandlungen', 'Triage', '3b', '5'),
(44, 'Notfallbehandlung', 'Mitwirkung bei Notfallbehandlungen', 'Notfallbehandlung', '3b', '5'),
(45, 'Schockraummanagement', 'Mitwirkung bei Notfallbehandlungen', 'Schockraummanagement', '3b', '5'),
(46, 'Disposition', 'Mitwirkung bei Notfallbehandlungen', 'Disposition', '3b', '5'),
(47, 'Visiten und ärztliche Besprechungen', 'Adressatengerechte Kommunikation und Informationsweitergabe', 'Visiten und ärztliche Besprechungen', '3b', '5'),
(48, 'Intra- und interprofessionelle Kommunikation', 'Adressatengerechte Kommunikation und Informationsweitergabe', 'Intra- und interprofessionelle Kommunikation', '3b', '5'),
(49, 'Kommunikation mit Patienten und Angehörigen', 'Adressatengerechte Kommunikation und Informationsweitergabe', 'Kommunikation mit Patienten und Angehörigen', '3b', '5'),
(50, 'Erläuterung von Diagnose, Behandlungsplan, medizinischen Maßnahmen und Compliancemanagement', 'Adressatengerechte Kommunikation und Informationsweitergabe', 'Erläuterung von Diagnose, Behandlungsplan, medizinischen Maßnahmen und Compliancemanagement', '3b', '5'),
(51, 'Vorbereitung des ärztlichen Aufklärungsgesprächs', 'Adressatengerechte Kommunikation und Informationsweitergabe', 'Vorbereitung des ärztlichen Aufklärungsgesprächs', '3b', '5'),
(52, 'Umsetzung angeordneter Untersuchungen und Maßnahmen', 'Prozessmanagement und Teamkoordination', 'Umsetzung angeordneter Untersuchungen und Maßnahmen', '3b', '5'),
(53, 'Konsiluntersuchungen', 'Prozessmanagement und Teamkoordination', 'Konsiluntersuchungen', '3b', '5'),
(54, 'Strukturierung der Einweisungsunterlagen; Vervollständigung von Unterlagen/Befunden', 'Prozessmanagement und Teamkoordination', 'Strukturierung der Einweisungsunterlagen; Vervollständigung von Unterlagen/Befunden', '3b', '5'),
(55, 'Fallbegleitung und Case Management', 'Prozessmanagement und Teamkoordination', 'Fallbegleitung und Case Management', '3b', '5'),
(56, 'Mitarbeit in klinischen Studien', 'Prozessmanagement und Teamkoordination', 'Mitarbeit in klinischen Studien', '3b', '5'),
(57, 'Dokumentation von Untersuchungen und Befunden', 'Unterstützung bei der Dokumentation', 'Dokumentation von Untersuchungen und Befunden', '3b', '5'),
(58, 'Dokumentation von Anordnungen', 'Unterstützung bei der Dokumentation', 'Dokumentation von Anordnungen', '3b', '5'),
(59, 'Dokumentation von klinischen Verläufen', 'Unterstützung bei der Dokumentation', 'Dokumentation von klinischen Verläufen', '3b', '5'),
(60, 'OP-Berichte, Epikrisen, Arztbriefe, Verlegungsberichte u. ä.', 'Unterstützung bei der Dokumentation', 'OP-Berichte, Epikrisen, Arztbriefe, Verlegungsberichte u. ä.', '3b', '5'),
(61, 'Kodierung von Diagnosen und Prozeduren', 'Unterstützung bei der Dokumentation', 'Kodierung von Diagnosen und Prozeduren', '3b', '5'),
(62, 'Externe Kommunikation, Atteste, Reha-Anträge u. ä.', 'Unterstützung bei der Dokumentation', 'Externe Kommunikation, Atteste, Reha-Anträge u. ä.', '3b', '5'),
(63, 'Kodierung mit Klassifikationssystemen', 'Unterstützung bei der Dokumentation', 'Kodierung mit Klassifikationssystemen', '3b', '5'),
(64, 'Kodierung in der Qualitätssicherung', 'Unterstützung bei der Dokumentation', 'Kodierung in der Qualitätssicherung', '3b', '5'),
(65, 'Qualitäts- und Risikomanagement', 'Unterstützung bei der Dokumentation', 'Qualitäts- und Risikomanagement', '3b', '5');

-- --------------------------------------------------------

--
-- Table structure for table `Userdaten_Hash`
--

CREATE TABLE `Userdaten_Hash` (
  `id` int NOT NULL,
  `rolle` int NOT NULL,
  `username` varchar(128) COLLATE utf8mb4_general_ci NOT NULL,
  `nachname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `vorname` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `geburtsdatum` date DEFAULT NULL,
  `profil-picture-id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Userdaten_Hash`
--

INSERT INTO `Userdaten_Hash` (`id`, `rolle`, `username`, `nachname`, `vorname`, `email`, `password_hash`, `geburtsdatum`, `profil-picture-id`) VALUES
(27, 3, 'Leo', 'Weinert', 'Leonie', 'leonie.weinert@gmail.com', '$2y$10$IBkfYqqqKsrUAbjmP9mmfuUv.dxMbHhzc8VQIGDGmdnJVtxGQtwh2', '2004-04-09', NULL),
(29, 3, 'Ente', 'Ebert', 'Erich', 'e@gmx.de', '$2y$10$K7bmbfY5NOn1.13G./Wz1uoVZHUAqUgSTu/DS4Irhok5homSad6l6', NULL, NULL),
(30, 2, 'sanschul', 'Schulze', 'Sandro', 'sandro.schulze@hs-anhalt.de', '$2y$10$07/FUK7mGHnvRWYmeQ4VTOSLiX78I9cn5ijDzz60GSojtnA4qHT5S', NULL, NULL),
(32, 2, 'bialojan_m', 'Bialojan', 'Monique', 'monique.bialojan@hs-anhalt.de', '$2y$10$npcCEFzjoRvWfOQX6LY0Guh3/TkS.2.VMpZfJpR1kHxlixfs56/XO', NULL, NULL),
(33, 3, 'Arina', 'Podoshvelieva', 'Arina', 'arinaarinaarina@gmail.com', '$2y$10$heEyrUfcQnn.o.uFfkgwzuLADAbok4CdkBJNRah2kCACJ/esCPIIa', '2004-04-28', NULL),
(34, 2, 'LeWe', 'Weinert', 'Leonie', 'leo@gmail.com', '$2y$10$5A7lSIRWqefr5DYTbJS24eEDEbFzcZ3CVzv8DAcj91sfsxZD/xw1y', '2003-12-04', NULL),
(36, 3, 'HanMai', 'Meyer', 'Lilli', 'han@web.de', '$2y$10$ieu5nTew6UQvDRQfrTto0eUuJxowsR.MrSyj8Ha9aXgBo0wVt9AXW', '2004-03-03', NULL),
(37, 3, 'Philli', 'Kai', 'Phil', 'phil@web.de', '$2y$10$kOL5HFbX/r40TfYY4ugV0ugX59dfCBAsVdfbezQY/hi1XAhtayR0W', NULL, NULL),
(38, 2, 'NilsMatthias', 'Matthias', 'Nils', 'n.matthias@gmx.de', '$2y$10$akxLc52nMY/3XmYU0eINNeh.UGejGX/wPkRTquwQRJwCOCwVv0mty', NULL, NULL),
(40, 3, 'NilsMatthias', 'Matthias', 'Nils', 'nil@mail.com', '$2y$10$oTIKTfgrLScTvBST8CL/he6OxcPSSR9kF0xI6A0F5XbLpR4MrfgTq', NULL, NULL),
(46, 3, 'Wewe', 'We', 'Leonie', 'leonie@gmail.com', '$2y$10$RT4x31EKzYhZrk36YMYVqOynJBj9khTxxmXGI1J0PnPRwSic7k7Sm', NULL, NULL),
(48, 3, 'Must49', 'Mustermann ', 'Max', 'max@gmail.com', '$2y$10$ecJ/j18d3rLtJrloJfDnVewCOP75.rneRvzTIMF2U/PiRNL.fLjp2', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Bilder-upload`
--
ALTER TABLE `Bilder-upload`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Dateien`
--
ALTER TABLE `Dateien`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Dateien_User_ID` (`user_id`),
  ADD KEY `Dateien_tätigkieit_ID` (`taetigkeit_id`);

--
-- Indexes for table `Durchführung`
--
ALTER TABLE `Durchführung`
  ADD PRIMARY KEY (`Durchführungs-ID`),
  ADD KEY `User ID` (`User-ID`),
  ADD KEY `Tätigkeiten ID` (`Tätigkeit-ID`);

--
-- Indexes for table `Rollen`
--
ALTER TABLE `Rollen`
  ADD PRIMARY KEY (`ID-Rolle`);

--
-- Indexes for table `Taetigkeiten`
--
ALTER TABLE `Taetigkeiten`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `Userdaten_Hash`
--
ALTER TABLE `Userdaten_Hash`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `rolle` (`rolle`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Bilder-upload`
--
ALTER TABLE `Bilder-upload`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Dateien`
--
ALTER TABLE `Dateien`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Durchführung`
--
ALTER TABLE `Durchführung`
  MODIFY `Durchführungs-ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `Rollen`
--
ALTER TABLE `Rollen`
  MODIFY `ID-Rolle` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `Taetigkeiten`
--
ALTER TABLE `Taetigkeiten`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `Userdaten_Hash`
--
ALTER TABLE `Userdaten_Hash`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Dateien`
--
ALTER TABLE `Dateien`
  ADD CONSTRAINT `Dateien_tätigkieit_ID` FOREIGN KEY (`taetigkeit_id`) REFERENCES `Taetigkeiten` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `Dateien_User_ID` FOREIGN KEY (`user_id`) REFERENCES `Userdaten_Hash` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT;

--
-- Constraints for table `Durchführung`
--
ALTER TABLE `Durchführung`
  ADD CONSTRAINT `Tätigkeiten ID` FOREIGN KEY (`Tätigkeit-ID`) REFERENCES `Taetigkeiten` (`ID`) ON DELETE SET NULL ON UPDATE RESTRICT,
  ADD CONSTRAINT `User ID` FOREIGN KEY (`User-ID`) REFERENCES `Userdaten_Hash` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `Userdaten_Hash`
--
ALTER TABLE `Userdaten_Hash`
  ADD CONSTRAINT `Userdaten_Hash_ibfk_1` FOREIGN KEY (`rolle`) REFERENCES `Rollen` (`ID-Rolle`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
