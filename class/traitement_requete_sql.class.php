<?php
/*
Copyright Laurent ROBIN CNRS - Université d'Orléans 2011 
Distributeur : UGCN - http://chimiotheque-nationale.enscm.fr

Laurent.robin@univ-orleans.fr
Institut de Chimie Organique et Analytique
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
class traitement_requete_sql {
  
  public $resultatsql;
  
  function __construct ($resultatsql) {
    $this->resultatsql=$resultatsql;
  }

  function imprime() {
    $tab=explode("'",$this->resultatsql);
	foreach ($tab as $key=>$value) {
		if ($key%2==1 and !preg_match('/ARRAY/',$value)) {
			$tab1[$value]=constant ($value);
		}			
	}
	/*if (preg_match('/enum|set/',$this->resultatsql)) {
      //supprime 'enum(' et ')' ou 'set (' et les '
      $this->resultatsql= str_replace('enum(','',$this->resultatsql);
      $this->resultatsql= str_replace(')','',$this->resultatsql);
      $this->resultatsql= str_replace('\'','',$this->resultatsql);
      $this->resultatsql= str_replace('set(','',$this->resultatsql);
      //sépare à partir de la , les élèments  qui son sous la forme xxx,zzz,yyy et renvoie les valeurs dans un tableau
      $tab=explode(",",$this->resultatsql);
      //change de tableau pour avoir une clès de tableau identique à la valeur
      foreach ($tab as $elem) {
        $tab1[$elem]=constant ($elem);
      }
    }
    elseif (preg_match('/varchar/',$this->resultatsql)) {
		//supprime 'varchar(' et')'
		$tab1= str_replace('varchar(','',$this->resultatsql);
		$tab1= str_replace(')','',$tab1);
    }
    elseif (preg_match('/smallint/',$this->resultatsql)) {
		//supprime 'smallint(' et ')'
		$tab1= str_replace('smallint(','',$this->resultatsql);
		$tab1= str_replace(')','',$tab1);
		if (preg_match('/unsigned/',$tab1)) {
		  $tab1= str_replace ('unsigned','',$tab1);
		  $tab1= trim($tab1);
		}
    }
    elseif (preg_match('/decimal/',$this->resultatsql)) {
		//supprime 'decimal(' et ',nombre)'
		$tab1= str_replace ('decimal(','',$this->resultatsql);
		$tab1= str_replace ('[0-9])','',$tab1);
		if (preg_match('/unsigned/',$tab1)) {
		  $tab1= str_replace ('unsigned','',$tab1);
		  $tab1= trim($tab1);
		}
    }
    elseif (preg_match('/int/',$this->resultatsql)) {
		//supprime 'int(' et ')'
		$tab1= str_replace('int(','',$this->resultatsql);
		$tab1= str_replace(')','',$tab1);
		if (preg_match('/unsigned/',$tab1)) {
		  $tab1= str_replace ('unsigned','',$tab1);
		  $tab1= trim($tab1);
		}
    }*/
    return $tab1;
  }
}
?>