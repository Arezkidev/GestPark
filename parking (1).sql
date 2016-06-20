-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Lun 20 Juin 2016 à 10:55
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `parking`
--

DELIMITER $$
--
-- Procédures
--
CREATE DEFINER=`root`@`%` PROCEDURE `PS_Admin_AttribuerPlace`(IN aIDUser INT(10)
											, IN aIDPlace INT(10)
											, IN aDmd INT(10)
                                            , IN aDateDebut datetime
                                            , IN aDateFin datetime)
BEGIN

UPDATE demandes_places
SET IDPlace = aIDPlace
	, Statut = 2
    , DateCloture = Now()
WHERE IDDemandes = aDmd;

UPDATE places_parking
SET IDUSer = aIDUser
WHERE IDPlace = aIDPlace;

INSERT INTO historique(IDPlace,IDUser,DateDebutAttribution, DateFinAttribution)
VALUES (aIDPlace, aIDUser, aDateDebut, aDateFin);

END$$

CREATE DEFINER=`root`@`%` PROCEDURE `PS_Admin_GetInfosUser`(IN aIDUser INT(8)
											, OUT aNom VARCHAR(50)
											, OUT aPrenom VARCHAR(50)
											, OUT aDateNaissance DATE
											, OUT aSexe BOOLEAN
											, OUT aMail VARCHAR(150)
											, OUT aAdressePostal VARCHAR(255)
                                            , OUT aVille VARCHAR(45)
											, OUT aCodePostal INT(5)
											, OUT aRole VARCHAR(50)
											, OUT aHandicape BOOLEAN
											, OUT aCompteConfirme BOOLEAN
											, OUT aImmat VARCHAR(10)
											, OUT aPrio INT(2))
BEGIN
	SELECT U.Nom
		, U.Prenom
		, U.DateNaissance
		, U.Sexe
		, U.AdresseMail
		, U.AdressePostal
        , U.Ville
		, U.CodePostal
		, R.Libelle
		, U.IsHandicape
		, U.IsConfirme
		, U.Immatriculation
		, U.Priorite
    INTO aNom 
		, aPrenom
		, aDateNaissance
		, aSexe
		, aMail
		, aAdressePostal
		, aCodePostal
		, aRole
		, aHandicape
		, aCompteConfirme
        , aImmat
        , aPrio
    FROM utilisateur U
    JOIN roles R ON U.IDRole = R.IDRole
    WHERE U.IDUser = aIDUser;
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `PS_Admin_ListeCompteNonConfirmer`()
BEGIN
SELECT IDUser
    , Nom
    , Prenom
    , DateNaissance
    , Sexe
    , AdresseMail
    , MDP
    , AdressePostal
    , Ville
    , CodePostal
    , IDRole
    , IsHandicape
    , IsConfirme
    , Immatriculation
    , Priorite
    FROM utilisateur
    WHERE IsConfirme = 0
	ORDER BY Nom;
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `PS_Admin_ListeReclamation`()
BEGIN
	SELECT RU.IDReclamation
			, U.Nom
            , U.Prenom
            , U.AdresseMail
			, RU.ObjetDemande
			, RU.ContenuDemande
			, RU.DateDemande
	FROM reclamation_utilisateur RU
    JOIN utilisateur U ON RU.IDUser = U.IDUser;
    
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `PS_Admin_ListeUser`()
BEGIN
	SELECT IDUser
    , Nom
    , Prenom
    , DateNaissance
    , Sexe
    , AdresseMail
    , MDP
    , AdressePostal
    , Ville
    , CodePostal
    , IDRole
    , IsHandicape
    , IsConfirme
    , Immatriculation
    , Priorite
    FROM utilisateur
	ORDER BY Priorite, Nom;
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `PS_Dmd_GetLastDmd`(IN aIDUser int(8)
									, OUT aIDDemandes int(8)
                                    , OUT aIDPlace int(8)
                                    , OUT aStatut varchar(15)
                                    , OUT aDateDemande date)
BEGIN
	SELECT D.IDDemandes, D.IDPlace, S.LibelleStatut_Dmd, D.DateDemande 
    INTO aIDDemandes, aIDPlace, aStatut, aDateDemande
    FROM statut_dmd S
    INNER JOIN demandes_places D ON S.idStatut_Dmd = D.Statut
    WHERE D.IDUser = aIDUser
        AND D.LibelleStatut_Dmd = "En cours";
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `PS_Dmd_Update`(IN aIDDemandes int(8)
								, IN aStatut int(8)
                                , IN aDateCloture date)
BEGIN
	UPDATE demandes_places 
    SET Statut = aStatut, DateCloture = aDateCloture
    WHERE IDDemandes = aIDDemandes;
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `PS_Parking_Delete`(IN aIDPlace int(8))
BEGIN
	DELETE FROM places_parking WHERE IDPlace = aIDPlace;
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `PS_Parking_GetInfos`(IN aIDPlace int(8)
										, OUT aNumeroPlaces int(8)
										, OUT aNumeroAllee int(3)
										, OUT aIsPlaceHandicape tinyint(1)
										, OUT aIDUser int(8))
BEGIN
	SELECT U.NumeroPlaces
    , U.NumeroAllee
    , U.IsPlaceHandicape
    , U.IDUser
    INTO aNumeroPlaces
        , aNumeroAllee
        , aIsPlaceHandicape
        , aIDUser
    FROM places_parking U
    WHERE U.IDPlace = aIDPlace;
    -- call PS_Parking_GetInfos(1, @aNumeroPlaces, @aNumeroAllee, @aIsPlaceHandicape, @aIDUser);
	-- select @aNumeroPlaces, @aNumeroAllee, @aIsPlaceHandicape, @aIDUser;
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `PS_Parking_Liste`()
BEGIN
	SELECT NumeroPlaces,
    NumeroAllee,
    IsPlaceHandicape,
    IDUser
    FROM places_parking
	ORDER BY NumeroPlaces, NumeroAllee;
    -- call PS_Parking_Liste();
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `PS_Parking_New`(IN aNumeroPlaces int(8)
															, IN aNumeroAllee int(3)
                                                            , IN aIsPlaceHandicape boolean)
BEGIN
	INSERT INTO places_parking (NumeroPlaces, NumeroAllee, IsPlaceHandicape)
    VALUES (aNumeroPlaces, aNumeroAllee, aIsPlaceHandicape);
    -- call PS_Parking_New(1, 1, 0);
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `PS_Parking_Update`(IN aIDPlace int(8)
                                                        , IN aNumeroPlaces int(8)
                                                        , IN aNumeroAllee int(3)
                                                        , IN aIsPlaceHandicape tinyint(1))
BEGIN
	UPDATE places_parking 
    SET NumeroPlaces = aNumeroPlaces, 
		NumeroAllee = aNumeroAllee, 
		IsPlaceHandicape = aIsPlaceHandicape
    WHERE IDPlace = aIDPlace;
    -- call PS_Parking_Update(1, 1, 1, 0);
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `PS_Projet_Recherche`(IN aValue VARCHAR(50))
BEGIN
DECLARE cmd VARCHAR(255);
SET @SQL = CONCAT("SELECT * FROM utilisateur WHERE Nom LIKE '%", aValue, "%' 
													OR Prenom LIKE '%" , aValue, "%' 
                                                    OR DateNaissance LIKE '%" , aValue, "%' 
                                                    OR Sexe LIKE '%" , aValue, "%' 
                                                    OR AdresseMail LIKE '%" , aValue, "%' 
                                                    OR AdressePostal LIKE '%" , aValue, "%' 
                                                    OR CodePostal LIKE '%" , aValue, "%' 
                                                    OR Immatriculation LIKE '%" , aValue, "%'");
PREPARE cmd FROM @SQL;
EXECUTE cmd;
DEALLOCATE PREPARE cmd;

END$$

CREATE DEFINER=`root`@`%` PROCEDURE `PS_Reclamation_New`(IN aIDUser INT(10)
										, IN aObjetDemande VARCHAR(50)
                                        , IN aContenuDemande VARCHAR(255))
BEGIN
INSERT INTO reclamation_utilisateur (IDUser, ObjetDemande, ContenuDemande, DateDemande)
VALUES (aIDUser, aObjetDemande,aContenuDemande, NOW());
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `PS_User_Connexion`(IN aMail VARCHAR(150), 
									IN aPassword VARCHAR(10), 
									OUT aID INT(10))
BEGIN
	SELECT IDUSer INTO aID
	FROM utilisateur 
	WHERE AdresseMail = aMail
		and MDP = aPassword
		and IsConfirme = 1;
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `PS_User_Delete`(IN aIDUser int(8))
BEGIN
	DELETE FROM utilisateur WHERE IDUser = aIDUser;
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `PS_User_Dmd`(IN aIDUser int(8)
									, OUT aStatut varchar(15))
BEGIN
	SELECT LibelleStatut_Dmd FROM statut_dmd
    INNER JOIN demandes_places ON statut_dmd.idStatut_Dmd = demandes_places.Statut
    WHERE demandes_places.IDUser = aIDUser
        AND demandes_places.LibelleStatut_Dmd = "En cours";
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `PS_User_GetInfos`(IN aIDUser INT(8)
														, OUT aNom VARCHAR(50)
                                                        , OUT aPrenom VARCHAR(50)
                                                        , OUT aDateNaissance DATE
                                                        , OUT aSexe BOOLEAN
                                                        , OUT aMail VARCHAR(150)
                                                        , OUT aMDP VARCHAR(50)
                                                        , OUT aAdressePostal VARCHAR(255)
                                                        , OUT aVille VARCHAR(45)
                                                        , OUT aCodePostal INT(5)
                                                        , OUT aRole VARCHAR(50)
                                                        , OUT aHandicape BOOLEAN
                                                        , OUT aCompteConfirme BOOLEAN
                                                        , OUT aImmat VARCHAR(10)
                                                        , OUT aPrio INT(2))
BEGIN
	SELECT U.Nom
    , U.Prenom
    , U.DateNaissance
    , U.Sexe
    , U.AdresseMail
    , U.MDP
    , U.AdressePostal
    , U.Ville
    , U.CodePostal
    , R.Libelle
    , U.IsHandicape
    , U.IsConfirme
    , U.Immatriculation
    , U.Priorite
    INTO aNom 
		, aPrenom
		, aDateNaissance
		, aSexe
		, aMail
		, aMDP
		, aAdressePostal
		, aCodePostal
		, aRole
		, aHandicape
		, aCompteConfirme
        , aImmat
        , aPrio
    FROM utilisateur U
    JOIN roles R ON U.IDRole = R.IDRole
    WHERE U.IDUser = aIDUser;
    -- call parking.Get_Infos_Test(, @aNom, @aPrenom, @aDateNaissance, @aSexe, @aMail, @aMDP, @aAdressePostal, @aCodePostal, @aRole, @aHandicape, @aCompteConfirme, @aImmat, @aPrio);
	-- select @aNom, @aPrenom, @aDateNaissance, @aSexe, @aMail, @aMDP, @aAdressePostal, @aCodePostal, @aRole, @aHandicape, @aCompteConfirme, @aImmat, @aPrio;

END$$

CREATE DEFINER=`root`@`%` PROCEDURE `PS_User_GetLastDmd`(IN aIDUser int(8)
									, OUT aIDDemandes int(8)
                                    , OUT aIDPlace int(8)
                                    , OUT aStatut varchar(15)
                                    , OUT aDateDemande date)
BEGIN
	SELECT D.IDDemandes, D.IDPlace, S.LibelleStatut_Dmd, D.DateDemande 
    INTO aIDDemandes, aIDPlace, aStatut, aDateDemande
    FROM statut_dmd S
    INNER JOIN demandes_places D ON S.idStatut_Dmd = D.Statut
    WHERE D.IDUser = aIDUser
		AND MAX(D.DateDemande) = aDateDamnde
        AND D.LibelleStatut_Dmd = "En cours";
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `PS_User_GetPlace`(IN aIDUser INT(8)
									, OUT aNumeroPlaces INT(10)
									, OUT aNumeroAllee INT(10))
BEGIN
	SELECT PP.NumeroPlaces
			, PP.NumeroAllee
	INTO aNumeroPlaces 
			, aNumeroAllee
    FROM places_parking PP 
    JOIN utilisateur U ON PP.IDUser = U.IDUser
    WHERE U.IDUser = aIDUser;
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `PS_User_HistoDmd`(IN aIDUser int(8)
									, OUT aIDDemandes int(8)
                                    , OUT aIDPlace int(8)
                                    , OUT aStatut varchar(15)
                                    , OUT aDateDemande date)
BEGIN
	SELECT D.IDDemandes, D.IDPlace, S.LibelleStatut_Dmd, D.DateDemande 
    INTO aIDDemandes, aIDPlace, aStatut, aDateDemande
    FROM statut_dmd S
    INNER JOIN demandes_places D ON S.idStatut_Dmd = D.Statut
    WHERE D.IDUser = aIDUser
        AND D.LibelleStatut_Dmd != "En cours";
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `PS_User_HistoPlace`(IN aID INT(10))
BEGIN
	SELECT * 
	FROM HISTORIQUE 
	WHERE IDUSer = aID
		and DateFinAttribution <= NOW();
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `PS_User_New`(IN aNom VARCHAR(50)
                                                        , IN aPrenom VARCHAR(50)
                                                        , IN aDateNaissance DATE
                                                        , IN aSexe BOOLEAN
                                                        , IN aMail VARCHAR(150)
                                                        , IN aMDP VARCHAR(50)
                                                        , IN aAdressePostal VARCHAR(255)
                                                        , IN aVille VARCHAR(45)
                                                        , IN aCodePostal INT(5)
                                                        , IN aRole VARCHAR(50)
                                                        , IN aHandicape BOOLEAN
                                                        , IN aCompteConfirme BOOLEAN
                                                        , IN aImmatriculation VARCHAR(10)
                                                        , IN aPriorite INT(2))
BEGIN
	declare  id INT(2);
    SELECT IDRole INTO id FROM roles WHERE Libelle = aRole;
	INSERT INTO utilisateur(Nom
    , Prenom
    , DateNaissance
    , Sexe
    , AdresseMail
    , MDP
    , AdressePostal
    , Ville
    , CodePostal
    , IDRole
    , IsHandicape
    , IsConfirme
    , Immatriculation
    , Priorite)
    VALUES (aNom 
		, aPrenom
		, aDateNaissance
		, aSexe
		, aMail
		, aMDP
		, aAdressePostal
        , aVille
		, aCodePostal
		, id
		, aHandicape
		, aCompteConfirme
        , aImmatriculation
        , aPriorite);
        -- call PS_User_UPDATE("Patrick", "Jean", "1975-05-24", 1, "pascal.com", 12345, "5 rue de la flote", 48002, "Externe", 0, 0, "WS500AZ", 1);
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `PS_User_Update`(IN aIDUser int(8)
														, IN aNom VARCHAR(50)
                                                        , IN aPrenom VARCHAR(50)
                                                        , IN aDateNaissance DATE
                                                        , IN aSexe BOOLEAN
                                                        , IN aMail VARCHAR(255)
                                                        , IN aPassword VARCHAR(50)
                                                        , IN aAdressePostal VARCHAR(255)
                                                        , IN aVille VARCHAR(45)
                                                        , IN aCodePostal INT(5)
                                                        , IN aRole VARCHAR(50)
                                                        , IN aHandicape BOOLEAN
                                                        , IN aCompteConfirme BOOLEAN
                                                        , IN aImmatriculation VARCHAR(10)
                                                        , IN aPriorite INT(2))
BEGIN
	DECLARE id INT(2);
    SELECT IDRole INTO id FROM roles WHERE Libelle = aRole;
	UPDATE utilisateur 
    SET Nom = aNom, 
		Prenom = aPrenom, 
		DateNaissance = aDateNaissance, 
		Sexe = aSexe, 
		AdresseMail = aMail, 
		MDP = aPassword, 
		AdressePostal = aAdressePostal,
        Ville = aVille,
		CodePostal = aCodePostal, 
		IDRole = id, 
		IsHandicape = aHandicape, 
		IsConfirme = aCompteConfirme,
        Immatriculation = aImmatriculation,
        Priorite = aPriorite
    WHERE IDUser = aIDUser;
    -- call PS_User_UPDATE(12, "Patrick", "Jean", "1975-05-24", 1, "pascal.com", 12345, "5 rue de la flote", 48002, "Externe", 0, 0, "WS500AZ", 1);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `archives_user`
--

CREATE TABLE IF NOT EXISTS `archives_user` (
  `IDUser` int(8) NOT NULL AUTO_INCREMENT,
  `Nom` varchar(50) NOT NULL,
  `Prenom` varchar(50) NOT NULL,
  `DateNaissance` date DEFAULT NULL,
  `Sexe` tinyint(1) DEFAULT NULL,
  `AdresseMail` varchar(255) NOT NULL,
  `Password` varchar(15) NOT NULL,
  `AdressePostal` varchar(255) DEFAULT NULL,
  `CodePostal` int(5) DEFAULT NULL,
  `IDRole` int(2) DEFAULT NULL,
  `IsHandicape` tinyint(1) DEFAULT NULL,
  `Immatriculation` varchar(10) DEFAULT NULL,
  `Priorite` int(2) DEFAULT NULL,
  `Date_Suppression` date NOT NULL,
  PRIMARY KEY (`IDUser`),
  KEY `IDRole_idx` (`IDRole`),
  KEY `IDPrio_idx` (`Priorite`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `demandes_places`
--

CREATE TABLE IF NOT EXISTS `demandes_places` (
  `IDDemandes` int(8) NOT NULL AUTO_INCREMENT,
  `IDUser` int(8) NOT NULL,
  `IDPlace` int(8) DEFAULT NULL,
  `Statut` int(11) NOT NULL,
  `DateDemande` date DEFAULT NULL,
  `DateCloture` date DEFAULT NULL,
  PRIMARY KEY (`IDDemandes`),
  KEY `FK_Statut_idx` (`Statut`),
  KEY `FK_DEMANDES_PLACES_IDUser_UTILISATEUR` (`IDUser`),
  KEY `FK_DEMANDES_PLACES_IDPlace` (`IDPlace`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Contenu de la table `demandes_places`
--

INSERT INTO `demandes_places` (`IDDemandes`, `IDUser`, `IDPlace`, `Statut`, `DateDemande`, `DateCloture`) VALUES
(2, 34, 4, 1, '2016-02-29', NULL),
(9, 10, 5, 1, '2016-03-01', NULL),
(10, 35, NULL, 1, '2016-03-01', NULL),
(14, 36, NULL, 1, '2016-03-01', NULL),
(15, 42, 15, 1, '2016-03-01', NULL),
(16, 43, 7, 1, '2016-03-02', NULL),
(18, 45, 12, 1, '2016-03-07', NULL),
(19, 46, 13, 1, '2016-03-08', NULL),
(20, 47, 14, 1, '2016-03-08', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `historique`
--

CREATE TABLE IF NOT EXISTS `historique` (
  `IDHistorique` int(11) NOT NULL AUTO_INCREMENT,
  `IDPlace` int(11) NOT NULL,
  `IDUser` int(8) NOT NULL,
  `DateDebutAttribution` date DEFAULT NULL,
  `DateFinAttribution` date DEFAULT NULL,
  PRIMARY KEY (`IDHistorique`),
  KEY `IDUser_idx` (`IDUser`),
  KEY `IDPlace` (`IDPlace`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `places_parking`
--

CREATE TABLE IF NOT EXISTS `places_parking` (
  `IDPlace` int(8) NOT NULL AUTO_INCREMENT,
  `NumeroPlaces` int(8) NOT NULL,
  `NumeroAllee` int(3) NOT NULL,
  `IsPlaceHandicape` tinyint(1) DEFAULT NULL,
  `IDUser` int(8) DEFAULT NULL,
  PRIMARY KEY (`IDPlace`),
  KEY `IDUser_idx` (`IDUser`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Contenu de la table `places_parking`
--

INSERT INTO `places_parking` (`IDPlace`, `NumeroPlaces`, `NumeroAllee`, `IsPlaceHandicape`, `IDUser`) VALUES
(1, 1, 1, 0, 33),
(3, 2, 1, 0, 34),
(4, 3, 1, 0, 10),
(5, 4, 1, NULL, 10),
(6, 5, 1, NULL, 43),
(7, 6, 1, NULL, 43),
(11, 10, 1, NULL, 45),
(12, 11, 2, NULL, 45),
(13, 12, 2, NULL, 46),
(14, 13, 2, NULL, 47),
(15, 14, 2, NULL, 42),
(16, 15, 2, NULL, NULL),
(17, 16, 2, NULL, NULL),
(18, 17, 2, NULL, NULL),
(19, 18, 2, NULL, NULL),
(20, 19, 2, NULL, NULL),
(21, 20, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `priorite`
--

CREATE TABLE IF NOT EXISTS `priorite` (
  `idPriorite` int(11) NOT NULL AUTO_INCREMENT,
  `LibellePriorite` varchar(45) NOT NULL,
  PRIMARY KEY (`idPriorite`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `priorite`
--

INSERT INTO `priorite` (`idPriorite`, `LibellePriorite`) VALUES
(1, 'Faible'),
(2, 'Forte'),
(3, 'Urgent');

-- --------------------------------------------------------

--
-- Structure de la table `reclamation_utilisateur`
--

CREATE TABLE IF NOT EXISTS `reclamation_utilisateur` (
  `IDReclamation` int(5) NOT NULL AUTO_INCREMENT,
  `IDUser` int(8) NOT NULL,
  `ObjetDemande` varchar(150) DEFAULT NULL,
  `ContenuDemande` text,
  `DateDemande` date DEFAULT NULL,
  PRIMARY KEY (`IDReclamation`),
  KEY `FK_RECLAMATION_UTILISATEUR_IDUser_UTILISATEUR` (`IDUser`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `IDRole` int(2) NOT NULL AUTO_INCREMENT,
  `Libelle` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`IDRole`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `roles`
--

INSERT INTO `roles` (`IDRole`, `Libelle`) VALUES
(1, 'Administrateur'),
(2, 'Interne'),
(3, 'Externe');

-- --------------------------------------------------------

--
-- Structure de la table `statut_dmd`
--

CREATE TABLE IF NOT EXISTS `statut_dmd` (
  `idStatut_Dmd` int(11) NOT NULL AUTO_INCREMENT,
  `LibelleStatut_Dmd` varchar(45) NOT NULL,
  PRIMARY KEY (`idStatut_Dmd`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `statut_dmd`
--

INSERT INTO `statut_dmd` (`idStatut_Dmd`, `LibelleStatut_Dmd`) VALUES
(1, 'En cours'),
(2, 'Validée'),
(3, 'Refusée');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE IF NOT EXISTS `utilisateur` (
  `IDUser` int(8) NOT NULL AUTO_INCREMENT,
  `Nom` varchar(50) NOT NULL,
  `Prenom` varchar(50) NOT NULL,
  `DateNaissance` date DEFAULT NULL,
  `Sexe` tinyint(1) DEFAULT NULL,
  `AdresseMail` varchar(255) NOT NULL,
  `MDP` varchar(50) DEFAULT NULL,
  `AdressePostal` varchar(255) DEFAULT NULL,
  `Ville` varchar(45) DEFAULT NULL,
  `CodePostal` int(5) DEFAULT NULL,
  `IDRole` int(2) DEFAULT NULL,
  `IsHandicape` tinyint(1) DEFAULT NULL,
  `IsConfirme` tinyint(1) DEFAULT NULL,
  `Immatriculation` varchar(10) DEFAULT NULL,
  `Priorite` int(2) DEFAULT NULL,
  PRIMARY KEY (`IDUser`),
  KEY `FK_IDRole_idx` (`IDRole`),
  KEY `FK_Priorite_idx` (`Priorite`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=49 ;

--
-- Contenu de la table `utilisateur`
--

INSERT INTO `utilisateur` (`IDUser`, `Nom`, `Prenom`, `DateNaissance`, `Sexe`, `AdresseMail`, `MDP`, `AdressePostal`, `Ville`, `CodePostal`, `IDRole`, `IsHandicape`, `IsConfirme`, `Immatriculation`, `Priorite`) VALUES
(1, 'Test', 'Admin', '2000-01-01', 1, 'aimee.elmkies@outlook.com', 'demo1234', '2 rue du cerfal', NULL, 75014, 1, 0, 1, 'number1', 1),
(10, 'Test', 'Interne', '1985-01-01', 0, 'aimee.elmkies@gmail.com', 'demo1234', '2 rue du cerfal', NULL, 75014, 2, 0, 1, 'ABD12354', 1),
(11, 'Test', 'Extenre', '1978-01-01', 0, 'micaelafonso@free.fr', 'demo1234', '2 rue du cerfal', NULL, 75014, 3, 0, 0, 'BCD6854', 1),
(12, 'Patrick', 'Jean', '1975-05-24', 1, 'pascal.com', '12345', '5 rue de la flote', NULL, 48002, 3, 0, 0, 'WS500AZ', 1),
(33, 'Daheur', 'Ares', '1995-08-20', 1, 'test@test.fr', 'demo', '30 rue henri simon', 'versailles', 78000, 2, 0, 1, 'WZ457DU', 1),
(34, 'Taghboulit', 'Arezki', '1995-08-20', 1, 'ataghboulit@yahoo.fr', 'demo', '30 rue henri simon', 'versailles', 78000, 2, 0, 0, 'WZ457DU', 1),
(35, 'Rouger', 'timmy', '1587-12-20', 1, 'test@yahoo.fr', 'demo', '24 avenue wai', 'Nanterre', 92000, 2, 0, 0, 'QE121ZA', 1),
(36, 'Walter', 'john', '1987-07-14', 1, 'aaa@yahoo.fr', 'demo', '74 place des loups', 'JESaisPas', 91400, 2, 0, 0, 'OP548MZ', 1),
(39, 'Seat', 'Ibiza', '1987-08-05', 1, 'ataghboulit@yahoo.fr', 'xed', '24 avenue wai', 'Creteil', 94000, 2, 0, 0, 'eeeee', 1),
(42, 'Belle', 'ValÃ©rie', '1992-05-14', 0, 'test@femme.fr', 'demo', '22 avenue des champs Ã©lysÃ©e', 'Paris', 75008, 2, 0, 1, 'SSW478CV', 1),
(43, 'tourcoing', 'giselle', '1847-11-02', 0, 'nord@nord.fr', 'demo', '2 avenue du nord', 'lille', 59000, 2, 0, 1, 'AA666AA', 1),
(45, 'riesco', 'maxime', '1996-02-16', 1, 'test@max.fr', 'demo', '37 rue marcelin ', 'yerres', 91330, 2, 0, 1, 'BN-870-CL', 1),
(46, 'lou', 'loulou', '1856-09-10', 0, 'test@beauf.fr', 'demo', '32 rue des beaufs', 'Nord', 59000, 2, 0, 1, 'WZ457DU', 1),
(47, 'test', 'testtest', '1989-04-15', 1, 'test@a.fr', 'demo', '2 rue ahaha', 'Paris', 75002, 2, 0, 1, 'WZ457DA', 1),
(48, 'touf', 'ik', '1987-05-24', 1, 'a@a.fr', 'demo', '24 rue de la botte', 'Val -de-Marne', 94780, 2, 0, 0, 'ZE547Pz', 1);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `archives_user`
--
ALTER TABLE `archives_user`
  ADD CONSTRAINT `IDPrio` FOREIGN KEY (`Priorite`) REFERENCES `priorite` (`idPriorite`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `IDRole` FOREIGN KEY (`IDRole`) REFERENCES `roles` (`IDRole`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `demandes_places`
--
ALTER TABLE `demandes_places`
  ADD CONSTRAINT `FK_DEMANDES_PLACES_IDPlace` FOREIGN KEY (`IDPlace`) REFERENCES `places_parking` (`IDPlace`),
  ADD CONSTRAINT `FK_DEMANDES_PLACES_IDUser_UTILISATEUR` FOREIGN KEY (`IDUser`) REFERENCES `utilisateur` (`IDUser`),
  ADD CONSTRAINT `FK_Statut` FOREIGN KEY (`Statut`) REFERENCES `statut_dmd` (`idStatut_Dmd`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `historique`
--
ALTER TABLE `historique`
  ADD CONSTRAINT `IDPlace` FOREIGN KEY (`IDPlace`) REFERENCES `places_parking` (`IDPlace`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `IDUser` FOREIGN KEY (`IDUser`) REFERENCES `utilisateur` (`IDUser`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `places_parking`
--
ALTER TABLE `places_parking`
  ADD CONSTRAINT `FK_IDUser` FOREIGN KEY (`IDUser`) REFERENCES `utilisateur` (`IDUser`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `reclamation_utilisateur`
--
ALTER TABLE `reclamation_utilisateur`
  ADD CONSTRAINT `FK_RECLAMATION_UTILISATEUR_IDUser_UTILISATEUR` FOREIGN KEY (`IDUser`) REFERENCES `utilisateur` (`IDUser`);

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `FK_IDPriorite` FOREIGN KEY (`Priorite`) REFERENCES `priorite` (`idPriorite`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_IDRole` FOREIGN KEY (`IDRole`) REFERENCES `roles` (`IDRole`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
