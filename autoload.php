<?php
/*
Copyright Laurent ROBIN CNRS - Universit� d'Orl�ans 2011
Distributeur : UGCN - http://chimiotheque-nationale.org

Laurent.robin@univ-orleans.fr
Institut de Chimie Organique et Analytique
Universit� d�Orl�ans
Rue de Chartre � BP6759
45067 Orl�ans Cedex 2

Ce logiciel est un programme informatique servant � la gestion d'une chimioth�que de produits de synth�ses.

Ce logiciel est r�gi par la licence CeCILL soumise au droit fran�ais et respectant les principes de diffusion des logiciels libres.
Vous pouvez utiliser, modifier et/ou redistribuer ce programme sous les conditions de la licence CeCILL telle que diffus�e par le CEA,
 le CNRS et l'INRIA sur le site "http://www.cecill.info".

En contrepartie de l'accessibilit� au code source et des droits de copie, de modification et de redistribution accord�s par cette licence,
 il n'est offert aux utilisateurs qu'une garantie limit�e. Pour les m�mes raisons, seule une responsabilit� restreinte p�se sur l'auteur du
 programme, le titulaire des droits patrimoniaux et les conc�dants successifs.

A cet �gard l'attention de l'utilisateur est attir�e sur les risques associ�s au chargement, � l'utilisation, � la modification et/ou au d�veloppement
 et � la reproduction du logiciel par l'utilisateur �tant donn� sa sp�cificit� de logiciel libre, qui peut le rendre complexe � manipuler et qui le
r�serve donc � des d�veloppeurs et des professionnels avertis poss�dant des connaissances informatiques approfondies. Les utilisateurs sont donc
invit�s � charger et tester l'ad�quation du logiciel � leurs besoins dans des conditions permettant d'assurer la s�curit� de leurs syst�mes et ou de
 leurs donn�es et, plus g�n�ralement, � l'utiliser et l'exploiter dans les m�mes conditions de s�curit�.

Le fait que vous puissiez acc�der � cet en-t�te signifie que vous avez pris connaissance de la licence CeCILL, et que vous en avez accept� les
termes.
*/
function __autoload ($classe) {
	require_once 'class/'.$classe.'.class.php';
}

function suppression($dossier_traite){
	// On ouvre le dossier.
	$repertoire = opendir($dossier_traite);
	// On lance notre boucle qui lira les fichiers un par un.
	while(false !== ($fichier = readdir($repertoire))){
		// On met le chemin du fichier dans une variable simple
		$chemin = $dossier_traite."/".$fichier;
		// Les variables qui contiennent toutes les infos nécessaires.
		$infos = pathinfo($chemin);
		$extension = "";
		if (isset($infos['extension']))
			$extension = $infos['extension'];

		// On n'oublie pas LA condition sous peine d'avoir quelques surprises. :p
		if($fichier!="." && $fichier!=".." && !is_dir($chemin) && $extension != "php"){
			unlink($chemin);
		}
	}
	closedir($repertoire); // On ferme !
}
?>
