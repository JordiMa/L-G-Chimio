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
include_once '../script/secure.php';
include_once '../protection.php';
include_once '../langues/'.$_SESSION['langue'].'/presentation.php';
echo "<tr>
    <td bgcolor=\"#FFFFFF\" align=\"center\" valign=\"middle\" width=\"100%\" colspan=\"2\">";

require '../script/connectionb.php';

$sql="SELECT chi_statut FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
//les résultats sont retournés dans la variable $result
$resulta = $dbh->query($sql);
$row = $resulta->fetch(PDO::FETCH_NUM);
$i=0;
if ($row[0]=='{ADMINISTRATEUR}') {

  $update ="
  BEGIN;

  CREATE Table IF NOT EXISTS champsAnnexe (
    ID SERIAL PRIMARY KEY,
    HTML CHARACTER VARYING(500) NOT NULL
  );

  CREATE Table IF NOT EXISTS champsProduit (
    pro_id_produit INTEGER NOT NULL references Produit(pro_id_produit),
    cha_ID INTEGER NOT NULL references champsAnnexe(ID),
    data CHARACTER VARYING(500),
    PRIMARY KEY (pro_id_produit, cha_ID)
  );

  CREATE TABLE IF NOT EXISTS Pays (
    pay_code_pays CHARACTER VARYING(3) PRIMARY KEY,
    pay_pays CHARACTER VARYING(55) NOT NULL,
    pay_collaboration BOOLEAN DEFAULT FALSE,
    UNIQUE (pay_pays)
  );

  CREATE TABLE IF NOT EXISTS Expedition (
    exp_ID SERIAL PRIMARY KEY,
    exp_nom CHARACTER VARYING(255),
    exp_contact CHARACTER VARYING(255),
    pay_code_pays CHARACTER VARYING(3) NOT NULL references Pays(pay_code_pays),
    UNIQUE (exp_nom, pay_code_pays)
  );

  CREATE TABLE IF NOT EXISTS Type_taxonomie (
    typ_tax_ID SERIAL PRIMARY KEY,
    typ_tax_type CHARACTER VARYING(255) UNIQUE NOT NULL
  );

  CREATE TABLE IF NOT EXISTS Taxonomie (
    tax_ID SERIAL PRIMARY KEY,
    tax_phylum CHARACTER VARYING(255),
    tax_classe CHARACTER VARYING(255),
    tax_ordre CHARACTER VARYING(255),
    tax_famille CHARACTER VARYING(255),
    tax_genre CHARACTER VARYING(255) NOT NULL,
    tax_espece CHARACTER VARYING(255) NOT NULL,
    tax_sous_espece CHARACTER VARYING(255),
    tax_variete CHARACTER VARYING(255),
    tax_protocole CHARACTER VARYING(255),
    tax_sequencage CHARACTER VARYING(255),
    tax_seq_ref_book CHARACTER VARYING(255),
    typ_tax_ID INTEGER NOT NULL references Type_taxonomie(typ_tax_ID),
    UNIQUE (tax_phylum, tax_classe, tax_ordre, tax_famille, tax_genre, tax_espece)
  );

  CREATE TABLE IF NOT EXISTS Specimen (
    spe_code_specimen CHARACTER VARYING(255) PRIMARY KEY,
    spe_date_recolte DATE NOT NULL,
    spe_lieu_recolte CHARACTER VARYING(255) NOT NULL,
    spe_GPS_recolte CHARACTER VARYING(255),
    spe_observation CHARACTER VARYING(255),
    spe_collection CHARACTER VARYING(255),
    spe_contact CHARACTER VARYING(255),
    spe_collecteur CHARACTER VARYING(255),
    tax_ID INTEGER NOT NULL references Taxonomie(tax_ID),
    exp_ID INTEGER NOT NULL references Expedition(exp_ID)
  );

  CREATE TABLE IF NOT EXISTS Autorisation (
    aut_numero_autorisation CHARACTER VARYING(255) PRIMARY KEY,
    aut_type CHARACTER VARYING(255) NOT NULL
  );

  CREATE TABLE IF NOT EXISTS Autorisation_Specimen (
    aut_numero_autorisation CHARACTER VARYING(255) references Autorisation(aut_numero_autorisation) ON UPDATE CASCADE,
    spe_code_specimen CHARACTER VARYING(255) references Specimen(spe_code_specimen),
    PRIMARY KEY (aut_numero_autorisation, spe_code_specimen)
  );

  CREATE TABLE IF NOT EXISTS Fichier_taxonomie (
    fic_ID SERIAL PRIMARY KEY,
    fic_fichier TEXT NOT NULL,
    fic_type CHARACTER VARYING(255) NOT NULL,
    tax_ID INTEGER NOT NULL references Taxonomie(tax_ID)
  );

  CREATE TABLE IF NOT EXISTS Fichier_specimen (
    fic_ID SERIAL PRIMARY KEY,
    fic_fichier TEXT NOT NULL,
    fic_type CHARACTER VARYING(255) NOT NULL,
    spe_code_specimen CHARACTER VARYING(255) NOT NULL references Specimen(spe_code_specimen)
  );

  CREATE TABLE IF NOT EXISTS Partie_organisme (
    par_ID SERIAL PRIMARY KEY,
    par_origine CHARACTER VARYING(255),
    par_fr CHARACTER VARYING(255) NOT NULL,
    par_en CHARACTER VARYING(255),
    par_observation CHARACTER VARYING(255),
    UNIQUE (par_origine, par_fr, par_en)
  );

  CREATE TABLE IF NOT EXISTS Condition (
    con_ID SERIAL PRIMARY KEY,
    con_milieu CHARACTER VARYING(255),
    con_temperature DOUBLE PRECISION,
    con_type_culture CHARACTER VARYING(255),
    con_mode_operatoir CHARACTER VARYING(255),
    con_observation CHARACTER VARYING(255)
  );

  CREATE TABLE IF NOT EXISTS Fichier_conditions (
    fic_ID SERIAL PRIMARY KEY,
    fic_fichier TEXT NOT NULL,
    fic_type CHARACTER VARYING(255) NOT NULL,
    con_ID INTEGER NOT NULL references Condition(con_ID)
  );

  CREATE TABLE IF NOT EXISTS Echantillon (
    ech_code_Echantillon CHARACTER VARYING(255) PRIMARY KEY,
    ech_contact CHARACTER VARYING(255),
    ech_publication_DOI CHARACTER VARYING(255),
    ech_stock_disponibilite BOOLEAN DEFAULT FALSE,
    ech_stock_quantite CHARACTER VARYING(255) NOT NULL,
    ech_lieu_stockage CHARACTER VARYING(255) NOT NULL,
    par_ID INTEGER NOT NULL references Partie_organisme(par_ID),
    spe_code_specimen CHARACTER VARYING(255) NOT NULL references Specimen(spe_code_specimen),
    con_ID INTEGER references Condition(con_ID)
  );

  CREATE TABLE IF NOT EXISTS Extraits (
    ext_Code_Extraits CHARACTER VARYING(255) PRIMARY KEY,
    ext_solvant smallint NOT NULL references solvant(sol_id_solvant),
    ext_type_extraction CHARACTER VARYING(255),
    ext_etat CHARACTER VARYING(255),
    ext_disponibilite BOOLEAN DEFAULT FALSE,
    ext_protocole CHARACTER VARYING(255),
    ext_stockage CHARACTER VARYING(255),
    ext_observations CHARACTER VARYING(255),
    chi_id_chimiste INTEGER NOT NULL references Chimiste(chi_id_chimiste),
    ech_code_Echantillon CHARACTER VARYING(255) NOT NULL references Echantillon(ech_code_Echantillon),
    typ_id_type smallint DEFAULT 1 references type(typ_id_type)
  );

  CREATE TABLE IF NOT EXISTS Purification(
    pur_ID SERIAL PRIMARY KEY,
    pur_purification CHARACTER VARYING(255) NOT NULL,
    pur_ref_book CHARACTER VARYING(255),
    ext_Code_Extraits CHARACTER VARYING(255) NOT NULL references Extraits(ext_Code_Extraits)
  );

  CREATE TABLE IF NOT EXISTS Fichier_purification (
    fic_ID SERIAL PRIMARY KEY,
    fic_fichier TEXT NOT NULL,
    fic_type CHARACTER VARYING(255) NOT NULL,
    pur_ID INTEGER NOT NULL references Purification(pur_ID)
  );

  CREATE TABLE IF NOT EXISTS Produit_Extraits (
    pro_id_produit INTEGER NOT NULL references Produit(pro_id_produit),
    ext_Code_Extraits CHARACTER VARYING(255) NOT NULL references Extraits(ext_Code_Extraits),
    PRIMARY KEY (pro_id_produit, ext_Code_Extraits)
  );

  CREATE TABLE IF NOT EXISTS Plaque_Extraits (
    pla_id_plaque INTEGER NOT NULL references Plaque(pla_id_plaque),
    ext_Code_Extraits CHARACTER VARYING(255) NOT NULL references Extraits(ext_Code_Extraits),
    pos_coordonnees CHARACTER VARYING(255) NOT NULL,
    PRIMARY KEY (pla_id_plaque, ext_Code_Extraits)
  );

  ALTER TABLE Resultat ADD COLUMN ext_Code_Extraits CHARACTER VARYING(255) references Extraits(ext_Code_Extraits);

  UPDATE parametres set para_version = '1.6';

  COMMIT;
  ";

  $err = 0;

  $dbh->beginTransaction();
  $upd=$dbh->exec($update);

  if ($dbh->errorInfo()[0] == 42701){
    $update = str_replace("ALTER TABLE Resultat ADD COLUMN ext_Code_Extraits CHARACTER VARYING(255) references Extraits(ext_Code_Extraits);","",$update);

  $dbh->rollBack();
  $dbh->beginTransaction();
  $upd=$dbh->exec($update);
  }

  if ($dbh->errorInfo()[0] != 00000){
	$dbh->rollBack();
    echo "Une erreur est survenue !<br/>";
    print_r($dbh->errorInfo());
    $err = 1;
  }

  if($err == 0){
    $upd=$dbh->exec($update);
  	$dbh->commit();
    echo "<h2>Mises à jour effectué avec succès !</h2>";
  	echo "<h3>Vous pouvez maintenant supprimer le dossier 'upgrade'</h3>";
  	echo "<br/>";
  }
}
else
{
	session_destroy();
	unset($_SESSION);
	include_once 'index.php';
}
unset($dbh);
?>
