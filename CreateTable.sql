CREATE TABLE specialite (
    id_specialite CHAR(3) NOT NULL,
    nom_specialite VARCHAR(50),
    CONSTRAINT pk_specialite PRIMARY KEY(id_specialite)
);

CREATE TABLE atelier (
    id_atelier CHAR(3) NOT NULL,
    nom_atelier VARCHAR(50),
    type VARCHAR(50),
    CONSTRAINT pk_atelier PRIMARY KEY(id_atelier)
);

CREATE TABLE matiere_premiere (
    id_matiere CHAR(3) NOT NULL,
    nom_matiere VARCHAR(50),
    CONSTRAINT pk_matiere_premiere PRIMARY KEY(id_matiere)
);

CREATE TABLE jouet (
    id_jouet CHAR(3) NOT NULL,
    nom_jouet VARCHAR(50),
    type VARCHAR(50),
    statut VARCHAR(50),
    est_substitue_par CHAR(3) NULL,
    CONSTRAINT pk_jouet PRIMARY KEY(id_jouet),
    CONSTRAINT fk_jouet_substitue FOREIGN KEY (est_substitue_par) REFERENCES jouet(id_jouet) ON DELETE SET NULL
);

CREATE TABLE sous_traitant (
    id_sstraitant CHAR(4) NOT NULL,
    nom_sstraitant VARCHAR(50),
    CONSTRAINT pk_sous_traitant PRIMARY KEY(id_sstraitant)
);

CREATE TABLE tournee (
    id_tournee CHAR(3) NOT NULL,
    nom_tournee VARCHAR(50),
    CONSTRAINT pk_tournee PRIMARY KEY(id_tournee)
);

CREATE TABLE enfant (
    id_enfant CHAR(3) NOT NULL,
    nom_enfant VARCHAR(50),
    pren_enfant VARCHAR(50),
    adresse_enfant VARCHAR(50),
    id_tournee CHAR(3) NULL,
    CONSTRAINT pk_enfant PRIMARY KEY(id_enfant),
    CONSTRAINT fk_enfant_tournee FOREIGN KEY (id_tournee) REFERENCES tournee(id_tournee) ON DELETE SET NULL
);

CREATE TABLE entrepot (
    id_entrepot CHAR(4) NOT NULL,
    nom_entrepot VARCHAR(50),
    region VARCHAR(50),
    id_tournee CHAR(3) NULL,
    CONSTRAINT pk_entrepot PRIMARY KEY(id_entrepot),
    CONSTRAINT fk_entrepot_tournee FOREIGN KEY (id_tournee) REFERENCES tournee(id_tournee) ON DELETE SET NULL
);

CREATE TABLE intermittent (
    id_intermittent CHAR(3) NOT NULL,
    nom_intermittent VARCHAR(50),
    prenom_intermittent VARCHAR(50),
    id_entrepot CHAR(4) NULL,
    CONSTRAINT pk_intermittent PRIMARY KEY(id_intermittent),
    CONSTRAINT fk_intermittent_entrepot FOREIGN KEY (id_entrepot) REFERENCES entrepot(id_entrepot) ON DELETE SET NULL
);

CREATE TABLE cadeau (
    id_cadeau CHAR(3) NOT NULL,
    nom_cadeau VARCHAR(50),
    poid_cadeau INT,
    statut_cadeau VARCHAR(50),
    id_intermittent CHAR(3) NULL,
    CONSTRAINT pk_cadeau PRIMARY KEY(id_cadeau),
    CONSTRAINT fk_cadeau_intermittent FOREIGN KEY (id_intermittent) REFERENCES intermittent(id_intermittent) ON DELETE SET NULL
);

CREATE TABLE equipe (
    id_equipe CHAR(3) NOT NULL,
    nom_equipe VARCHAR(50),
    id_atelier CHAR(3) NULL,
    CONSTRAINT pk_equipe PRIMARY KEY(id_equipe),
    CONSTRAINT fk_equipe_atelier FOREIGN KEY (id_atelier) REFERENCES atelier(id_atelier) ON DELETE SET NULL
);

CREATE TABLE elfe (
    id_elfe CHAR(3) NOT NULL,
    nom_elfe VARCHAR(50),
    role VARCHAR(50),
    id_equipe_se_regroupe CHAR(3) NULL,
    id_equipe_dirige CHAR(3) NULL,
    id_elfe_remplace CHAR(3) NULL,
    CONSTRAINT pk_elfe PRIMARY KEY(id_elfe),
    CONSTRAINT fk_elfe_equipe_se_regroupe FOREIGN KEY (id_equipe_se_regroupe) REFERENCES equipe(id_equipe) ON DELETE SET NULL,
    CONSTRAINT fk_elfe_equipe_dirige FOREIGN KEY (id_equipe_dirige) REFERENCES equipe(id_equipe) ON DELETE SET NULL,
    CONSTRAINT fk_elfe_elfe_remplace FOREIGN KEY (id_elfe_remplace) REFERENCES elfe(id_elfe) ON DELETE CASCADE
);

CREATE TABLE traineau (
    id_traineau CHAR(3) NOT NULL,
    nom_traineau VARCHAR(50),
    capacite_traineau INT CHECK (capacite_traineau <= 8),
    poid INT,
    CONSTRAINT pk_traineau PRIMARY KEY(id_traineau)
);

CREATE TABLE renne (
    id_puce CHAR(3) NOT NULL,
    nom_renne VARCHAR(50),
    pos_renne VARCHAR(50),
    couleur_nez VARCHAR(50),
    poid_renne INT,
    id_traineau_gerer CHAR(3) NULL,
    id_traineau_tirer CHAR(3) NULL,
    CONSTRAINT pk_renne PRIMARY KEY(id_puce),
    CONSTRAINT fk_renne_traineau_gerer FOREIGN KEY (id_traineau_gerer) REFERENCES traineau(id_traineau) ON DELETE SET NULL,
    CONSTRAINT fk_renne_traineau_tirer FOREIGN KEY (id_traineau_tirer) REFERENCES traineau(id_traineau) ON DELETE SET NULL
);

CREATE TABLE a_comme_specialite_principale (
    id_elfe CHAR(3) NOT NULL,
    id_specialite CHAR(3) NOT NULL,
    CONSTRAINT pk_specialite_principale PRIMARY KEY(id_elfe, id_specialite),
    CONSTRAINT fk_a_comme_specialite_principale_elfe FOREIGN KEY (id_elfe) REFERENCES elfe(id_elfe) ON DELETE CASCADE,
    CONSTRAINT fk_a_comme_specialite_principale_specialite FOREIGN KEY (id_specialite) REFERENCES specialite(id_specialite) ON DELETE CASCADE
);

CREATE TABLE a_comme_specialite_secondaire (
    id_elfe CHAR(3) NOT NULL,
    id_specialite CHAR(3) NOT NULL,
    CONSTRAINT pk_specialite_secondaire PRIMARY KEY(id_elfe, id_specialite),
    CONSTRAINT fk_a_comme_specialite_secondaire_elfe FOREIGN KEY (id_elfe) REFERENCES elfe(id_elfe) ON DELETE CASCADE,
    CONSTRAINT fk_a_comme_specialite_secondaire_specialite FOREIGN KEY (id_specialite) REFERENCES specialite(id_specialite) ON DELETE CASCADE
);

CREATE TABLE passer (
    id_atelier CHAR(3) NOT NULL,
    id_cadeau CHAR(3) NOT NULL,
    CONSTRAINT pk_passer PRIMARY KEY(id_atelier, id_cadeau),
    CONSTRAINT fk_passer_atelier FOREIGN KEY (id_atelier) REFERENCES atelier(id_atelier) ON DELETE CASCADE,
    CONSTRAINT fk_passer_cadeau FOREIGN KEY (id_cadeau) REFERENCES cadeau(id_cadeau) ON DELETE CASCADE
);

CREATE TABLE fabriquer (
    id_cadeau CHAR(3) NOT NULL,
    id_matiere CHAR(3) NOT NULL,
    CONSTRAINT pk_fabriquer PRIMARY KEY(id_cadeau, id_matiere),
    CONSTRAINT fk_fabriquer_cadeau FOREIGN KEY (id_cadeau) REFERENCES cadeau(id_cadeau) ON DELETE CASCADE,
    CONSTRAINT fk_fabriquer_matiere FOREIGN KEY (id_matiere) REFERENCES matiere_premiere(id_matiere) ON DELETE CASCADE
);

CREATE TABLE lister (
    id_enfant CHAR(3) NOT NULL,
    id_jouet CHAR(3) NOT NULL,
    qte INT NOT NULL,
    CONSTRAINT pk_lister PRIMARY KEY(id_enfant, id_jouet),
    CONSTRAINT fk_lister_enfant FOREIGN KEY (id_enfant) REFERENCES enfant(id_enfant) ON DELETE CASCADE,
    CONSTRAINT fk_lister_jouet FOREIGN KEY (id_jouet) REFERENCES jouet(id_jouet) ON DELETE CASCADE
);

CREATE TABLE produireAtelier (
    id_atelier CHAR(3) NOT NULL,
    id_jouet CHAR(3) NOT NULL,
    CONSTRAINT pk_produireAtelier PRIMARY KEY(id_atelier, id_jouet),
    CONSTRAINT fk_produireAtelier_atelier FOREIGN KEY (id_atelier) REFERENCES atelier(id_atelier) ON DELETE CASCADE,
    CONSTRAINT fk_produireAtelier_jouet FOREIGN KEY (id_jouet) REFERENCES jouet(id_jouet) ON DELETE CASCADE
);

CREATE TABLE produireSousTraitant (
    id_jouet CHAR(3) NOT NULL,
    id_sstraitant CHAR(4) NOT NULL,
    CONSTRAINT pk_produireSousTraitant PRIMARY KEY(id_jouet, id_sstraitant),
    CONSTRAINT fk_produireSousTraitant_jouet FOREIGN KEY (id_jouet) REFERENCES jouet(id_jouet) ON DELETE CASCADE,
    CONSTRAINT fk_produireSousTraitant_sstraitant FOREIGN KEY (id_sstraitant) REFERENCES sous_traitant(id_sstraitant) ON DELETE CASCADE
);

CREATE TABLE gestion_ (
    id_equipe CHAR(3) NOT NULL,
    id_entrepot CHAR(4) NOT NULL,
    CONSTRAINT pk_gestion PRIMARY KEY(id_equipe, id_entrepot),
    CONSTRAINT fk_gestion_equipe FOREIGN KEY (id_equipe) REFERENCES equipe(id_equipe) ON DELETE CASCADE,
    CONSTRAINT fk_gestion_entrepot FOREIGN KEY (id_entrepot) REFERENCES entrepot(id_entrepot) ON DELETE CASCADE
);

CREATE TABLE mener (
    id_elfe CHAR(3) NOT NULL,
    id_traineau CHAR(3) NOT NULL,
    CONSTRAINT pk_mener PRIMARY KEY(id_elfe, id_traineau),
    CONSTRAINT fk_mener_elfe FOREIGN KEY (id_elfe) REFERENCES elfe(id_elfe) ON DELETE CASCADE,
    CONSTRAINT fk_mener_traineau FOREIGN KEY (id_traineau) REFERENCES traineau(id_traineau) ON DELETE CASCADE
);

CREATE TABLE entretenir (
    id_elfe CHAR(3) NOT NULL,
    id_puce CHAR(3) NOT NULL,
    id_traineau CHAR(3) NOT NULL,
    CONSTRAINT pk_entretenir PRIMARY KEY(id_elfe, id_puce, id_traineau),
    CONSTRAINT fk_entretenir_elfe FOREIGN KEY (id_elfe) REFERENCES elfe(id_elfe) ON DELETE CASCADE,
    CONSTRAINT fk_entretenir_renne FOREIGN KEY (id_puce) REFERENCES renne(id_puce) ON DELETE CASCADE,
    CONSTRAINT fk_entretenir_traineau FOREIGN KEY (id_traineau) REFERENCES traineau(id_traineau) ON DELETE CASCADE
);

CREATE TABLE paticiper (
    id_tournee CHAR(3) NOT NULL,
    id_intermittent CHAR(3) NOT NULL,
    CONSTRAINT pk_paticiper PRIMARY KEY(id_tournee, id_intermittent),
    CONSTRAINT fk_paticiper_tournee FOREIGN KEY (id_tournee) REFERENCES tournee(id_tournee) ON DELETE CASCADE,
    CONSTRAINT fk_paticiper_intermittent FOREIGN KEY (id_intermittent) REFERENCES intermittent(id_intermittent) ON DELETE CASCADE
);

CREATE TABLE send (
    id_traineau CHAR(3) NOT NULL,
    id_entrepot CHAR(4) NOT NULL,
    date_envoie DATE NOT NULL,
    CONSTRAINT pk_send PRIMARY KEY(id_traineau, id_entrepot, date_envoie),
    CONSTRAINT fk_send_traineau FOREIGN KEY (id_traineau) REFERENCES traineau(id_traineau) ON DELETE CASCADE,
    CONSTRAINT fk_send_entrepot FOREIGN KEY (id_entrepot) REFERENCES entrepot(id_entrepot) ON DELETE CASCADE
);
