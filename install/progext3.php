<?php
/*
Copyright Laurent ROBIN CNRS - Université d'Orléans 2011
Distributeur : UGCN - http://chimiotheque-nationale.org

Laurent.robin@univ-orleans.fr
Institut de Chimie Organique et Analytique - ICOA UMR7311
Université d’Orléans
Rue de Chartre – BP6759
45067 Orléans Cedex 2

Ce logiciel est un programme informatique servant à la gestion d'une chimiothèque de produits de synthèses.

Ce logiciel est régi par la licence CeCILL soumise au droit français et respectant les principes de diffusion des logiciels libres.
Vous pouvez utiliser, modifier et/ou redistribuer ce programme sous les conditions de la licence CeCILL telle que diffusée par le CEA,
 le CNRS et l'INRIA sur le site "http://www.cecill.info".

En contrepartie de l'accessibilité au code source et des droits de copie, de modification et de redistribution accordés par cette licence,
 il n'est offert aux utilisateurs qu'une garantie limitée. Pour les mêmes raisons, seule une responsabilité restreinte pèse sur l'auteur du
 programme, le titulaire des droits patrimoniaux et les concédants successifs.

A cet égard l'attention de l'utilisateur est attirée sur les risques associés au chargement, à l'utilisation, à la modification et/ou au développement
 et à la reproduction du logiciel par l'utilisateur étant donné sa spécificité de logiciel libre, qui peut le rendre complexe à manipuler et qui le
réserve donc à des développeurs et des professionnels avertis possédant des connaissances informatiques approfondies. Les utilisateurs sont donc
invités à charger et tester l'adéquation du logiciel à leurs besoins dans des conditions permettant d'assurer la sécurité de leurs systèmes et ou de
 leurs données et, plus généralement, à l'utiliser et l'exploiter dans les mêmes conditions de sécurité.

Le fait que vous puissiez accéder à cet en-tête signifie que vous avez pris connaissance de la licence CeCILL, et que vous en avez accepté les
termes.
*/
include_once 'autoload.php';
$erreur="";
$ok=0;
if (!empty($_POST["servmysql"]) and !empty($_POST["loginmysql"]) and !empty($_POST["passmysql"]) and !empty($_POST["namebase"])) {
	try {
		$db = new PDO('pgsql:host='.$_POST["servmysql"].';dbname='.$_POST["namebase"].'', $_POST["loginmysql"], $_POST["passmysql"]);
		$db->exec("SET CHARACTER SET utf8");
	} catch (PDOException $excep) {
		print " Error! : ".$excep->getMessage()."<br />";
		die();
	}

	if (empty($db)) print"connexion au serveur de base de données PostgreSQL échouée";
	else {
		include_once 'structure.php';
		$req=$db->exec ($sql);
		if ($req!==false) {
			print"<li>Création des tables de la base de données réussit</li>";
			$ok++;
		}
		else {
			print"<li class=\"rouge\">Echec de la création des tables de la base de données<br/>Message(s) d'erreur :<br/>";
			print_r ($db->errorInfo());
			print"</li>";
			$ok=0;
		}
		$req1=$db->exec("INSERT INTO couleur (cou_id_couleur, cou_couleur) VALUES
													(1, 'FFFFFF'),
													(2, '000000'),
													(3, '000033'),
													(4, '000066'),
													(5, '000099'),
													(6, '0000CC'),
													(7, '0000FF'),
													(8, '003300'),
													(9, '003333'),
													(10, '003366'),
													(11, '003399'),
													(12, '0033CC'),
													(13, '0033FF'),
													(14, '006600'),
													(15, '006633'),
													(16, '006666'),
													(17, '006699'),
													(18, '0066CC'),
													(19, '0066FF'),
													(20, '009900'),
													(21, '009933'),
													(22, '009966'),
													(23, '009999'),
													(24, '0099CC'),
													(25, '0099FF'),
													(26, '00CC00'),
													(27, '00CC33'),
													(28, '00CC66'),
													(29, '00CC99'),
													(30, '00CCCC'),
													(31, '00CCFF'),
													(32, '00FF00'),
													(33, '00FF33'),
													(34, '00FF66'),
													(35, '00FF99'),
													(36, '00FFCC'),
													(37, '00FFFF'),
													(38, '330000'),
													(39, '330033'),
													(40, '330066'),
													(41, '330099'),
													(42, '3300CC'),
													(43, '3300FF'),
													(44, '333300'),
													(45, '333333'),
													(46, '333366'),
													(47, '333399'),
													(48, '3333CC'),
													(49, '3333FF'),
													(50, '336600'),
													(51, '336633'),
													(52, '336666'),
													(53, '336699'),
													(54, '3366CC'),
													(55, '3366FF'),
													(56, '339900'),
													(57, '339933'),
													(58, '339966'),
													(59, '339999'),
													(60, '3399CC'),
													(61, '3399FF'),
													(62, '33CC00'),
													(63, '33CC33'),
													(64, '33CC66'),
													(65, '33CC99'),
													(66, '33CCCC'),
													(67, '33CCFF'),
													(68, '33FF00'),
													(69, '33FF33'),
													(70, '33FF66'),
													(71, '33FF99'),
													(72, '33FFCC'),
													(73, '33FFFF'),
													(74, '660000'),
													(75, '660033'),
													(76, '660066'),
													(77, '660099'),
													(78, '6600CC'),
													(79, '6600FF'),
													(80, '663300'),
													(81, '663333'),
													(82, '663366'),
													(83, '663399'),
													(84, '6633CC'),
													(85, '6633FF'),
													(86, '666600'),
													(87, '666633'),
													(88, '666666'),
													(89, '666699'),
													(90, '6666CC'),
													(91, '6666FF'),
													(92, '669900'),
													(93, '669933'),
													(94, '669966'),
													(95, '669999'),
													(96, '6699CC'),
													(97, '6699FF'),
													(98, '66CC00'),
													(99, '66CC33'),
													(100, '66CC66'),
													(101, '66CC99'),
													(102, '66CCCC'),
													(103, '66CCFF'),
													(104, '66FF00'),
													(105, '66FF33'),
													(106, '66FF66'),
													(107, '66FF99'),
													(108, '66FFCC'),
													(109, '66FFFF'),
													(110, '990000'),
													(111, '990033'),
													(112, '990066'),
													(113, '990099'),
													(114, '9900CC'),
													(115, '9900FF'),
													(116, '993300'),
													(117, '993333'),
													(118, '993366'),
													(119, '993399'),
													(120, '9933CC'),
													(121, '9933FF'),
													(122, '996600'),
													(123, '996633'),
													(124, '996666'),
													(125, '996699'),
													(126, '9966CC'),
													(127, '9966FF'),
													(128, '999900'),
													(129, '999933'),
													(130, '999966'),
													(131, '999999'),
													(132, '9999CC'),
													(133, '9999FF'),
													(134, '99CC00'),
													(135, '99CC33'),
													(136, '99CC66'),
													(137, '99CC99'),
													(138, '99CCCC'),
													(139, '99CCFF'),
													(140, '99FF00'),
													(141, '99FF33'),
													(142, '99FF66'),
													(143, '99FF99'),
													(144, '99FFCC'),
													(145, '99FFFF'),
													(146, 'CC0000'),
													(147, 'CC0033'),
													(148, 'CC0066'),
													(149, 'CC0099'),
													(150, 'CC00CC'),
													(151, 'CC00FF'),
													(152, 'CC3300'),
													(153, 'CC3333'),
													(154, 'CC3366'),
													(155, 'CC3399'),
													(156, 'CC33CC'),
													(157, 'CC33FF'),
													(158, 'CC6600'),
													(159, 'CC6633'),
													(160, 'CC6666'),
													(161, 'CC6699'),
													(162, 'CC66CC'),
													(163, 'CC66FF'),
													(164, 'CC9900'),
													(165, 'CC9933'),
													(166, 'CC9966'),
													(167, 'CC9999'),
													(168, 'CC99CC'),
													(169, 'CC99FF'),
													(170, 'CCCC00'),
													(171, 'CCCC33'),
													(172, 'CCCC66'),
													(173, 'CCCC99'),
													(174, 'CCCCCC'),
													(175, 'CCCCFF'),
													(176, 'CCFF00'),
													(177, 'CCFF33'),
													(178, 'CCFF66'),
													(179, 'CCFF99'),
													(180, 'CCFFCC'),
													(181, 'CCFFFF'),
													(182, 'FF0000'),
													(183, 'FF0033'),
													(184, 'FF0066'),
													(185, 'FF0099'),
													(186, 'FF00CC'),
													(187, 'FF00FF'),
													(188, 'FF3300'),
													(189, 'FF3333'),
													(190, 'FF3366'),
													(191, 'FF3399'),
													(192, 'FF33CC'),
													(193, 'FF33FF'),
													(194, 'FF6600'),
													(195, 'FF6633'),
													(196, 'FF6666'),
													(197, 'FF6699'),
													(198, 'FF66CC'),
													(199, 'FF66FF'),
													(200, 'FF9900'),
													(201, 'FF9933'),
													(202, 'FF9966'),
													(203, 'FF9999'),
													(204, 'FF99CC'),
													(205, 'FF99FF'),
													(206, 'FFCC00'),
													(207, 'FFCC33'),
													(208, 'FFCC66'),
													(209, 'FFCC99'),
													(210, 'FFCCCC'),
													(211, 'FFCCFF'),
													(212, 'FFFF00'),
													(213, 'FFFF33'),
													(214, 'FFFF66'),
													(215, 'FFFF99'),
													(216, 'FFFFCC'),
													(217, 'INCOL'),
													(218, 'INCON');");
		if ($req1!==false) {
			$psql103="SELECT setval('couleur_id_seq',217);";
			$presultat103=$db->exec($psql103);
			print"<ul><li>Insertion des couleurs réussit</li></ul>";
			$ok++;
		}
		else {
			print"<ul><li class=\"rouge\">Echec de l'insertion des données dans la table couleur<br/>Message(s) d'erreur :<br/>";
			print_r ($db->errorInfo());
			print"</li></ul>";
			$ok=0;
		}
		$req2=$db->exec("INSERT INTO precaution (pre_id_precaution, pre_precaution) VALUES
										(1, 'HYGROSCOPIQUE'),
										(2, 'INFLAMABLE'),
										(3, 'INSTABLE'),
										(4, 'IRRITANT'),
										(5, 'LACRYMOGENE'),
										(6, 'HOTTE'),
										(7, 'ARGON'),
										(8, 'VOLATILE'),
										(9, 'FRIGO'),
										(10, 'ELECTROSTAT'),
										(11, 'TOXIQUE'),
										(12, 'DEGRADE'),
										(13, 'SENSIBLE');");
		if ($req2!==false) {
			$psql102="SELECT setval('precaution_pre_id_precaution_seq',13);";
			$presultat102=$db->exec($psql102);
			print"<ul><li>Insertion des données dans la table preautions réussit</li></ul>";
			$ok++;
		}
		else {
			print"<ul><li class=\"rouge\">Echec de l'insertion des données dans la table precaution<br/>Message(s) d'erreur :<br/>";
			print_r ($db->errorInfo());
			print"</li></ul>";
			$ok=0;
		}
		$req3=$db->exec("INSERT INTO solvant (sol_id_solvant, sol_solvant) VALUES
										(1, 'ACETATETYLE'),
										(2, 'ACETONE'),
										(3, 'ACETONITRILE'),
										(4, 'BENZENE'),
										(5, 'CHOLOROFORME'),
										(6, 'DICHLO'),
										(7, 'DMF'),
										(8, 'DMSO'),
										(9, 'EAU'),
										(10, 'ETHANOL'),
										(11, 'ETHERPET'),
										(12, 'ETHERETHYL'),
										(13, 'METHANOL'),
										(14, 'PYRIDINE'),
										(15, 'THF'),
										(16, 'TOLUENE'),
										(17, 'INSOLUBLE'),
										(18, 'INCONNU');");
		if ($req3!==false) {
			$psql101="SELECT setval('solvant_sol_id_solvant_seq',18);";
			$presultat101=$db->exec($psql101);
			print"<ul><li>Insertion des données dans la table solvant réussit</li></ul>";
			$ok++;
		}
		else {
			print"<ul><li class=\"rouge\">Echec de l'insertion des données dans la table solvant<br/>Message(s) d'erreur :<br/>";
			print_r ($db->errorInfo());
			print"</li></ul>";
			$ok=0;
		}
		$req4=$db->exec("INSERT INTO type (typ_id_type, typ_type, typ_initiale) VALUES
								(1, 'LIBRE', 'L'),
								(2, 'CONTRAT', 'C'),
								(3, 'BREVET', 'B');");
		if ($req4!==false) {
			$psql100="SELECT setval('type_typ_id_type_seq',3);";
			$presultat100=$db->exec($psql100);
			print"<ul><li>Insertion des données dans la table type réussit</li></ul>";
			$ok++;
		}
		else {
			print"<ul><li class=\"rouge\">Echec de l'insertion des données dans la table type<br/>Message(s) d'erreur :<br/>";
			print_r ($db->errorInfo());
			print"</li></ul>";
			$ok=0;
		}
		$psql1="create index index_structures on structure using bingo_idx (str_mol bingo.molecule);";
		$presultat1=$db->exec($psql1);
		if ($presultat1!==false) {
			echo "<ul><li>table structure est correctement indexée dans postgresql.</li></ul>";
			$ok++;
		}
		else {
			echo "<ul><li class=\"rouge\">erreur de l'indexation des données de la table structure de Postgresql!<br/>Vérifiez l'existence et la configuration de Bingo dans PostgreSQL<br/>";
			print_r ($db->errorInfo());
			print"</li></ul>";
			$ok=0;
		}
		unset ($db);
	}
	echo "</ul>";

	if ($ok!=0 and $ok==6) {
		$formulaire=new formulaire ("install3","etape5.php","POST",true);
		$formulaire->affiche_formulaire();
		$formulaire->ajout_cache ($_POST["web"],"web");
		$formulaire->ajout_cache ($_POST["reptemp"],"reptemp");
		$formulaire->ajout_cache ($_POST["repp"],"repp");
		$formulaire->ajout_cache ($_POST["servmysql"],"servmysql");
		$formulaire->ajout_cache ($_POST["passmysql"],"passmysql");
		$formulaire->ajout_cache ($_POST["loginmysql"],"loginmysql");
		$formulaire->ajout_cache ($_POST["namebase"],"namebase");
		if ($_POST["win"]==1) $formulaire->ajout_cache ($_POST["win"],"win");
		$formulaire->ajout_button ("Etape suivante","","submit","");
		$formulaire->fin();
	}
	else {
		$formulaire=new formulaire ("install3","etape4.php","POST",true);
		$formulaire->affiche_formulaire();
		$formulaire->ajout_cache ($_POST["web"],"web");
		$formulaire->ajout_cache ($_POST["reptemp"],"reptemp");
		$formulaire->ajout_cache ($_POST["repp"],"repp");
		$formulaire->ajout_cache ($_POST["servmysql"],"servmysql");
		$formulaire->ajout_cache ($_POST["passmysql"],"passmysql");
		$formulaire->ajout_cache ($_POST["loginmysql"],"loginmysql");
		$formulaire->ajout_cache ($_POST["namebase"],"namebase");
		if ($_POST["win"]==1) $formulaire->ajout_cache ($_POST["win"],"win");
		$formulaire->ajout_button ("Nouvelle tentative","new","submit","");
		$formulaire->fin();
	}
}
else include_once 'progext2.php';

?>
