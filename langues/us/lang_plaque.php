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
define ('CREA','Created');
define ('GESTION','administration');
define ('VISU','View');
define('ACETATETYLE','ethyl acetate');
define('ACETONE','acetone');
define('ACETONITRILE','acetonitrile');
define('BENZENE','benzene');
define('CHOLOROFORME','chloroform');
define('DICHLO','dichloromethane');
define('DMF','DMF');
define('DMSO','DMSO');
define('EAU','eau');
define('ETHANOL','ethanol');
define('ETHERPET','petroleum ether');
define('ETHERETHYL','ethyl ether');
define('INCONNU','unknown');
define('INSOLUBLE','unsolvable');
define('METHANOL','methanol');
define('PYRIDINE','pyridine');
define('THF','THF');
define('TOLUENE','toluene');
define ('PLAQUERA','Plate \'daughter\' of the plate \'Mother\' N°:');
define ('NUMERO','Plate number for internal use:');
define ('NUMEROEVOTEC','Plate number for external use:');
define ('NUMEROLOT','New batch of belonging:');
define ('LOTANCIEN','Existing lot :');
define ('CONCENTRATION','Average concentration:');
define ('SOLVANT','Solvent used:');
define ('MOL','mol. L<sup>-1</sup>');
define ('DATE','Creation Date:');
define ('VOLUME','plate volume:');
define ('ML','mL');
define ('MIL','µL');
define ('OU','ou');
define ('CHAMPNUM','The field plate number is not informed');
define ('CHAMPCON','The field average concentration is not informed');
define ('CHAMPVOL','The field volume is not informed');
define ('SELECPLA','Select');
define ('SELECTEQUIPE','Select a team');
define ('EQUIPE','Teams:');
define ('CHIMISTE','Chemist:');
define ('SELECTCHIMISTE','Select a chemist');
define ('NUMEROPRO','Identification number');
define ('SELECTNUMERO','Select a number');
define ('SELECTBOITE','Select a box');
define ('BOITETO','plate placed in a  box full of the chemical library:');
define ('SAUVEGARDE','Backup');
define ('CHARGERCOO','Load a file in CSV format:');
define ('DEVELOPPE','Expand');
define ('SELECTCIBLE','Select a target');
define ('DESCRIPT','Description:');
define ('ANCIBLE','Targets listed :');
define ('NVCIBLE','New target:');
define ('CIBLE','Target name:');
define ('LABO','Laboratory:');
define ('CONCEN','Concentration:');
define ('PROTOCOL','Test protocol:');
define ('TEST','Bioassay :');
define ('DATEENV','Date of dispatch:');
define ('RESULTAT','Biological Results:');
define ('TESTCOUR','Bioassay in progress');
define ('TESTRESU','bioassay test performed, results received');
define ('SELECTPLAQUE','Select a plate');
define ('PLAQUEMERE','List of mothers plates:');
define ('AIDECSV','You can use a csv file to insert data.<br> The file must contain the number of wells in the plate and the product number (unique number to 8 digit or pill number).<br><br>example : <br>\'ICOA-FST-L-01A02\';\'A02\'<br>\'ICOA-SBR-L-02D05\';\'A03\'<br>\'ICOA-PBU-L-02D06\';\'A04\'<br>...<br>\'ICOA-SBR-L-06H03\';\'H09\'<br>\'ICOA-SRR-L-03D03\';\'H10\'<br>\'ICOA-PBU-L-04C11\';\'H11\'<br>');
define ('AIDECSV1','You can use a csv file to insert data.<br> The file must contain the number of wells in the plate, the product number (unique number to 8 digit or pill number) and the exact mass of product per wells.<br><br>example : <br>\'ICOA-FST-L-01A02\';\'A02\';\'1,2\'<br>\'ICOA-SBR-L-02D05\';\'A03\';\'0,9\'<br>\'ICOA-PBU-L-02D06\';\'A04\';\'1,0\'<br>...<br>\'ICOA-SBR-L-06H03\';\'H09\';\'1,0\'<br>\'ICOA-SRR-L-03D03\';\'H10\';\'1,1\'<br>\'ICOA-PBU-L-04C11\';\'H11\';\'1,2\'<br>');
define ('PUITVIDE','Click on the wells to fit a structure or charge CSV data file by the form');
define ('PUITVIDE1','Click on the wells to fit a structure');
define ('MASSE','Mass of product per well:');
define ('SELECTMASSE','Select');
define ('MASSEMOY','average mass');
define ('MASSEEXA','exact mass');
define ('MG','mg');
define ('MASSEPROD','Mass of this product in the well:');
define ('CHAMPMASSPROD','field \''.MASSEPROD.'\' is not informed');
define ('DEFALK','Subtract the stock with the mass for each molecule of the plate');
define ('CHAMPMASS','Field Average weight of product is not informed');
define ('VOLUMEPRE','Volume collected in plate ');
define ('DPOINT',' :');
define ('REDUIRE','Reduce');
define('RETOUR','Back');
define ('LISTE','Export to CSV');
define ('ATTDEFALK','If the stock drops to a substance at 0mg and setting the numbering is set to automatic, then the number will be changed');
define ('MOD','Change');
define ('SUP','Remove');
define ('SUPCONFIRM','Do you want to remove the plate: ');
define ('ATTENTION','All changes wells on this page will not make changes in the mass storage of bulk product. It is up to you to modify the corresponding product sheet.');

?>