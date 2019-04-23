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
  ALTER TABLE produit ADD pro_controle_purete integer;
  ALTER TABLE produit ADD pro_date_controle_purete date;
  ALTER TABLE produit ADD pro_controle_structure integer;

  ALTER TABLE produit ALTER COLUMN pro_id_type DROP NOT NULL;
  ALTER TABLE produit ALTER COLUMN pro_id_equipe DROP NOT NULL;
  ALTER TABLE produit ALTER COLUMN pro_id_responsable DROP NOT NULL;
  ALTER TABLE produit ALTER COLUMN pro_id_chimiste DROP NOT NULL;
  ALTER TABLE produit ALTER COLUMN pro_id_couleur DROP NOT NULL;
  ALTER TABLE produit ALTER COLUMN pro_purete DROP NOT NULL;
  ALTER TABLE produit ALTER COLUMN pro_purification DROP NOT NULL;
  ALTER TABLE produit ALTER COLUMN pro_unite_masse DROP NOT NULL;
  ALTER TABLE produit ALTER COLUMN pro_aspect DROP NOT NULL;
  ALTER TABLE produit ALTER COLUMN pro_date_entree DROP NOT NULL;
  ALTER TABLE produit ALTER COLUMN pro_ref_cahier_labo DROP NOT NULL;
  ALTER TABLE produit ALTER COLUMN pro_etape_mol DROP NOT NULL;
  ALTER TABLE produit ALTER COLUMN pro_numero DROP NOT NULL;

  ALTER TABLE produit ALTER COLUMN pro_purete TYPE character varying;
  ALTER TABLE produit ALTER COLUMN pro_point_fusion TYPE character varying;
  ALTER TABLE produit ALTER COLUMN pro_point_ebullition TYPE character varying;
  ALTER TABLE produit ALTER COLUMN pro_pression_pb TYPE character varying;
  ALTER TABLE produit ALTER COLUMN pro_sel TYPE character varying(15);

  ALTER TABLE produit DROP CONSTRAINT IF EXISTS contrainte_aspect;
  ALTER TABLE produit ADD CONSTRAINT contrainte_aspect CHECK ((pro_aspect <@ ARRAY['GOMME'::character varying, 'HUILE'::character varying, 'LIQUIDE'::character varying, 'MOUSSE'::character varying, 'SOLIDE'::character varying, 'INCONNU'::character varying]));

  ALTER TABLE produit DROP CONSTRAINT IF EXISTS contrainte_purification;
  ALTER TABLE produit ADD CONSTRAINT contrainte_purification CHECK ((pro_purification <@ ARRAY['AUCUNE'::character varying, 'COLONNE'::character varying, 'DISTILLATION'::character varying, 'EXTRACTION'::character varying, 'FILTRATION'::character varying, 'FILTRATIONCEL'::character varying, 'HPLC'::character varying, 'PRECIPITATION'::character varying, 'RECRISTALLISATION'::character varying, 'RESINE'::character varying, 'INCONNUE'::character varying]));

  ALTER TABLE evotec ADD evo_date_envoie date DEFAULT now();
  ALTER TABLE evotec ADD evo_insoluble boolean DEFAULT FALSE;

  ALTER TABLE chimiste ADD chi_date_expiration date DEFAULT (now() + '1 year'::interval);

  ALTER TABLE chimiste ALTER COLUMN chi_password TYPE character varying(60);

  CREATE OR REPLACE FUNCTION pro_chi_deactive() RETURNS void AS $$
  DECLARE
  cur_chi_deactive CURSOR IS
  SELECT chi_id_chimiste, chi_date_expiration FROM chimiste
  WHERE chi_date_expiration < now()
  AND chi_statut = '{CHIMISTE}'
  AND chi_passif = FALSE
  FOR UPDATE;
  BEGIN
  FOR leChimiste IN cur_chi_deactive LOOP
  UPDATE chimiste
  SET chi_passif = TRUE
  WHERE CURRENT OF cur_chi_deactive;
  END LOOP;
  END;
  $$ LANGUAGE plpgsql;

  INSERT INTO couleur (cou_id_couleur, cou_couleur) VALUES (218, 'INCON');

  ALTER TABLE hrms ADD hrms_date date DEFAULT now();
  ALTER TABLE ir ADD ir_date date DEFAULT now();
  ALTER TABLE rmnc ADD rmnc_date date DEFAULT now();
  ALTER TABLE rmnh ADD rmnh_date date DEFAULT now();
  ALTER TABLE sm ADD sm_date date DEFAULT now();
  ALTER TABLE uv ADD uv_date date DEFAULT now();

  ALTER TABLE hrms ALTER COLUMN hrms_fichier type text;
  ALTER TABLE ir ALTER COLUMN ir_fichier type text;
  ALTER TABLE rmnc ALTER COLUMN rmnc_fichier type text;
  ALTER TABLE rmnh ALTER COLUMN rmnh_fichier type text;
  ALTER TABLE sm ALTER COLUMN sm_fichier type text;
  ALTER TABLE uv ALTER COLUMN uv_fichier type text;

  CREATE OR REPLACE FUNCTION ajoute_pro_date_ctrl_purete() RETURNS trigger AS $$
      BEGIN
  		IF NEW.pro_controle_purete <> 0 THEN
  				UPDATE produit SET pro_date_controle_purete = now() where pro_id_produit = OLD.pro_id_produit;
  		END IF;
  		IF NEW.pro_controle_purete = 0 THEN
  				UPDATE produit SET pro_date_controle_purete = NULL where pro_id_produit = OLD.pro_id_produit;
  		END IF;
          RETURN NEW;
      END;
  $$ LANGUAGE plpgsql;

  CREATE TRIGGER ajoute_pro_date_ctrl_purete
  AFTER UPDATE OF pro_controle_purete ON produit
  FOR EACH ROW
  EXECUTE PROCEDURE ajoute_pro_date_ctrl_purete();

  ALTER TABLE parametres ALTER COLUMN para_version type character varying(12);
  UPDATE parametres set para_version = '1.5';
  COMMIT;
  ";

  $err = 0;

  $upd=$dbh->exec($update);
  if ($dbh->errorInfo()[0] == 42701){
    $update = str_replace("ALTER TABLE produit ADD pro_controle_purete boolean;","",$update);
    $upd=$dbh->exec($update);
  }

  if ($dbh->errorInfo()[0] == 42701){
    $update = str_replace("ALTER TABLE produit ADD pro_date_controle_purete date;","",$update);
    $upd=$dbh->exec($update);
  }

  if ($dbh->errorInfo()[0] == 42701){
    $update = str_replace("ALTER TABLE produit ADD pro_controle_structure boolean;","",$update);
    $upd=$dbh->exec($update);
  }

  if ($dbh->errorInfo()[2] != 00000){
    echo "erreur lors de la modification de la table « produit »";
    print_r($dbh->errorInfo());
    $err = 1;
  }

  $upd=$dbh->exec($update);

  if ($dbh->errorInfo()[0] == 42701){
    $update = str_replace("ALTER TABLE evotec ADD evo_date_envoie date DEFAULT now();","",$update);
    $upd=$dbh->exec($update);
  }

  if ($dbh->errorInfo()[0] == 42701){
    $update = str_replace("ALTER TABLE evotec ADD evo_insoluble boolean DEFAULT FALSE;","",$update);
    $upd=$dbh->exec($update);
  }

  if ($dbh->errorInfo()[2] != 00000){
    echo "erreur lors de la modification de la table « evotec »";
    print_r($dbh->errorInfo());
    $err = 1;
  }

  $upd=$dbh->exec($update);

  if ($dbh->errorInfo()[0] == 42701){
    $update = str_replace("ALTER TABLE chimiste ADD chi_date_expiration date DEFAULT (now() + '1 year'::interval);","",$update);
    $upd=$dbh->exec($update);
  }

  if ($dbh->errorInfo()[2] != 00000){
    echo "erreur lors de la modification de la table « chimiste »";
    print_r($dbh->errorInfo());
    $err = 1;
  }

  $upd=$dbh->exec($update);
  if ($dbh->errorInfo()[0] == 23505){
    $update = str_replace("INSERT INTO couleur (cou_id_couleur, cou_couleur) VALUES (218, 'INCON');","",$update);
    $upd=$dbh->exec($update);
  }

  if ($dbh->errorInfo()[2] != 00000){
    echo "erreur lors de la modification de la table « couleur »";
    print_r($dbh->errorInfo());
    $err = 1;
  }

  $upd=$dbh->exec($update);
  if ($dbh->errorInfo()[0] == 42701){
    $update = str_replace("ALTER TABLE hrms ADD hrms_date date DEFAULT now();","",$update);
    $upd=$dbh->exec($update);
  }

  if ($dbh->errorInfo()[0] == 42701){
    $update = str_replace("ALTER TABLE ir ADD ir_date date DEFAULT now();","",$update);
    $upd=$dbh->exec($update);
  }

  if ($dbh->errorInfo()[0] == 42701){
    $update = str_replace("ALTER TABLE rmnc ADD rmnc_date date DEFAULT now();","",$update);
    $upd=$dbh->exec($update);
  }

  if ($dbh->errorInfo()[0] == 42701){
    $update = str_replace("ALTER TABLE rmnh ADD rmnh_date date DEFAULT now();","",$update);
    $upd=$dbh->exec($update);
  }

  if ($dbh->errorInfo()[0] == 42701){
    $update = str_replace("ALTER TABLE sm ADD sm_date date DEFAULT now();","",$update);
    $upd=$dbh->exec($update);
  }

  if ($dbh->errorInfo()[0] == 42701){
    $update = str_replace("ALTER TABLE uv ADD uv_date date DEFAULT now();","",$update);
    $upd=$dbh->exec($update);
  }

  if ($dbh->errorInfo()[2] != 00000){
    echo "erreur lors de la modification des table de fichiers";
    print_r($dbh->errorInfo());
    $err = 1;
  }

  $upd=$dbh->exec($update);
  if ($dbh->errorInfo()[0] == 42701){
    $update = str_replace("CREATE TRIGGER ajoute_pro_date_ctrl_purete
  AFTER UPDATE OF pro_controle_purete ON produit
  FOR EACH ROW
  EXECUTE PROCEDURE ajoute_pro_date_ctrl_purete();","",$update);
    $upd=$dbh->exec($update);
  }

  if ($dbh->errorInfo()[2] != 00000){
    echo "erreur lors de la modification des fonctions/trigger de contrôle";
    print_r($dbh->errorInfo());
    $err = 1;
  }

  if($err == 0){
    $upd=$dbh->exec($update);
    echo "<h2>Mises à jour effectué avec succès !</h2>";
  	echo "<h3>Vous pouvez maintenant supprimer le dossier 'upgrade'</h3>";
  	echo "<br/>";
  	echo "<h3><a href='../oublie.php'>Veuillez demander un nouveau mot de passe en cliquant ici</a></h3>";
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
