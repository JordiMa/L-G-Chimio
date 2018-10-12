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
class formulaire {
  public $name;
  public $action;
  public $type;
  public $reshtml=array();
  public $telecharge;

  function __construct ($name,$action,$type, $telecharge) {
    $this->name=$name;
    $this->action=$action;
    $this->type=$type;
    //$telecharge permet de définir si le champ fichier permet de télécharger un fichier
    //ou prend juste la valeur de l'arborescence du fichier
    $this->telecharge=$telecharge;
  }

  //permet d'ajouter un champ de type text
  function ajout_text ($size, $value, $maxlenght, $variable, $label,$label2,$onclick) {
    $champ=new champ ($variable,"text",$size,$value,$maxlenght,false,$label,$label2,$onclick,"","");
    echo $champ->imprime();
  }

  //permet d'ajouter un champ de type mot de passe
  function ajout_password ($size, $value, $maxlenght, $variable, $label,$label2) {
    $champ=new champ ($variable,"password",$size,$value,$maxlenght,false,$label,$label2,"","","");
    echo $champ->imprime();
  }

  //permet d'ajouter un champ de type caché (ou hidden)
  function ajout_cache ($value,$label) {
    $champ=new champ ($label,"hidden","",$value,"",false,"","","","","");
    echo $champ->imprime();
  }

  //permet d'ajouter  un champ de type fichier
  function ajout_file ($size, $variable, $telecharge_champ, $label,$label2) {
    $champ=new champ ($variable,"file",$size,"","",false,$label,$label2,"","","");
    echo $champ->imprime();
    //$telecharge_champ : définit oui ou non le téléchargement du fichier en
    //ajoutant enctype="multipart/form-data" dans la définition du formulaire
    $this->telecharge=$telecharge_champ;
  }

  //permet d'ajouter un champ boutton avec les 3 types (button, submit ou reset)
  //la variable $type prend 3 valeurs possibles : button, submit ou reset
  function ajout_button ($value,$label,$type,$onclick) {
    $champ=new champ ($label,$type,"",$value,"",false,"","",$onclick,"","");
    echo $champ->imprime();
  }

   //permet d'ajouter un bouton de type image
   function ajout_buttonimage ($value,$label,$type,$onclick,$adresse,$alt) {
    $champ=new champ ($label,$type,"",$value,"",false,"","",$onclick,$adresse,$alt);
    echo $champ->imprime();
  }

  //permet d'ajouter un champ de menu déroulant
  function ajout_select ($size,$variable,$option_base,$multiple,$selection,$option_vide,$label,$style,$java){
    $champ=new autre_champ ($variable,"",$size,$multiple,$option_vide,$label,$style,$option_base,$java);
    echo $champ->imprime($selection);
  }

  //permet d'ajouter un champ de texte multilignes
  function ajout_textarea ($variable,$size,$value,$hauteur,$wrap,$label){
    $champ=new champ_textarea ($variable,$size,$value,$hauteur,$wrap,$label);
    echo $champ->imprime();
  }

  //permet d'ajouter une case à cocher de type radio (sélection d'un seul critère parmis plusieurs)
  function ajout_radio ($name,$value,$coche,$label,$memevariable,$javascript){
    $champ=new champ_radio ($name,"radio",$value,$coche,$label,$memevariable,$javascript);
    echo $champ->imprime();
  }

  //permet d'ajouter une case à cocher de type checkbox (sélection multi critères)
  function ajout_checkbox ($name,$value,$coche,$label,$memevariable){
    $champ=new champ_radio ($name,"checkbox",$value,$coche,$label,$memevariable,"");
    echo $champ->imprime();
  }

  //fonction permettant d'afficher les balises de début du formulaire
  function affiche_formulaire () {
    $html="\n<form method=\"$this->type\" action=\"$this->action\"";
    if ($this->telecharge==true) $html.=" enctype=\"multipart/form-data\""; //valeur necessaire pour telecharger un fichier sur le serveur
    $html.= " name=\"$this->name\">\n";
    print "$html";
  }

  //affiche la balide de fin du formulaire
  function fin() {
    print"</form>\n";
  }
}



class champ {
  public $name_champ;
  public $type_champ;
  public $size_champ;
  public $value_champ;
  public $maxlenght_champ;
  public $coche_champ;
  public $label_champ;
  public $label2_champ;
  public $onclick_champ;
  public $adresse_champ;
  public $alt_champ;


  function __construct ($name_champ,$type_champ,$size_champ,$value_champ,$maxlenght_champ,$coche_champ,$label_champ,$label2_champ,$onclick_champ,$adresse_champ,$alt_champ) {
    $this->name_champ=$name_champ;
    $this->type_champ=$type_champ;
    $this->size_champ=$size_champ;
    $this->value_champ=$value_champ;
    $this->maxlenght_champ=$maxlenght_champ;
    $this->coche_champ=$coche_champ;
    $this->label_champ=$label_champ;
    $this->label2_champ=$label2_champ;
    $this->onclick_champ=$onclick_champ;
    $this->adresse_champ=$adresse_champ;
	$this->alt_champ=$alt_champ;
  }

  function imprime () {
      $html="";
      if (!empty($this->label_champ)) $html.="<label>$this->label_champ\n";
      $html.= "<input type=\"$this->type_champ\" name=\"$this->name_champ\"";
      if (!empty($this->size_champ)) $html.=" size=\"$this->size_champ\"";
      if ($this->value_champ!="") $html.=" value=\"$this->value_champ\"";
      if (!empty($this->maxlenght_champ)) $html.=" maxlength=\"$this->maxlenght_champ\"";
      if (!empty($this->adresse_champ)) $html.=" src=\"$this->adresse_champ\"";
      if (!empty($this->onclick_champ)) $html.=" $this->onclick_champ";
	  if (!empty($this->alt_champ)) $html.=" alt=\"$this->alt_champ\" title=\"$this->alt_champ\"";
      $html.=">\n";
      if (!empty($this->label_champ)) {
        if (!empty($this->label2_champ)) $html.="$this->label2_champ</label>\n";
        else $html.="</label>\n";
      }
      return $html;
  }
}

class autre_champ {
   public $name_champ;
   public $value_champ;
   public $size_champ;
   public $multiple_champ;
   public $label_champ;
   public $style_champ;
   public $option_base;
   public $javascript;

   function __construct ($name_champ,$value_champ,$size_champ,$multiple_champ,$option_vide,$label_champ,$style_champ,$option_base,$javascript) {
     $this->name_champ=$name_champ;
     $this->value_champ=$value_champ;
     $this->size_champ=$size_champ;
     $this->multiple_champ=$multiple_champ;
     $this->option_vide=$option_vide;
     $this->label_champ=$label_champ;
     $this->style_champ=$style_champ;
     $this->option_base=$option_base;
     $this->javascript=$javascript;
   }

   function imprime ($selection) {
    $html="";
	$conteur=0;
    if (!empty($this->label_champ))$html.="<label>$this->label_champ\n";
    $html.= "<select name=\"";
    if ($this->multiple_champ==true) $html.=$this->name_champ."[]";
    else  $html.=$this->name_champ;
    $html.="\"";
    if ($this->size_champ>0) $html.=" size=\"$this->size_champ\"";
    if ($this->multiple_champ==true) $html.=" multiple";
    if ($this->javascript!="") $html.=" ".$this->javascript;
	if (!empty($this->option_base)) {
		$value="";	
		foreach($this->option_base as $key=>$elem) {
			if ($this->style_champ) {
				if ($elem!="INCOL") {
					$conteur=1;
				}	
			}
			if ($selection==$key) $value=$elem;
		}
		if ($conteur==1) $html.=" style=\"background-color:#".$value.";\" onChange=\"this.style.backgroundColor=this.options[this.selectedIndex].style.backgroundColor\"";
	}
    $html.=">\n";
    if (!empty($this->option_vide)) $html.="<option value=\"\">-- $this->option_vide --</option>\n";
	if (!empty($this->option_base)) {	 
		foreach($this->option_base as $key=>$elem) {
			if ($this->style_champ) {
				if ($elem=="INCOL") {
					if ($selection==$key) $html.="<option value=\"$key\" selected=\"selected\">".constant($elem)."</option>\n";
					else $html.="<option value=\"$key\">".constant($elem)."</option>\n";
				}
				else {
					if ($selection==$key) $html.="<option value=\"$key\" style=\"background-color:#$elem;\" selected=\"selected\">&nbsp;</option>\n";
					else $html.="<option value=\"$key\" style=\"background-color:#$elem;\">&nbsp;</option>\n";
				}
			}
			else {
				if ($this->multiple_champ==true and !empty($selection)) {
					if (is_array($selection)) {
						if (in_array($key,$selection)) $html.="<option value=\"$key\" selected=\"selected\">$elem</option>\n";
						else $html.="<option value=\"$key\">$elem</option>\n";
					}
					else {
						if ($selection==$key) $html.="<option value=\"$key\" selected=\"selected\">$elem</option>\n";
						else $html.="<option value=\"$key\">$elem</option>\n";
					}
				}
				else {
					if ($selection==$key) $html.="<option value=\"$key\" selected=\"selected\">$elem</option>\n";
					else $html.="<option value=\"$key\">$elem</option>\n";
				}
			}
		}
	}	
		 
     $html.="</select>\n";
     if (!empty($this->label_champ)) $html.="</label>\n";
     return $html;
   }
}

class champ_textarea {
  public $name_champ;
  public $value_champ;
  public $size_champ;
  public $hauteur_champ;
  public $wrap_champ;
  public $label_champ;

  function __construct ($name_champ,$size_champ,$value_champ,$hauteur_champ,$wrap_champ,$label_champ) {
    $this->name_champ=$name_champ;
    $this->size_champ=$size_champ;
    $this->value_champ=$value_champ;
    $this->hauteur_champ=$hauteur_champ;
    $this->wrap_champ=$wrap_champ;
    $this->label_champ=$label_champ;
  }

  function imprime () {
    $html="";
    if (!empty($this->label_champ)) $html.="<label>$this->label_champ\n";
    $html.= "<textarea";
	if (!empty($this->name_champ)) $html.=" name=\"$this->name_champ\"";
    if (!empty($this->size_champ)) $html.=" cols=\"$this->size_champ\"";
    if (!empty($this->hauteur_champ)) $html.=" rows=\"$this->hauteur_champ\"";
    if (!empty($this->wrap_champ)) $html.=" wrap=\"$this->wrap_champ\"";
    $html.=">$this->value_champ</textarea>\n";
    if (!empty($this->label_champ)) $html.="</label>\n";
    return $html;
  }
}

class champ_radio {
  public $name_champ;
  public $type_champ;
  public $value_champ;
  public $coche_champ;
  public $label_champ;
  public $memevariable;
  public $javascript;

  function __construct ($name_champ,$type_champ,$value_champ,$coche_champ,$label_champ,$memevariable,$javascript) {
	$this->name_champ=$name_champ;
    $this->type_champ=$type_champ;
    $this->value_champ=$value_champ;
    $this->coche_champ=$coche_champ;
    $this->label_champ=$label_champ;
    $this->memevariable=$memevariable;
    $this->javascript=$javascript;
  }

  function imprime () {
    $html="";
    if (!empty($this->label_champ)) $html.="<label>$this->label_champ</label>\n";
    if ($this->memevariable==false) {
      $i=0;
      foreach($this->value_champ as $key=>$elem){		  
        $html.="<label class=\"chek\">";
        $html.= "<input type=\"$this->type_champ\" name=\"$this->name_champ$i\"";
        if (!empty($this->value_champ)) $html.=" value=\"$key\"";
        if (is_array($this->coche_champ) and !empty($this->coche_champ)) {
			if (in_array($key,$this->coche_champ)) $html.=" checked=\"checked\"";
        }
        elseif (is_numeric($this->coche_champ) and !empty($this->coche_champ)) {
            if ($key==$this->coche_champ) $html.=" checked=\"checked\"";
        }
        if ($this->javascript!="") $html.=" $this->javascript";
        $html.=">\n$elem<br/></label>\n";
        $i++;
      }
    }
    else {
      if (is_array($this->value_champ)) {
		$html.="<label class=\"chek\">";
        foreach($this->value_champ as $key=>$elem) {
			$html.= "<input type=\"$this->type_champ\" name=\"$this->name_champ\"";
			if (!empty($this->value_champ)) $html.=" value=\"$key\"";
			if ($this->coche_champ==$key) $html.=" checked=\"checked\"";
			if ($this->javascript!="") $html.=" $this->javascript";
			$html.=">\n$elem";
        }
		$html.="</label>\n";
      }
      else {
        $html.="<label class=\"chek\">";
			$html.= "<input type=\"$this->type_champ\" name=\"$this->name_champ\"";
			if (!empty($this->value_champ)) $html.=" value=\"$this->value_champ\"";
			if ($this->coche_champ==$this->value_champ) $html.=" checked=\"checked\"";
			if ($this->javascript!="") $html.=" $this->javascript";
			$html.=">\n$this->value_champ<br/></label>\n";
        }
    }
    return $html;
  }

}
?>