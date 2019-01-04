<?php
//chi_date_expiration date DEFAULT NOW(),
$sql="
DROP TABLE IF EXISTS chimiste CASCADE;
CREATE TABLE chimiste (
  chi_id_chimiste integer NOT NULL,
  chi_nom character varying(40),
  chi_prenom character varying(40),
  chi_password character varying(60),
  chi_email character varying(60),
  chi_recevoir boolean DEFAULT true,
  chi_langue character varying(2),
  chi_statut character varying(14)[],
  chi_id_responsable integer,
  chi_id_equipe smallint,
  chi_passif boolean DEFAULT false,
  chi_date_expiration date DEFAULT (now() + '1 year'::interval),
  CONSTRAINT contrainte_statut CHECK ((chi_statut <@ ARRAY['ADMINISTRATEUR'::character varying, 'CHEF'::character varying, 'RESPONSABLE'::character varying, 'CHIMISTE'::character varying]))
);

CREATE SEQUENCE chimiste_chi_id_chimiste_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

ALTER SEQUENCE chimiste_chi_id_chimiste_seq OWNED BY chimiste.chi_id_chimiste;

DROP TABLE IF EXISTS cible CASCADE;
CREATE TABLE cible (
  cib_id_cible smallint NOT NULL,
  cib_nom character varying(100),
  cib_uniprot character varying(6)
);

CREATE SEQUENCE cible_cib_id_cible_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

ALTER SEQUENCE cible_cib_id_cible_seq OWNED BY cible.cib_id_cible;

DROP TABLE IF EXISTS couleur CASCADE;
CREATE TABLE couleur (
  cou_id_couleur smallint NOT NULL,
  cou_couleur character varying(7) NOT NULL
);

CREATE SEQUENCE couleur_id_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

ALTER SEQUENCE couleur_id_seq OWNED BY couleur.cou_id_couleur;

DROP TABLE IF EXISTS equipe CASCADE;
CREATE TABLE equipe (
  equi_id_equipe smallint NOT NULL,
  equi_nom_equipe character varying(30),
  equi_initiale_numero character varying(6)
);

CREATE SEQUENCE equipe_equi_id_equipe_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;


ALTER SEQUENCE equipe_equi_id_equipe_seq OWNED BY equipe.equi_id_equipe;

DROP TABLE IF EXISTS hrms CASCADE;
CREATE TABLE hrms (
  hrms_id_hrms integer NOT NULL,
  hrms_type character varying(7)[],
  hrms_text text,
  hrms_fichier bytea,
  hrms_nom_fichier character varying(6),
  hrms_date date DEFAULT now(),
  CONSTRAINT contrainte_hrmstype CHECK ((hrms_type <@ ARRAY['ACPI'::character varying, 'APPI'::character varying, 'CI'::character varying, 'DCI'::character varying, 'EI'::character varying, 'ESI'::character varying, 'MAL'::character varying, 'INCONNU'::character varying]))
);

CREATE SEQUENCE hrms_hrms_id_hrms_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

ALTER SEQUENCE hrms_hrms_id_hrms_seq OWNED BY hrms.hrms_id_hrms;

DROP TABLE IF EXISTS ir CASCADE;
CREATE TABLE ir (
  ir_id_ir integer NOT NULL,
  ir_text text,
  ir_fichier bytea,
  ir_nom_fichier character varying(6),
  ir_date date DEFAULT now()
);

CREATE SEQUENCE ir_ir_id_ir_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

ALTER SEQUENCE ir_ir_id_ir_seq OWNED BY ir.ir_id_ir;

DROP TABLE IF EXISTS labocible CASCADE;
CREATE TABLE labocible (
  lab_id_labocible smallint NOT NULL,
  lab_concentration double precision,
  lab_protocol text,
  lab_laboratoire text,
  lab_id_cible smallint
);

CREATE SEQUENCE labocible_lab_id_labocible_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

ALTER SEQUENCE labocible_lab_id_labocible_seq OWNED BY labocible.lab_id_labocible;

DROP TABLE IF EXISTS liste_precaution CASCADE;
CREATE TABLE liste_precaution (
  lis_id_precaution smallint NOT NULL,
  lis_id_structure integer NOT NULL
);

DROP TABLE IF EXISTS lot CASCADE;
CREATE TABLE lot (
  lot_id_lot smallint NOT NULL,
  lot_num_lot character varying(35)
);

CREATE SEQUENCE lot_lot_id_lot_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

ALTER SEQUENCE lot_lot_id_lot_seq OWNED BY lot.lot_id_lot;

DROP TABLE IF EXISTS lotplaque CASCADE;
CREATE TABLE lotplaque (
  lopla_id_lot smallint NOT NULL,
  lopla_id_plaque integer NOT NULL
);

DROP TABLE IF EXISTS numerotation CASCADE;
CREATE TABLE numerotation (
  num_id_numero smallint NOT NULL,
  num_parametre smallint NOT NULL,
  num_type character varying(9)[] NOT NULL,
  num_valeur character varying(5),
  CONSTRAINT contrainte_numtype CHECK ((num_type <@ ARRAY['FIXE'::character varying, 'COORDONEE'::character varying, 'EQUIPE'::character varying, 'TYPE'::character varying, 'BOITE'::character varying, 'NUMERIC'::character varying]))
);

CREATE SEQUENCE numerotation_num_id_numero_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

ALTER SEQUENCE numerotation_num_id_numero_seq OWNED BY numerotation.num_id_numero;

DROP TABLE IF EXISTS numerotation_temporaire CASCADE;
CREATE TABLE numerotation_temporaire (
  nume_tempo character varying(6) NOT NULL,
  nume_type smallint NOT NULL,
  nume_equipe smallint NOT NULL,
  nume_date date
);

DROP TABLE IF EXISTS parametres CASCADE;
CREATE TABLE parametres (
  para_id_parametre smallint NOT NULL,
  para_nom_labo character varying(255),
  para_numerotation character varying(4),
  para_logo character varying(255),
  para_acronyme character varying(7),
  para_stock smallint,
  para_email_national character varying(40),
  para_num_exportation boolean,
  para_email_envoie character varying(50),
  para_version character varying(7),
  para_origin_defaut character varying(12)
);

CREATE SEQUENCE parametres_para_id_parametre_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

ALTER SEQUENCE parametres_para_id_parametre_seq OWNED BY parametres.para_id_parametre;

DROP TABLE IF EXISTS plaque CASCADE;
CREATE TABLE plaque (
  pla_id_plaque integer NOT NULL,
  pla_concentration double precision,
  pla_nb_decongelation smallint,
  pla_date date,
  pla_volume double precision,
  pla_unite_volume character varying(3)[],
  pla_masse double precision,
  pla_identifiant_local character varying(35),
  pla_id_solvant smallint,
  pla_id_plaque_mere integer,
  pla_volume_preleve double precision,
  pla_unite_vol_preleve character varying[],
  pla_identifiant_externe character varying(35),
  CONSTRAINT contrainte_unite_volume CHECK ((pla_unite_volume <@ ARRAY['ML'::character varying, 'MIL'::character varying])),
  CONSTRAINT plaque_pla_unite_vol_preleve_check CHECK ((pla_unite_vol_preleve <@ ARRAY['ML'::character varying, 'MIL'::character varying, ''::character varying]))
);

CREATE SEQUENCE plaque_pla_id_plaque_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

ALTER SEQUENCE plaque_pla_id_plaque_seq OWNED BY plaque.pla_id_plaque;

DROP TABLE IF EXISTS plaquecible CASCADE;
CREATE TABLE plaquecible (
  plac_id smallint NOT NULL,
  plac_id_cible smallint,
  plac_id_plaque smallint,
  plac_date date
);

CREATE SEQUENCE plaquecible_plac_id_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

ALTER SEQUENCE plaquecible_plac_id_seq OWNED BY plaquecible.plac_id;

DROP TABLE IF EXISTS \"position\" CASCADE;
CREATE TABLE \"position\" (
  pos_id_plaque integer NOT NULL,
  pos_id_produit integer NOT NULL,
  pos_coordonnees character varying(3) NOT NULL,
  pos_mass_prod double precision
);

DROP TABLE IF EXISTS precaution CASCADE;
CREATE TABLE precaution (
  pre_id_precaution smallint NOT NULL,
  pre_precaution character varying(20) NOT NULL
);

CREATE SEQUENCE precaution_pre_id_precaution_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

ALTER SEQUENCE precaution_pre_id_precaution_seq OWNED BY precaution.pre_id_precaution;

DROP TABLE IF EXISTS produit CASCADE;
CREATE TABLE produit (
  pro_id_produit integer NOT NULL,
  pro_id_type smallint,
  pro_id_equipe smallint,
  pro_id_responsable smallint,
  pro_id_chimiste smallint,
  pro_id_couleur smallint,
  pro_id_structure integer NOT NULL,
  pro_purete integer,
  pro_purification character varying(17)[],
  pro_pourcentage_actif character varying(5),
  pro_sel character varying(5),
  pro_masse double precision,
  pro_unite_masse character varying(4)[],
  pro_aspect character varying(7)[],
  pro_date_entree timestamp without time zone,
  pro_ref_cahier_labo character varying(50),
  pro_modop text,
  pro_statut character varying(5),
  pro_num_brevet character varying(25),
  pro_ref_contrat text,
  pro_date_contrat smallint,
  pro_observation text,
  pro_etape_mol character varying(13)[],
  pro_configuration character varying(255),
  pro_numero character varying(35),
  pro_num_boite smallint,
  pro_num_position character varying(3),
  pro_num_incremental smallint,
  pro_num_sansmasse smallint,
  pro_num_constant integer,
  pro_analyse_elem_trouve character varying(200),
  pro_point_fusion character varying(7),
  pro_point_ebullition character varying(7),
  pro_pression_pb character varying(10),
  pro_alpha numeric(4,1),
  pro_alpha_temperature numeric(3,1),
  pro_alpha_concentration numeric(5,1),
  pro_alpha_solvant smallint,
  pro_rf numeric(5,2),
  pro_rf_solvant character varying(255),
  pro_doi character varying(70),
  pro_hal character varying(12),
  pro_cas character varying(12),
  pro_suivi_modification text,
  pro_methode_purete character varying(20),
  pro_num_cn character varying(9),
  pro_tare_pilulier double precision,
  pro_origine_substance character varying(12)[],
  pro_qrcode character varying(256),
  pro_id_rmnh integer,
  pro_id_rmnc integer,
  pro_id_ir integer,
  pro_id_uv integer,
  pro_id_sm integer,
  pro_id_hrms integer,
  pro_controle_purete boolean,
  pro_date_controle_purete date,
  pro_controle_structure boolean,
  CONSTRAINT contrainte_aspect CHECK ((pro_aspect <@ ARRAY['GOMME'::character varying, 'HUILE'::character varying, 'LIQUIDE'::character varying, 'MOUSSE'::character varying, 'SOLIDE'::character varying, 'INCONNU'::character varying])),
  CONSTRAINT contrainte_originesubstance CHECK ((pro_origine_substance <@ ARRAY['SYNTHESE'::character varying, 'HEMISYNTHESE'::character varying, 'NATURELLE'::character varying, 'INCONNU'::character varying])),
  CONSTRAINT contrainte_purification CHECK ((pro_purification <@ ARRAY['AUCUNE'::character varying, 'COLONNE'::character varying, 'DISTILLATION'::character varying, 'EXTRACTION'::character varying, 'FILTRATION'::character varying, 'FILTRATIONCEL'::character varying, 'HPLC'::character varying, 'PRECIPITATION'::character varying, 'RECRISTALLISATION'::character varying, 'RESINE'::character varying, 'INCONNUE'::character varying])),
  CONSTRAINT contrainte_unitemasse CHECK ((pro_unite_masse <@ ARRAY['MG'::character varying, 'NMOL'::character varying])),
  CONSTRAINT contrainte_etapemol CHECK ((pro_etape_mol <@ ARRAY['INTERMEDIAIRE'::character varying, 'FINALE'::character varying, 'AUCUNE'::character varying, 'INCONNUE'::character varying]))
);

CREATE SEQUENCE produit_pro_id_produit_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

ALTER SEQUENCE produit_pro_id_produit_seq OWNED BY produit.pro_id_produit;

DROP TABLE IF EXISTS reference_resultat CASCADE;
CREATE TABLE reference_resultat (
  ref_id_ref smallint NOT NULL,
  ref_unite_resultat character varying(30),
  ref_date date,
  ref_molecule_reference text,
  ref_resultat_reference text
);

CREATE SEQUENCE reference_resultat_ref_id_ref_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

ALTER SEQUENCE reference_resultat_ref_id_ref_seq OWNED BY reference_resultat.ref_id_ref;

DROP TABLE IF EXISTS resultat CASCADE;
CREATE TABLE resultat (
  res_id_resultat integer NOT NULL,
  res_id_produit integer,
  res_id_labocible smallint,
  res_id_reference smallint,
  res_resultat_pourcentactivite double precision,
  res_resultat_ic50 character varying(12),
  res_resultat_ec50 character varying(12),
  res_resultat_autre character varying(50),
  res_commentaire text,
  res_actif smallint,
  res_resultat_pourcentageinhi double precision
);

CREATE SEQUENCE resultat_res_id_resultat_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

ALTER SEQUENCE resultat_res_id_resultat_seq OWNED BY resultat.res_id_resultat;

DROP TABLE IF EXISTS rmnc CASCADE;
CREATE TABLE rmnc (
  rmnc_id_rmnc integer NOT NULL,
  rmnc_text text,
  rmnc_fichier bytea,
  rmnc_nom_fichier character varying(6),
  rmnc_date date DEFAULT now()
);

CREATE SEQUENCE rmnc_rmnc_id_rmnc_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

ALTER SEQUENCE rmnc_rmnc_id_rmnc_seq OWNED BY rmnc.rmnc_id_rmnc;

DROP TABLE IF EXISTS rmnh CASCADE;
CREATE TABLE rmnh (
  rmnh_id_rmnh integer NOT NULL,
  rmnh_text text,
  rmnh_fichier bytea,
  rmnh_nom_fichier character varying(6),
  rmnh_date date DEFAULT now()
);

CREATE SEQUENCE rmnh_rmnh_id_rmnh_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

ALTER SEQUENCE rmnh_rmnh_id_rmnh_seq OWNED BY rmnh.rmnh_id_rmnh;

DROP TABLE IF EXISTS sm CASCADE;
CREATE TABLE sm (
  sm_id_sm integer NOT NULL,
  sm_type character varying(7)[],
  sm_text text,
  sm_fichier bytea,
  sm_nom_fichier character varying(6),
  sm_date date DEFAULT now(),
  CONSTRAINT contrainte_typesm CHECK ((sm_type <@ ARRAY['ACPI'::character varying, 'APPI'::character varying, 'CI'::character varying, 'DCI'::character varying, 'EI'::character varying, 'ESI'::character varying, 'MAL'::character varying, 'INCONNU'::character varying]))
);

CREATE SEQUENCE sm_sm_id_sm_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

ALTER SEQUENCE sm_sm_id_sm_seq OWNED BY sm.sm_id_sm;

DROP TABLE IF EXISTS solubilite CASCADE;
CREATE TABLE solubilite (
  sol_id_solvant smallint NOT NULL,
  sol_id_produit integer NOT NULL
);

CREATE SEQUENCE solubilite_sol_id_produit_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

ALTER SEQUENCE solubilite_sol_id_produit_seq OWNED BY solubilite.sol_id_produit;

CREATE SEQUENCE solubilite_sol_id_solvant_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

ALTER SEQUENCE solubilite_sol_id_solvant_seq OWNED BY solubilite.sol_id_solvant;

DROP TABLE IF EXISTS solvant CASCADE;
CREATE TABLE solvant (
  sol_id_solvant smallint NOT NULL,
  sol_solvant character varying(35)
);

CREATE SEQUENCE solvant_sol_id_solvant_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

ALTER SEQUENCE solvant_sol_id_solvant_seq OWNED BY solvant.sol_id_solvant;

DROP TABLE IF EXISTS evotec CASCADE;
CREATE TABLE evotec (
  evo_id_evotec smallint NOT NULL,
  evo_numero_permanent integer,
  evo_masse double precision,
  evo_date_envoie date
);

CREATE SEQUENCE evotec_evo_id_evotec_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

ALTER SEQUENCE evotec_evo_id_evotec_seq OWNED BY evotec.evo_id_evotec;

DROP TABLE IF EXISTS structure CASCADE;
CREATE TABLE structure (
  str_id_structure integer NOT NULL,
  str_nom text NOT NULL,
  str_mol text NOT NULL,
  str_formule_brute character varying(60) NOT NULL,
  str_masse_molaire double precision NOT NULL,
  str_analyse_elem character varying(200) NOT NULL,
  str_date timestamp without time zone,
  str_logp double precision,
  str_inchi text NOT NULL,
  str_inchi_md5 character varying(27) NOT NULL,
  str_acceptorcount smallint,
  str_rotatablebondcount smallint,
  str_aromaticatomcount smallint,
  str_donorcount smallint,
  str_asymmetricatomcount smallint,
  str_aromaticbondcount smallint
);

CREATE SEQUENCE structure_str_acceptorcount_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

ALTER SEQUENCE structure_str_acceptorcount_seq OWNED BY structure.str_acceptorcount;

CREATE SEQUENCE structure_str_analyse_elem_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

ALTER SEQUENCE structure_str_analyse_elem_seq OWNED BY structure.str_analyse_elem;

CREATE SEQUENCE structure_str_aromaticatomcount_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

ALTER SEQUENCE structure_str_aromaticatomcount_seq OWNED BY structure.str_aromaticatomcount;

CREATE SEQUENCE structure_str_aromaticbondcount_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

ALTER SEQUENCE structure_str_aromaticbondcount_seq OWNED BY structure.str_aromaticbondcount;

CREATE SEQUENCE structure_str_asymmetricatomcount_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

ALTER SEQUENCE structure_str_asymmetricatomcount_seq OWNED BY structure.str_asymmetricatomcount;

CREATE SEQUENCE structure_str_donorcount_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

ALTER SEQUENCE structure_str_donorcount_seq OWNED BY structure.str_donorcount;

CREATE SEQUENCE structure_str_formule_brute_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

ALTER SEQUENCE structure_str_formule_brute_seq OWNED BY structure.str_formule_brute;

CREATE SEQUENCE structure_str_id_structure_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

ALTER SEQUENCE structure_str_id_structure_seq OWNED BY structure.str_id_structure;

CREATE SEQUENCE structure_str_inchi_md5_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

ALTER SEQUENCE structure_str_inchi_md5_seq OWNED BY structure.str_inchi_md5;

CREATE SEQUENCE structure_str_inchi_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

ALTER SEQUENCE structure_str_inchi_seq OWNED BY structure.str_inchi;


CREATE SEQUENCE structure_str_logp_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

ALTER SEQUENCE structure_str_logp_seq OWNED BY structure.str_logp;

CREATE SEQUENCE structure_str_masse_molaire_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

ALTER SEQUENCE structure_str_masse_molaire_seq OWNED BY structure.str_masse_molaire;

CREATE SEQUENCE structure_str_mol_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

ALTER SEQUENCE structure_str_mol_seq OWNED BY structure.str_mol;

CREATE SEQUENCE structure_str_nom_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

ALTER SEQUENCE structure_str_nom_seq OWNED BY structure.str_nom;

CREATE SEQUENCE structure_str_rotatablebondcount_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

ALTER SEQUENCE structure_str_rotatablebondcount_seq OWNED BY structure.str_rotatablebondcount;

DROP TABLE IF EXISTS type CASCADE;
CREATE TABLE type (
  typ_id_type smallint NOT NULL,
  typ_type character varying(12),
  typ_initiale character varying(1)
);

CREATE SEQUENCE type_typ_id_type_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

ALTER SEQUENCE type_typ_id_type_seq OWNED BY type.typ_id_type;

DROP TABLE IF EXISTS uv CASCADE;
CREATE TABLE uv (
  uv_id_uv integer NOT NULL,
  uv_text text,
  uv_fichier bytea,
  uv_nom_fichier character varying(6),
  uv_date date DEFAULT now()
);

CREATE SEQUENCE uv_uv_id_uv_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;

ALTER SEQUENCE uv_uv_id_uv_seq OWNED BY uv.uv_id_uv;

ALTER TABLE ONLY chimiste ALTER COLUMN chi_id_chimiste SET DEFAULT nextval('chimiste_chi_id_chimiste_seq'::regclass);
ALTER TABLE ONLY parametres ALTER COLUMN para_id_parametre SET DEFAULT nextval('parametres_para_id_parametre_seq'::regclass);
ALTER TABLE ONLY cible ALTER COLUMN cib_id_cible SET DEFAULT nextval('cible_cib_id_cible_seq'::regclass);
ALTER TABLE ONLY couleur ALTER COLUMN cou_id_couleur SET DEFAULT nextval('couleur_id_seq'::regclass);
ALTER TABLE ONLY equipe ALTER COLUMN equi_id_equipe SET DEFAULT nextval('equipe_equi_id_equipe_seq'::regclass);
ALTER TABLE ONLY hrms ALTER COLUMN hrms_id_hrms SET DEFAULT nextval('hrms_hrms_id_hrms_seq'::regclass);
ALTER TABLE ONLY ir ALTER COLUMN ir_id_ir SET DEFAULT nextval('ir_ir_id_ir_seq'::regclass);
ALTER TABLE ONLY labocible ALTER COLUMN lab_id_labocible SET DEFAULT nextval('labocible_lab_id_labocible_seq'::regclass);
ALTER TABLE ONLY lot ALTER COLUMN lot_id_lot SET DEFAULT nextval('lot_lot_id_lot_seq'::regclass);
ALTER TABLE ONLY numerotation ALTER COLUMN num_id_numero SET DEFAULT nextval('numerotation_num_id_numero_seq'::regclass);
ALTER TABLE ONLY plaque ALTER COLUMN pla_id_plaque SET DEFAULT nextval('plaque_pla_id_plaque_seq'::regclass);
ALTER TABLE ONLY plaquecible ALTER COLUMN plac_id SET DEFAULT nextval('plaquecible_plac_id_seq'::regclass);
ALTER TABLE ONLY precaution ALTER COLUMN pre_id_precaution SET DEFAULT nextval('precaution_pre_id_precaution_seq'::regclass);
ALTER TABLE ONLY produit ALTER COLUMN pro_id_produit SET DEFAULT nextval('produit_pro_id_produit_seq'::regclass);
ALTER TABLE ONLY reference_resultat ALTER COLUMN ref_id_ref SET DEFAULT nextval('reference_resultat_ref_id_ref_seq'::regclass);
ALTER TABLE ONLY resultat ALTER COLUMN res_id_resultat SET DEFAULT nextval('resultat_res_id_resultat_seq'::regclass);
ALTER TABLE ONLY rmnc ALTER COLUMN rmnc_id_rmnc SET DEFAULT nextval('rmnc_rmnc_id_rmnc_seq'::regclass);
ALTER TABLE ONLY rmnh ALTER COLUMN rmnh_id_rmnh SET DEFAULT nextval('rmnh_rmnh_id_rmnh_seq'::regclass);
ALTER TABLE ONLY sm ALTER COLUMN sm_id_sm SET DEFAULT nextval('sm_sm_id_sm_seq'::regclass);
ALTER TABLE ONLY solubilite ALTER COLUMN sol_id_solvant SET DEFAULT nextval('solubilite_sol_id_solvant_seq'::regclass);
ALTER TABLE ONLY solubilite ALTER COLUMN sol_id_produit SET DEFAULT nextval('solubilite_sol_id_produit_seq'::regclass);
ALTER TABLE ONLY solvant ALTER COLUMN sol_id_solvant SET DEFAULT nextval('solvant_sol_id_solvant_seq'::regclass);
ALTER TABLE ONLY evotec ALTER COLUMN evo_id_evotec SET DEFAULT nextval('evotec_evo_id_evotec_seq'::regclass);
ALTER TABLE ONLY structure ALTER COLUMN str_id_structure SET DEFAULT nextval('structure_str_id_structure_seq'::regclass);
ALTER TABLE ONLY type ALTER COLUMN typ_id_type SET DEFAULT nextval('type_typ_id_type_seq'::regclass);
ALTER TABLE ONLY uv ALTER COLUMN uv_id_uv SET DEFAULT nextval('uv_uv_id_uv_seq'::regclass);
ALTER TABLE ONLY structure
ADD CONSTRAINT \"Primaire\" PRIMARY KEY (str_id_structure);
ALTER TABLE ONLY chimiste
ADD CONSTRAINT chimiste_pkey PRIMARY KEY (chi_id_chimiste);
ALTER TABLE ONLY cible
ADD CONSTRAINT cible_pkey PRIMARY KEY (cib_id_cible);
ALTER TABLE ONLY equipe
ADD CONSTRAINT equipe_pkey PRIMARY KEY (equi_id_equipe);
ALTER TABLE ONLY hrms
ADD CONSTRAINT hrms_pkey PRIMARY KEY (hrms_id_hrms);
ALTER TABLE ONLY ir
ADD CONSTRAINT ir_pkey PRIMARY KEY (ir_id_ir);
ALTER TABLE ONLY labocible
ADD CONSTRAINT labocible_pkey PRIMARY KEY (lab_id_labocible);
ALTER TABLE ONLY liste_precaution
ADD CONSTRAINT liste_precaution_pkey PRIMARY KEY (lis_id_precaution, lis_id_structure);
ALTER TABLE ONLY lot
ADD CONSTRAINT lot_pkey PRIMARY KEY (lot_id_lot);
ALTER TABLE ONLY lotplaque
ADD CONSTRAINT lotplaque_pkey PRIMARY KEY (lopla_id_lot, lopla_id_plaque);
ALTER TABLE ONLY numerotation
ADD CONSTRAINT numerotation_pkey PRIMARY KEY (num_id_numero, num_parametre, num_type);
ALTER TABLE ONLY numerotation_temporaire
ADD CONSTRAINT numerotation_temporaire_pkey PRIMARY KEY (nume_tempo, nume_type, nume_equipe);
ALTER TABLE ONLY plaque
ADD CONSTRAINT plaque_pkey PRIMARY KEY (pla_id_plaque);
ALTER TABLE ONLY plaquecible
ADD CONSTRAINT plaquecible_pkey PRIMARY KEY (plac_id);
ALTER TABLE ONLY \"position\"
ADD CONSTRAINT position_pkey PRIMARY KEY (pos_id_plaque, pos_id_produit, pos_coordonnees);
ALTER TABLE ONLY precaution
ADD CONSTRAINT precaution_pkey PRIMARY KEY (pre_id_precaution);
ALTER TABLE ONLY couleur
ADD CONSTRAINT primaire PRIMARY KEY (cou_id_couleur);
ALTER TABLE ONLY produit
ADD CONSTRAINT produit_pkey PRIMARY KEY (pro_id_produit);
ALTER TABLE ONLY reference_resultat
ADD CONSTRAINT reference_resultat_pkey PRIMARY KEY (ref_id_ref);
ALTER TABLE ONLY resultat
ADD CONSTRAINT resultat_pkey PRIMARY KEY (res_id_resultat);
ALTER TABLE ONLY rmnc
ADD CONSTRAINT rmnc_pkey PRIMARY KEY (rmnc_id_rmnc);
ALTER TABLE ONLY rmnh
ADD CONSTRAINT rmnh_pkey PRIMARY KEY (rmnh_id_rmnh);
ALTER TABLE ONLY sm
ADD CONSTRAINT sm_pkey PRIMARY KEY (sm_id_sm);
ALTER TABLE ONLY solubilite
ADD CONSTRAINT solubilite_pkey PRIMARY KEY (sol_id_solvant, sol_id_produit);
ALTER TABLE ONLY solvant
ADD CONSTRAINT solvant_pkey PRIMARY KEY (sol_id_solvant);
ALTER TABLE ONLY evotec
ADD CONSTRAINT evotec_pkey PRIMARY KEY (evo_id_evotec);
ALTER TABLE ONLY type
ADD CONSTRAINT type_pkey PRIMARY KEY (typ_id_type);
ALTER TABLE ONLY uv
ADD CONSTRAINT uv_pkey PRIMARY KEY (uv_id_uv);
ALTER TABLE ONLY parametres
ADD CONSTRAINT parametres_pkey PRIMARY KEY (para_id_parametre);
CREATE INDEX index_nom ON chimiste USING btree (chi_nom);
CREATE UNIQUE INDEX index_numero ON produit USING btree (pro_numero);
CREATE UNIQUE INDEX index_evotec ON evotec USING btree (evo_numero_permanent);
CREATE UNIQUE INDEX index_numero_constant ON produit USING btree (pro_num_constant);
CREATE INDEX index_ref_cahierlabo ON produit USING btree (pro_ref_cahier_labo);
ALTER TABLE ONLY produit
ADD CONSTRAINT cles_etrangere_chimiste FOREIGN KEY (pro_id_chimiste) REFERENCES chimiste(chi_id_chimiste) ON UPDATE CASCADE;
ALTER TABLE ONLY produit
ADD CONSTRAINT cles_etrangere_couleur FOREIGN KEY (pro_id_couleur) REFERENCES couleur(cou_id_couleur) ON UPDATE CASCADE;
ALTER TABLE ONLY produit
ADD CONSTRAINT cles_etrangere_equipe FOREIGN KEY (pro_id_equipe) REFERENCES equipe(equi_id_equipe) ON UPDATE CASCADE;
ALTER TABLE ONLY liste_precaution
ADD CONSTRAINT cles_etrangere_precaution FOREIGN KEY (lis_id_precaution) REFERENCES precaution(pre_id_precaution) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE ONLY solubilite
ADD CONSTRAINT cles_etrangere_produit FOREIGN KEY (sol_id_produit) REFERENCES produit(pro_id_produit) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE ONLY evotec
ADD CONSTRAINT evotec_evo_numero_permanent_fkey FOREIGN KEY (evo_numero_permanent) REFERENCES produit(pro_num_constant) ON UPDATE CASCADE;
ALTER TABLE ONLY solubilite
ADD CONSTRAINT cles_etrangere_solvant FOREIGN KEY (sol_id_solvant) REFERENCES solvant(sol_id_solvant) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE ONLY liste_precaution
ADD CONSTRAINT cles_etrangere_structure FOREIGN KEY (lis_id_structure) REFERENCES structure(str_id_structure) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE ONLY produit
ADD CONSTRAINT cles_etrangere_structure FOREIGN KEY (pro_id_structure) REFERENCES structure(str_id_structure) ON UPDATE CASCADE;
ALTER TABLE ONLY produit
ADD CONSTRAINT cles_etrangere_type FOREIGN KEY (pro_id_type) REFERENCES type(typ_id_type) ON UPDATE CASCADE;
ALTER TABLE ONLY labocible
ADD CONSTRAINT labocible_lab_id_cible_fkey FOREIGN KEY (lab_id_cible) REFERENCES cible(cib_id_cible) ON UPDATE CASCADE;
ALTER TABLE ONLY lotplaque
ADD CONSTRAINT lotplaque_lopla_id_lot_fkey FOREIGN KEY (lopla_id_lot) REFERENCES lot(lot_id_lot) ON UPDATE CASCADE;
ALTER TABLE ONLY lotplaque
ADD CONSTRAINT lotplaque_lopla_id_plaque_fkey FOREIGN KEY (lopla_id_plaque) REFERENCES plaque(pla_id_plaque) ON UPDATE CASCADE;
ALTER TABLE ONLY plaque
ADD CONSTRAINT plaque_pla_id_solvant_fkey FOREIGN KEY (pla_id_solvant) REFERENCES solvant(sol_id_solvant) ON UPDATE CASCADE;
ALTER TABLE ONLY plaquecible
ADD CONSTRAINT plaquecible_plac_id_cible_fkey FOREIGN KEY (plac_id_cible) REFERENCES cible(cib_id_cible) ON UPDATE CASCADE;
ALTER TABLE ONLY plaquecible
ADD CONSTRAINT plaquecible_plac_id_plaque_fkey FOREIGN KEY (plac_id_plaque) REFERENCES plaque(pla_id_plaque) ON UPDATE CASCADE;
ALTER TABLE ONLY \"position\"
ADD CONSTRAINT position_pos_id_plaque_fkey FOREIGN KEY (pos_id_plaque) REFERENCES plaque(pla_id_plaque) ON UPDATE CASCADE;
ALTER TABLE ONLY \"position\"
ADD CONSTRAINT position_pos_id_produit_fkey FOREIGN KEY (pos_id_produit) REFERENCES produit(pro_id_produit) ON UPDATE CASCADE;
ALTER TABLE ONLY produit
ADD CONSTRAINT produit_pro_alpha_solvant_fkey FOREIGN KEY (pro_alpha_solvant) REFERENCES solvant(sol_id_solvant);
ALTER TABLE ONLY produit
ADD CONSTRAINT produit_pro_id_hrms_fkey FOREIGN KEY (pro_id_hrms) REFERENCES hrms(hrms_id_hrms);
ALTER TABLE ONLY produit
ADD CONSTRAINT produit_pro_id_ir_fkey FOREIGN KEY (pro_id_ir) REFERENCES ir(ir_id_ir);
ALTER TABLE ONLY produit
ADD CONSTRAINT produit_pro_id_rmnc_fkey FOREIGN KEY (pro_id_rmnc) REFERENCES rmnc(rmnc_id_rmnc);
ALTER TABLE ONLY produit
ADD CONSTRAINT produit_pro_id_rmnh_fkey FOREIGN KEY (pro_id_rmnh) REFERENCES rmnh(rmnh_id_rmnh);
ALTER TABLE ONLY produit
ADD CONSTRAINT produit_pro_id_sm_fkey FOREIGN KEY (pro_id_sm) REFERENCES sm(sm_id_sm);
ALTER TABLE ONLY produit
ADD CONSTRAINT produit_pro_id_uv_fkey FOREIGN KEY (pro_id_uv) REFERENCES uv(uv_id_uv);
ALTER TABLE ONLY resultat
ADD CONSTRAINT resultat_res_id_labocible_fkey FOREIGN KEY (res_id_labocible) REFERENCES labocible(lab_id_labocible) ON UPDATE CASCADE;
ALTER TABLE ONLY produit
ADD CONSTRAINT produit_pro_id_responsable_fkey FOREIGN KEY (pro_id_responsable) REFERENCES chimiste(chi_id_chimiste) ON UPDATE CASCADE;
ALTER TABLE ONLY resultat
ADD CONSTRAINT resultat_res_id_produit_fkey FOREIGN KEY (res_id_produit) REFERENCES produit(pro_id_produit) ON UPDATE CASCADE;

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
$$ LANGUAGE plpgsql;";
