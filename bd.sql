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


Create table lutin ( login varchar (16) PRIMARY KEY , password char (64)); 

/* partie insertion*/
--Insertion dans la table specialite
INSERT INTO specialite (id_specialite, nom_specialite) VALUES ('S01', 'Menuiserie');
INSERT INTO specialite (id_specialite, nom_specialite) VALUES ('S02', 'Bricolage');
INSERT INTO specialite (id_specialite, nom_specialite) VALUES ('S03', 'Emballage');
INSERT INTO specialite (id_specialite, nom_specialite) VALUES ('S04', 'Logistique');
INSERT INTO specialite (id_specialite, nom_specialite) VALUES ('S05', 'Entretien');

-- Insertion dans la table atelier
INSERT INTO atelier (id_atelier, nom_atelier, type) VALUES ('A01', 'Fabrication', 'Production');
INSERT INTO atelier (id_atelier, nom_atelier, type) VALUES ('A02', 'Peinture', 'Décoration');
INSERT INTO atelier (id_atelier, nom_atelier, type) VALUES ('A03', 'Emballage', 'Emballage');
INSERT INTO atelier (id_atelier, nom_atelier, type) VALUES ('A04', 'Assemblage', 'Assemblage');
INSERT INTO atelier (id_atelier, nom_atelier, type) VALUES ('A05', 'Contrôle qualité', 'Contrôle');

-- Insertion dans la table matiere_premiere
INSERT INTO matiere_premiere (id_matiere, nom_matiere) VALUES ('M01', 'Bois');
INSERT INTO matiere_premiere (id_matiere, nom_matiere) VALUES ('M02', 'Plastique');
INSERT INTO matiere_premiere (id_matiere, nom_matiere) VALUES ('M03', 'Papier');
INSERT INTO matiere_premiere (id_matiere, nom_matiere)VALUES ('M04', 'Métal');
INSERT INTO matiere_premiere (id_matiere, nom_matiere) VALUES ('M05', 'Tissu');

-- Insertion dans la table jouet
INSERT INTO jouet (id_jouet, nom_jouet, type, statut, est_substitue_par) VALUES ('J01', 'Poupée', 'Dolls', 'En fabrication', NULL);
INSERT INTO jouet (id_jouet, nom_jouet, type, statut, est_substitue_par) VALUES ('J02', 'Vélo', 'Transport', 'Disponible', NULL);
INSERT INTO jouet (id_jouet, nom_jouet, type, statut, est_substitue_par) VALUES ('J03', 'Train', 'Transport', 'Indisponible', 'J01');
INSERT INTO jouet (id_jouet, nom_jouet, type, statut, est_substitue_par) VALUES ('J04', 'Voiture', 'Transport', 'Disponible', NULL);
INSERT INTO jouet (id_jouet, nom_jouet, type, statut, est_substitue_par) VALUES ('J05', 'Robot', 'Technologie', 'En fabrication', NULL);

-- Insertion dans la table sous_traitant
INSERT INTO sous_traitant (id_sstraitant, nom_sstraitant) VALUES ('ST01', 'Sous-traitant A');
INSERT INTO sous_traitant (id_sstraitant, nom_sstraitant) VALUES ('ST02', 'Sous-traitant B');
INSERT INTO sous_traitant (id_sstraitant, nom_sstraitant) VALUES ('ST03', 'Sous-traitant C');

INSERT INTO sous_traitant (id_sstraitant, nom_sstraitant) VALUES ('ST04', 'Sous-traitant D');
INSERT INTO sous_traitant (id_sstraitant, nom_sstraitant) VALUES ('ST05', 'Sous-traitant E');

-- Insertion dans la table tournee
INSERT INTO tournee (id_tournee, nom_tournee) VALUES ('T01', 'Tournee Europe');
INSERT INTO tournee (id_tournee, nom_tournee) VALUES ('T02', 'Tournee Amérique');
INSERT INTO tournee (id_tournee, nom_tournee) VALUES ('T03', 'Tournee Asie');
INSERT INTO tournee (id_tournee, nom_tournee) VALUES ('T04', 'Tournee Océanie');
INSERT INTO tournee (id_tournee, nom_tournee)VALUES ('T05', 'Tournee Afrique');

-- Insertion dans la table enfant
INSERT INTO enfant (id_enfant, nom_enfant, pren_enfant, adresse_enfant, id_tournee) VALUES ('E01', 'Dupont', 'Paul', '123 Rue de Paris, Paris', 'T01');
INSERT INTO enfant (id_enfant, nom_enfant, pren_enfant, adresse_enfant, id_tournee) VALUES ('E02', 'Martin', 'Lucie', '456 Rue du Monde, Lyon', 'T02');
INSERT INTO enfant (id_enfant, nom_enfant, pren_enfant, adresse_enfant, id_tournee) VALUES ('E03', 'Lemoine', 'Alex', '789 Rue des Champs, Marseille', 'T03');
INSERT INTO enfant (id_enfant, nom_enfant, pren_enfant, adresse_enfant, id_tournee) VALUES ('E04', 'Petit', 'Claire', '321 Rue de Lyon, Paris', 'T04');
INSERT INTO enfant (id_enfant, nom_enfant, pren_enfant, adresse_enfant, id_tournee) VALUES ('E05', 'Durand', 'Louis', '654 Rue des Roses, Toulouse', 'T05');


-- Insertion dans la table entrepot
INSERT INTO entrepot (id_entrepot, nom_entrepot, region, id_tournee) VALUES ('ET01', 'Entrepot Paris', 'Europe', 'T01');
INSERT INTO entrepot (id_entrepot, nom_entrepot, region, id_tournee) VALUES ('ET02', 'Entrepot New York', 'Amérique du Nord', 'T02');
INSERT INTO entrepot (id_entrepot, nom_entrepot, region, id_tournee) VALUES ('ET03', 'Entrepot Tokyo', 'Asie - Est', 'T03');
INSERT INTO entrepot (id_entrepot, nom_entrepot, region, id_tournee) VALUES ('ET04', 'Entrepot Sydney', 'Océanie', 'T04');
INSERT INTO entrepot (id_entrepot, nom_entrepot, region, id_tournee) VALUES ('ET05', 'Entrepot Nairobi', 'Afrique de l’Est', 'T05');
INSERT INTO entrepot (id_entrepot, nom_entrepot, region, id_tournee) VALUES ('ET06', 'Entrepot Rio de Janeiro', 'Amérique du Sud', 'T05');
INSERT INTO entrepot (id_entrepot, nom_entrepot, region, id_tournee) VALUES ('ET07', 'Entrepot Moscou', 'Europe de l’Est', 'T05');
INSERT INTO entrepot (id_entrepot, nom_entrepot, region, id_tournee) VALUES ('ET08', 'Entrepot Delhi', 'Asie - Sud', 'T04');
INSERT INTO entrepot (id_entrepot, nom_entrepot, region, id_tournee) VALUES ('ET09', 'Entrepot Cape Town', 'Afrique du Sud', 'T01');
INSERT INTO entrepot (id_entrepot, nom_entrepot, region, id_tournee) VALUES ('ET10', 'Entrepot Los Angeles', 'Amérique du Nord - Ouest', 'T01');
INSERT INTO entrepot (id_entrepot, nom_entrepot, region, id_tournee) VALUES ('ET11', 'Entrepot Beijing', 'Asie - Nord', 'T01');
INSERT INTO entrepot (id_entrepot, nom_entrepot, region, id_tournee) VALUES ('ET12', 'Entrepot Anchorage', 'Alaska', 'T03');

-- Insertion dans la table intermittent
INSERT INTO intermittent (id_intermittent, nom_intermittent, prenom_intermittent, id_entrepot) VALUES ('I01', 'Smith', 'John', 'ET01');
INSERT INTO intermittent (id_intermittent, nom_intermittent, prenom_intermittent, id_entrepot) VALUES ('I02', 'Doe', 'Jane', 'ET02');
INSERT INTO intermittent (id_intermittent, nom_intermittent, prenom_intermittent, id_entrepot) VALUES ('I03', 'Williams', 'James', 'ET03');
INSERT INTO intermittent (id_intermittent, nom_intermittent, prenom_intermittent, id_entrepot) VALUES ('I04', 'Brown', 'Linda', 'ET04');
INSERT INTO intermittent (id_intermittent, nom_intermittent, prenom_intermittent, id_entrepot) VALUES ('I05', 'Jones', 'Michael', 'ET05');

-- Insertion dans la table cadeau
INSERT INTO cadeau (id_cadeau, nom_cadeau, poid_cadeau, statut_cadeau, id_intermittent) VALUES ('C01', 'Cadeau 1', 1, 'En livraison', 'I01');
INSERT INTO cadeau (id_cadeau, nom_cadeau, poid_cadeau, statut_cadeau, id_intermittent) VALUES ('C02', 'Cadeau 2', 2, 'Livré', 'I02');
INSERT INTO cadeau (id_cadeau, nom_cadeau, poid_cadeau, statut_cadeau, id_intermittent) VALUES ('C03', 'Cadeau 3', 1, 'En fabrication', 'I03');
INSERT INTO cadeau (id_cadeau, nom_cadeau, poid_cadeau, statut_cadeau, id_intermittent) VALUES ('C04', 'Cadeau 4', 3, 'En préparation', 'I04');
INSERT INTO cadeau (id_cadeau, nom_cadeau, poid_cadeau, statut_cadeau, id_intermittent) VALUES ('C05', 'Cadeau 5', 5, 'Livré', 'I05');

-- Insertion dans la table equipe
INSERT INTO equipe (id_equipe, nom_equipe, id_atelier) VALUES ('E01', 'Equipe Fabrication', 'A01');
INSERT INTO equipe (id_equipe, nom_equipe, id_atelier) VALUES ('E02', 'Equipe Peinture', 'A02');
INSERT INTO equipe (id_equipe, nom_equipe, id_atelier) VALUES ('E03', 'Equipe Emballage', 'A03');
INSERT INTO equipe (id_equipe, nom_equipe, id_atelier) VALUES ('E04', 'Equipe Contrôle', 'A05');
INSERT INTO equipe (id_equipe, nom_equipe, id_atelier) VALUES ('E05', 'Equipe Assemblage', 'A04');


-- Insertion dans la table elfe
INSERT INTO elfe (id_elfe, nom_elfe, role, id_equipe_se_regroupe, id_equipe_dirige, id_elfe_remplace) VALUES ('E01', 'Lutin 1', 'Menuisier', 'E01', 'E01', NULL);
INSERT INTO elfe (id_elfe, nom_elfe, role, id_equipe_se_regroupe, id_equipe_dirige, id_elfe_remplace) VALUES ('E02', 'Lutin 2', 'Peintre', 'E02', NULL, 'E01');
INSERT INTO elfe (id_elfe, nom_elfe, role, id_equipe_se_regroupe, id_equipe_dirige, id_elfe_remplace) VALUES ('E03', 'Lutin 3', 'Emballage', 'E03', NULL, NULL);
INSERT INTO elfe (id_elfe, nom_elfe, role, id_equipe_se_regroupe, id_equipe_dirige, id_elfe_remplace) VALUES ('E04', 'Lutin 4', 'Logistique', 'E01', 'E01', NULL);
INSERT INTO elfe (id_elfe, nom_elfe, role, id_equipe_se_regroupe, id_equipe_dirige, id_elfe_remplace) VALUES ('E05', 'Lutin 5', 'Entretien', 'E04', 'E04', NULL);


-- Insertion dans la table traineau
INSERT INTO traineau (id_traineau, nom_traineau, capacite_traineau, poid) VALUES ('T01', 'Traineau Californie', 8, 500);
INSERT INTO traineau (id_traineau, nom_traineau, capacite_traineau, poid) VALUES ('T02', 'Traineau Nevada', 8, 600);
INSERT INTO traineau (id_traineau, nom_traineau, capacite_traineau, poid) VALUES ('T03', 'Traineau Texas', 8, 650);
INSERT INTO traineau (id_traineau, nom_traineau, capacite_traineau, poid) VALUES ('T04', 'Traineau Oregon', 8, 450);
INSERT INTO traineau (id_traineau, nom_traineau, capacite_traineau, poid) VALUES ('T05', 'Traineau Utah', 8, 800);
INSERT INTO traineau (id_traineau, nom_traineau, capacite_traineau, poid) VALUES ('T06', 'Traineau Washington', 8, 800);
INSERT INTO traineau (id_traineau, nom_traineau, capacite_traineau, poid) VALUES ('T07', 'Traineau Arizona', 8, 800);
INSERT INTO traineau (id_traineau, nom_traineau, capacite_traineau, poid) VALUES ('T08', 'Traineau Alaska', 8, 700);
INSERT INTO traineau (id_traineau, nom_traineau, capacite_traineau, poid) VALUES ('T09', 'Traineau Idaho', 8, 650);
INSERT INTO traineau (id_traineau, nom_traineau, capacite_traineau, poid) VALUES ('T10', 'Traineau Montana', 8, 500);
INSERT INTO traineau (id_traineau, nom_traineau, capacite_traineau, poid) VALUES ('T11', 'Traineau Wyoming', 8, 500);
INSERT INTO traineau (id_traineau, nom_traineau, capacite_traineau, poid) VALUES ('T12', 'Traineau Colorado', 8, 800);

-- Insertion dans la table renne

-- Traîneau 1 - Californie
INSERT INTO renne (id_puce, nom_renne, pos_renne, couleur_nez, poid_renne, id_traineau_gerer, id_traineau_tirer) VALUES ('R01', 'Renne Californie 1', 'Avant', 'Rouge', 150, 'T01', 'T01');
INSERT INTO renne (id_puce, nom_renne, pos_renne, couleur_nez, poid_renne, id_traineau_gerer, id_traineau_tirer) VALUES ('R02', 'Renne Californie 2', 'Milieu', 'Noir', 160, NULL, 'T01');
INSERT INTO renne (id_puce, nom_renne, pos_renne, couleur_nez, poid_renne, id_traineau_gerer, id_traineau_tirer) VALUES ('R03', 'Renne Californie 3', 'Milieu', 'Noir', 140, NULL, 'T01');
INSERT INTO renne (id_puce, nom_renne, pos_renne, couleur_nez, poid_renne, id_traineau_gerer, id_traineau_tirer) VALUES ('R04', 'Renne Californie 4', 'Milieu', 'Noir', 145, NULL, 'T01');
INSERT INTO renne (id_puce, nom_renne, pos_renne, couleur_nez, poid_renne, id_traineau_gerer, id_traineau_tirer) VALUES ('R05', 'Renne Californie 5', 'Arrière', 'Noir', 155, NULL, 'T01');
INSERT INTO renne (id_puce, nom_renne, pos_renne, couleur_nez, poid_renne, id_traineau_gerer, id_traineau_tirer) VALUES ('R06', 'Renne Californie 6', 'Arrière', 'Noir', 150, NULL, 'T01');
INSERT INTO renne (id_puce, nom_renne, pos_renne, couleur_nez, poid_renne, id_traineau_gerer, id_traineau_tirer) VALUES ('R07', 'Renne Californie 7', 'Arrière', 'Noir', 160, NULL, 'T01');
INSERT INTO renne (id_puce, nom_renne, pos_renne, couleur_nez, poid_renne, id_traineau_gerer, id_traineau_tirer) VALUES ('R08', 'Renne Californie 8', 'Arrière', 'Noir', 145, NULL, 'T01');

-- Traîneau 2 - Nevada
INSERT INTO renne (id_puce, nom_renne, pos_renne, couleur_nez, poid_renne, id_traineau_gerer, id_traineau_tirer) VALUES ('R09', 'Renne Nevada 1', 'Avant', 'Rouge', 155, 'T02', 'T02');
INSERT INTO renne (id_puce, nom_renne, pos_renne, couleur_nez, poid_renne, id_traineau_gerer, id_traineau_tirer) VALUES ('R10', 'Renne Nevada 2', 'Milieu', 'Noir', 140, NULL, 'T02');
INSERT INTO renne (id_puce, nom_renne, pos_renne, couleur_nez, poid_renne, id_traineau_gerer, id_traineau_tirer) VALUES ('R11', 'Renne Nevada 3', 'Milieu', 'Noir', 160, NULL, 'T02');
INSERT INTO renne (id_puce, nom_renne, pos_renne, couleur_nez, poid_renne, id_traineau_gerer, id_traineau_tirer) VALUES ('R12', 'Renne Nevada 4', 'Milieu', 'Noir', 145, NULL, 'T02');
INSERT INTO renne (id_puce, nom_renne, pos_renne, couleur_nez, poid_renne, id_traineau_gerer, id_traineau_tirer) VALUES ('R13', 'Renne Nevada 5', 'Arrière', 'Noir', 150, NULL, 'T02');
INSERT INTO renne (id_puce, nom_renne, pos_renne, couleur_nez, poid_renne, id_traineau_gerer, id_traineau_tirer) VALUES ('R14', 'Renne Nevada 6', 'Arrière', 'Noir', 155, NULL, 'T02');
INSERT INTO renne (id_puce, nom_renne, pos_renne, couleur_nez, poid_renne, id_traineau_gerer, id_traineau_tirer) VALUES ('R15', 'Renne Nevada 7', 'Arrière', 'Noir', 140, NULL, 'T02');
INSERT INTO renne (id_puce, nom_renne, pos_renne, couleur_nez, poid_renne, id_traineau_gerer, id_traineau_tirer) VALUES ('R16', 'Renne Nevada 8', 'Arrière', 'Noir', 145,NULL, 'T02');

-- Traîneau 3 - Texas
INSERT INTO renne VALUES ('R17', 'Renne Texas 1', 'Avant', 'Rouge', 150, 'T03', 'T03');
INSERT INTO renne VALUES ('R18', 'Renne Texas 2', 'Milieu', 'Noir', 160, NULL, 'T03');
INSERT INTO renne VALUES ('R19', 'Renne Texas 3', 'Milieu', 'Noir', 140, NULL, 'T03');
INSERT INTO renne VALUES ('R20', 'Renne Texas 4', 'Milieu', 'Noir', 145, NULL, 'T03');
INSERT INTO renne VALUES ('R21', 'Renne Texas 5', 'Arrière', 'Noir', 155, NULL, 'T03');
INSERT INTO renne VALUES ('R22', 'Renne Texas 6', 'Arrière', 'Noir', 150, NULL, 'T03');
INSERT INTO renne VALUES ('R23', 'Renne Texas 7', 'Arrière', 'Noir', 160, NULL, 'T03');
INSERT INTO renne VALUES ('R24', 'Renne Texas 8', 'Arrière', 'Noir', 145, NULL, 'T03');

-- Traîneau 4 - Oregon
INSERT INTO renne VALUES ('R25', 'Renne Oregon 1', 'Avant', 'Rouge', 150, 'T04', 'T04');
INSERT INTO renne VALUES ('R26', 'Renne Oregon 2', 'Milieu', 'Noir', 140, NULL, 'T04');
INSERT INTO renne VALUES ('R27', 'Renne Oregon 3', 'Milieu', 'Noir', 145, NULL, 'T04');
INSERT INTO renne VALUES ('R28', 'Renne Oregon 4', 'Milieu', 'Noir', 155, NULL, 'T04');
INSERT INTO renne VALUES ('R29', 'Renne Oregon 5', 'Arrière', 'Noir', 150, NULL, 'T04');
INSERT INTO renne VALUES ('R30', 'Renne Oregon 6', 'Arrière', 'Noir', 145, NULL, 'T04');
INSERT INTO renne VALUES ('R31', 'Renne Oregon 7', 'Arrière', 'Noir', 160, NULL, 'T04');
INSERT INTO renne VALUES ('R32', 'Renne Oregon 8', 'Arrière', 'Noir', 155, NULL, 'T04');

-- Traîneau 5 - Utah
INSERT INTO renne VALUES ('R33', 'Renne Utah 1', 'Avant', 'Rouge', 160, 'T05', 'T05');
INSERT INTO renne VALUES ('R34', 'Renne Utah 2', 'Milieu', 'Noir', 140, NULL, 'T05');
INSERT INTO renne VALUES ('R35', 'Renne Utah 3', 'Milieu', 'Noir', 150, NULL, 'T05');
INSERT INTO renne VALUES ('R36', 'Renne Utah 4', 'Milieu', 'Noir', 145, NULL, 'T05');
INSERT INTO renne VALUES ('R37', 'Renne Utah 5', 'Arrière', 'Noir', 155, NULL, 'T05');
INSERT INTO renne VALUES ('R38', 'Renne Utah 6', 'Arrière', 'Noir', 160, NULL, 'T05');
INSERT INTO renne VALUES ('R39', 'Renne Utah 7', 'Arrière', 'Noir', 150, NULL, 'T05');
INSERT INTO renne VALUES ('R40', 'Renne Utah 8', 'Arrière', 'Noir', 140, NULL, 'T05');

-- Traîneau 6 - Washington
INSERT INTO renne VALUES ('R41', 'Renne Washington 1', 'Avant', 'Rouge', 155, 'T06', 'T06');
INSERT INTO renne VALUES ('R42', 'Renne Washington 2', 'Milieu', 'Noir', 145, NULL, 'T06');
INSERT INTO renne VALUES ('R43', 'Renne Washington 3', 'Milieu', 'Noir', 150, NULL, 'T06');
INSERT INTO renne VALUES ('R44', 'Renne Washington 4', 'Milieu', 'Noir', 160, NULL, 'T06');
INSERT INTO renne VALUES ('R45', 'Renne Washington 5', 'Arrière', 'Noir', 140, NULL, 'T06');
INSERT INTO renne VALUES ('R46', 'Renne Washington 6', 'Arrière', 'Noir', 150, NULL, 'T06');
INSERT INTO renne VALUES ('R47', 'Renne Washington 7', 'Arrière', 'Noir', 145, NULL, 'T06');
INSERT INTO renne VALUES ('R48', 'Renne Washington 8', 'Arrière', 'Noir', 160, NULL, 'T06');

-- Traîneau 7 - Arizona
INSERT INTO renne VALUES ('R49', 'Renne Arizona 1', 'Avant', 'Rouge', 150, 'T07', 'T07');
INSERT INTO renne VALUES ('R50', 'Renne Arizona 2', 'Milieu', 'Noir', 140, NULL, 'T07');
INSERT INTO renne VALUES ('R51', 'Renne Arizona 3', 'Milieu', 'Noir', 145, NULL, 'T07');
INSERT INTO renne VALUES ('R52', 'Renne Arizona 4', 'Milieu', 'Noir', 155, NULL, 'T07');
INSERT INTO renne VALUES ('R53', 'Renne Arizona 5', 'Arrière', 'Noir', 150, NULL, 'T07');
INSERT INTO renne VALUES ('R54', 'Renne Arizona 6', 'Arrière', 'Noir', 145, NULL, 'T07');
INSERT INTO renne VALUES ('R55', 'Renne Arizona 7', 'Arrière', 'Noir', 160, NULL, 'T07');
INSERT INTO renne VALUES ('R56', 'Renne Arizona 8', 'Arrière', 'Noir', 155, NULL, 'T07');

-- Traîneau 8 - Alaska
INSERT INTO renne VALUES ('R57', 'Renne Alaska 1', 'Avant', 'Rouge', 160, 'T08', 'T08');
INSERT INTO renne VALUES ('R58', 'Renne Alaska 2', 'Milieu', 'Noir', 140, NULL, 'T08');
INSERT INTO renne VALUES ('R59', 'Renne Alaska 3', 'Milieu', 'Noir', 150, NULL, 'T08');
INSERT INTO renne VALUES ('R60', 'Renne Alaska 4', 'Milieu', 'Noir', 145, NULL, 'T08');
INSERT INTO renne VALUES ('R61', 'Renne Alaska 5', 'Arrière', 'Noir', 155, NULL, 'T08');
INSERT INTO renne VALUES ('R62', 'Renne Alaska 6', 'Arrière', 'Noir', 160, NULL, 'T08');
INSERT INTO renne VALUES ('R63', 'Renne Alaska 7', 'Arrière', 'Noir', 150, NULL, 'T08');
INSERT INTO renne VALUES ('R64', 'Renne Alaska 8', 'Arrière', 'Noir', 140, NULL, 'T08');


-- Traîneau 9 - Idaho
INSERT INTO renne VALUES ('R65', 'Renne Idaho 1', 'Avant', 'Rouge', 150, 'T09', 'T09');
INSERT INTO renne VALUES ('R66', 'Renne Idaho 2', 'Milieu', 'Noir', 140, NULL, 'T09');
INSERT INTO renne VALUES ('R67', 'Renne Idaho 3', 'Milieu', 'Noir', 145, NULL, 'T09');
INSERT INTO renne VALUES ('R68', 'Renne Idaho 4', 'Milieu', 'Noir', 155, NULL, 'T09');
INSERT INTO renne VALUES ('R69', 'Renne Idaho 5', 'Arrière', 'Noir', 150, NULL, 'T09');
INSERT INTO renne VALUES ('R70', 'Renne Idaho 6', 'Arrière', 'Noir', 145, NULL, 'T09');
INSERT INTO renne VALUES ('R71', 'Renne Idaho 7', 'Arrière', 'Noir', 160, NULL, 'T09');
INSERT INTO renne VALUES ('R72', 'Renne Idaho 8', 'Arrière', 'Noir', 155, NULL, 'T09');

-- Traîneau 10 - Montana
INSERT INTO renne VALUES ('R73', 'Renne Montana 1', 'Avant', 'Rouge', 160, 'T10', 'T10');
INSERT INTO renne VALUES ('R74', 'Renne Montana 2', 'Milieu', 'Noir', 140, NULL, 'T10');
INSERT INTO renne VALUES ('R75', 'Renne Montana 3', 'Milieu', 'Noir', 150, NULL, 'T10');
INSERT INTO renne VALUES ('R76', 'Renne Montana 4', 'Milieu', 'Noir', 145, NULL, 'T10');
INSERT INTO renne VALUES ('R77', 'Renne Montana 5', 'Arrière', 'Noir', 155, NULL, 'T10');
INSERT INTO renne VALUES ('R78', 'Renne Montana 6', 'Arrière', 'Noir', 160, NULL, 'T10');
INSERT INTO renne VALUES ('R79', 'Renne Montana 7', 'Arrière', 'Noir', 150, NULL, 'T10');
INSERT INTO renne VALUES ('R80', 'Renne Montana 8', 'Arrière', 'Noir', 140, NULL, 'T10');

-- Traîneau 11 - Wyoming
INSERT INTO renne VALUES ('R81', 'Renne Wyoming 1', 'Avant', 'Rouge', 155, 'T11', 'T11');
INSERT INTO renne VALUES ('R82', 'Renne Wyoming 2', 'Milieu', 'Noir', 140, NULL, 'T11');
INSERT INTO renne VALUES ('R83', 'Renne Wyoming 3', 'Milieu', 'Noir', 145, NULL, 'T11');
INSERT INTO renne VALUES ('R84', 'Renne Wyoming 4', 'Milieu', 'Noir', 150, NULL, 'T11');
INSERT INTO renne VALUES ('R85', 'Renne Wyoming 5', 'Arrière', 'Noir', 155, NULL, 'T11');
INSERT INTO renne VALUES ('R86', 'Renne Wyoming 6', 'Arrière', 'Noir', 160, NULL, 'T11');
INSERT INTO renne VALUES ('R87', 'Renne Wyoming 7', 'Arrière', 'Noir', 150, NULL, 'T11');
INSERT INTO renne VALUES ('R88', 'Renne Wyoming 8', 'Arrière', 'Noir', 140, NULL, 'T11');

-- Traîneau 12 - Colorado
INSERT INTO renne VALUES ('R89', 'Renne Colorado 1', 'Avant', 'Rouge', 150, 'T12', 'T12');
INSERT INTO renne VALUES ('R90', 'Renne Colorado 2', 'Milieu', 'Noir', 140, NULL, 'T12');
INSERT INTO renne VALUES ('R91', 'Renne Colorado 3', 'Milieu', 'Noir', 145, NULL, 'T12');
INSERT INTO renne VALUES ('R92', 'Renne Colorado 4', 'Milieu', 'Noir', 155, NULL, 'T12');
INSERT INTO renne VALUES ('R93', 'Renne Colorado 5', 'Arrière', 'Noir', 150, NULL, 'T12');
INSERT INTO renne VALUES ('R94', 'Renne Colorado 6', 'Arrière', 'Noir', 145, NULL, 'T12');
INSERT INTO renne VALUES ('R95', 'Renne Colorado 7', 'Arrière', 'Noir', 160, NULL, 'T12');
INSERT INTO renne VALUES ('R96', 'Renne Colorado 8', 'Arrière', 'Noir', 155, NULL, 'T12');



-- Insertion dans la table a_comme_specialite_principale
INSERT INTO a_comme_specialite_principale (id_elfe, id_specialite) VALUES ('E01', 'S01');
INSERT INTO a_comme_specialite_principale (id_elfe, id_specialite) VALUES ('E02', 'S02');
INSERT INTO a_comme_specialite_principale (id_elfe, id_specialite) VALUES ('E03', 'S03');
INSERT INTO a_comme_specialite_principale (id_elfe, id_specialite) VALUES ('E04', 'S04');
INSERT INTO a_comme_specialite_principale (id_elfe, id_specialite) VALUES ('E05', 'S05');

-- Insertion dans la table a_comme_specialite_secondaire
INSERT INTO a_comme_specialite_secondaire (id_elfe, id_specialite) VALUES ('E01', 'S02');
INSERT INTO a_comme_specialite_secondaire (id_elfe, id_specialite) VALUES ('E02', 'S03');
INSERT INTO a_comme_specialite_secondaire (id_elfe, id_specialite) VALUES ('E03', 'S04');
INSERT INTO a_comme_specialite_secondaire (id_elfe, id_specialite) VALUES ('E04', 'S05');
INSERT INTO a_comme_specialite_secondaire (id_elfe, id_specialite) VALUES ('E05', 'S01');

-- Insertion dans la table passer
INSERT INTO passer (id_atelier, id_cadeau) VALUES ('A01', 'C01');
INSERT INTO passer (id_atelier, id_cadeau) VALUES ('A02', 'C02');
INSERT INTO passer (id_atelier, id_cadeau) VALUES ('A03', 'C03');
INSERT INTO passer (id_atelier, id_cadeau) VALUES ('A04', 'C04');
INSERT INTO passer (id_atelier, id_cadeau) VALUES ('A05', 'C05');

-- Insertion dans la table fabriquer
INSERT INTO fabriquer (id_cadeau, id_matiere) VALUES ('C01', 'M01');
INSERT INTO fabriquer (id_cadeau, id_matiere) VALUES ('C02', 'M02');
INSERT INTO fabriquer (id_cadeau, id_matiere) VALUES ('C03', 'M03');
INSERT INTO fabriquer (id_cadeau, id_matiere) VALUES ('C04', 'M04');
INSERT INTO fabriquer (id_cadeau, id_matiere) VALUES ('C05', 'M05');

-- Insertion dans la table lister
INSERT INTO lister (id_enfant, id_jouet, qte) VALUES ('E01', 'J01', 1);
INSERT INTO lister (id_enfant, id_jouet, qte) VALUES ('E02', 'J02', 2);
INSERT INTO lister (id_enfant, id_jouet, qte) VALUES ('E03', 'J03', 3);
INSERT INTO lister (id_enfant, id_jouet, qte) VALUES ('E04', 'J04', 4);
INSERT INTO lister (id_enfant, id_jouet, qte) VALUES ('E05', 'J05', 5);

-- Insertion dans la table produireAtelier
INSERT INTO produireAtelier (id_atelier, id_jouet) VALUES ('A01', 'J01');
INSERT INTO produireAtelier (id_atelier, id_jouet) VALUES ('A02', 'J02');
INSERT INTO produireAtelier (id_atelier, id_jouet) VALUES ('A03', 'J03');
INSERT INTO produireAtelier (id_atelier, id_jouet) VALUES ('A04', 'J04');
INSERT INTO produireAtelier (id_atelier, id_jouet) VALUES ('A05', 'J05');

-- Insertion dans la table produireSousTraitant
INSERT INTO produireSousTraitant (id_jouet, id_sstraitant) VALUES ('J01', 'ST01');
INSERT INTO produireSousTraitant (id_jouet, id_sstraitant) VALUES ('J02', 'ST02');
INSERT INTO produireSousTraitant (id_jouet, id_sstraitant) VALUES ('J03', 'ST03');
INSERT INTO produireSousTraitant (id_jouet, id_sstraitant) VALUES ('J04', 'ST04');
INSERT INTO produireSousTraitant (id_jouet, id_sstraitant) VALUES ('J05', 'ST05');

-- Insertion dans la table gestion_
INSERT INTO gestion_ (id_equipe, id_entrepot) VALUES ('E01', 'ET01');
INSERT INTO gestion_ (id_equipe, id_entrepot) VALUES ('E02', 'ET02');
INSERT INTO gestion_ (id_equipe, id_entrepot) VALUES ('E03', 'ET03');
INSERT INTO gestion_ (id_equipe, id_entrepot) VALUES ('E04', 'ET04');
INSERT INTO gestion_ (id_equipe, id_entrepot) VALUES ('E05', 'ET05');

-- Insertion dans la table mener
INSERT INTO mener (id_elfe, id_traineau) VALUES ('E01', 'T01');
INSERT INTO mener (id_elfe, id_traineau) VALUES ('E02', 'T02');
INSERT INTO mener (id_elfe, id_traineau) VALUES ('E03', 'T03');
INSERT INTO mener (id_elfe, id_traineau) VALUES ('E04', 'T04');
INSERT INTO mener (id_elfe, id_traineau) VALUES ('E05', 'T05');

-- Insertion dans la table entretenir
INSERT INTO entretenir (id_elfe, id_puce, id_traineau) VALUES ('E01', 'R01', 'T01');
INSERT INTO entretenir (id_elfe, id_puce, id_traineau) VALUES ('E02', 'R02', 'T02');
INSERT INTO entretenir (id_elfe, id_puce, id_traineau) VALUES ('E03', 'R03', 'T03');
INSERT INTO entretenir (id_elfe, id_puce, id_traineau) VALUES ('E04', 'R04', 'T04');
INSERT INTO entretenir (id_elfe, id_puce, id_traineau) VALUES ('E05', 'R05', 'T05');

-- Insertion dans la table paticiper
INSERT INTO paticiper (id_tournee, id_intermittent) VALUES ('T01', 'I01');
INSERT INTO paticiper (id_tournee, id_intermittent) VALUES ('T02', 'I01');
INSERT INTO paticiper (id_tournee, id_intermittent) VALUES ('T03', 'I01');
INSERT INTO paticiper (id_tournee, id_intermittent) VALUES ('T04', 'I01');
INSERT INTO paticiper (id_tournee, id_intermittent) VALUES ('T05', 'I01');
INSERT INTO paticiper (id_tournee, id_intermittent) VALUES ('T02', 'I02');
INSERT INTO paticiper (id_tournee, id_intermittent) VALUES('T03', 'I03');
INSERT INTO paticiper (id_tournee, id_intermittent) VALUES ('T04', 'I04');
INSERT INTO paticiper (id_tournee, id_intermittent) VALUES ('T05', 'I05');

-- Insertion dans la table send
INSERT INTO send (id_traineau, id_entrepot, date_envoie) VALUES ('T01', 'ET01', TO_DATE('2025-12-01', 'YYYY-MM-DD'));
INSERT INTO send (id_traineau, id_entrepot, date_envoie) VALUES ('T02', 'ET02', TO_DATE('2025-12-02', 'YYYY-MM-DD'));
INSERT INTO send (id_traineau, id_entrepot, date_envoie) VALUES ('T03', 'ET03', TO_DATE('2025-12-03', 'YYYY-MM-DD'));
INSERT INTO send (id_traineau, id_entrepot, date_envoie) VALUES ('T04', 'ET04', TO_DATE('2025-12-04', 'YYYY-MM-DD'));
INSERT INTO send (id_traineau, id_entrepot, date_envoie) VALUES ('T05', 'ET05', TO_DATE('2025-12-05', 'YYYY-MM-DD'));


/*partie requete

--Requete 01 :  Afficher les noms des cadeaux disponibles  et leur poids, triés par poids décroissant

SELECT nom_cadeau, poid_cadeau FROM cadeau WHERE statut_cadeau= 'En livraison' ORDER BY poid_cadeau DESC; 


--Requête 02 : Lister les noms et prénoms des enfants qui ont choisi une poupée comme jouet

select distinct  nom_enfant,pren_enfant from enfant e , lister l , jouet j  where e.id_enfant=l.id_enfant and l.id_jouet=j.id_jouet and nom_jouet='Poupée';

--Requête 03 : lister toutes les informations concernant les enfants
select * from enfant;

--Requête 04 :Lister les noms des cadeaux fabriqués à partir de la matière "bois"

select nom_cadeau from cadeau c , fabriquer f ,matiere_premiere m where c.id_cadeau=f.id_cadeau and f.id_matiere=m.id_matiere and lower(nom_matiere)='bois';

--Requête 05 :Lister les noms des équipes qui regroupent des elfes ayant un rôle de Menuisier et de Logistique
select nom_equipe from equipe eq , elfe el where eq.id_equipe=el.id_equipe_se_regroupe and role = 'Menuisier' intersect select nom_equipe from equipe eq , elfe el where eq.id_equipe=el.id_equipe_se_regroupe and role = 'Logistique' ; 

--Requête 06 :Lister les noms des  entrepôts qui ont reçu un traîneau à la date du 25/12/01 ou à la date du 2025/12/03

select nom_entrepot from entrepot e , send s where e.id_entrepot=s.id_entrepot and date_envoie=TO_DATE('2025-12-01', 'YYYY-MM-DD') union select nom_entrepot from entrepot e , send s where e.id_entrepot=s.id_entrepot and date_envoie=TO_DATE('2025-12-03', 'YYYY-MM-DD');

--Requête 07 :  Lister les  noms des traîneaux dont le poids est compris entre 400 et 800
select nom_traineau from traineau where poid <=800 and poid >=400;
--autre méthode :
select nom_traineau from traineau where poid  BETWEEN 400 and 800;


--Requête 08 : Lister les identifiants des entrepôts situés dans les régions Europe, Asie et Afrique
SELECT id_entrepot  FROM entrepot WHERE region IN ('Europe','Asie','Afrique');

--autre méthode :
 SELECT id_entrepot  FROM entrepot WHERE region='Europe' OR region='Asie' OR region='Afrique';

--Requête 09 : Lister les noms qui ne regroupent pas  des elfes ayant un rôle d Emballage
select nom_equipe from equipe MINUS  select nom_equipe from equipe ,elfe where equipe.id_equipe=elfe.id_equipe_se_regroupe and role='Emballage';


--Requête 10 :Lister les identifiants des enfants dont le nom commence par "D" et dont le prénom se termine par "l"
select id_enfant from enfant where nom_enfant LIKE  'D%' AND pren_enfant LIKE '%l';

--Requete 11 : Lister les noms des enfants dont le prénom ne contient pas de voyelle en deuxième position
select Distinct nom_enfant  from enfant e where pren_enfant  NOT LIKE '_a%' AND pren_enfant  NOT LIKE '_e%' AND pren_enfant  NOT LIKE '_u%' AND pren_enfant  NOT LIKE '_i%' AND pren_enfant  NOT LIKE '_o%';


--Requête 12 : Lister toutes les informations sur les entrepôts et les intermittents associés, où l identifiant de l entrepôt correspond à celui de l intermittent
select * from entrepot e ,intermittent i  where e.id_entrepot=i.id_entrepot;

--Requete 13 :Lister les noms des intermittents qui sont affectés à un entrepôt situé en Amérique
 select DISTINCT nom_intermittent from intermittent i where id_entrepot IN (select id_entrepot from entrepot where region='Europe');
 --autre méthode
 select DISTINCT nom_intermittent from intermittent i , entrepot e where e.id_entrepot=i.id_entrepot and region='Europe';


--Requête 14 : Lister les noms des  rennes qui ont une couleur de nez verte ou rouge
select DISTINCT nom_renne from renne where couleur_nez IN ('Rouge', 'Vert');

--Requête 15 : Lister les noms des rennes qui tirent un traineau de poid 500
 select nom_renne from renne r ,  traineau t  where r.id_traineau_tirer=t.id_traineau and poid=500;

--Requête 16 : Lister les noms des enfants dont la taille de l dresse ne dépasse pas 30 caractères
 SELECT nom_enfant FROM enfant WHERE LENGTH(adresse_enfant) <=30;


--Requête 17 : Lister tous les rennes (identifiant et nom) avec, si ils existent, les traîneaux qui les concernent (qu ils  tirent)
SELECT  id_puce , nom_renne , id_traineau FROM renne LEFT OUTER JOIN traineau ON traineau.id_traineau=renne.id_traineau_tirer;

--autre méthode :
SELECT id_puce, nom_renne , id_traineau FROM renne, traineau WHERE renne.id_traineau_tirer = traineau.id_traineau(+);

--Requête 18 : Quels sont les identifiants des intermittents qui ont participé à toutes les tournées 
select distinct id_intermittent from paticiper p1 where not exists ( select id_tournee from tournee minus ( select id_tournee from paticiper p2 where p2.id_intermittent=p1.id_intermittent )) ;
--autre méthode : 
select distinct id_intermittent from paticiper p1 where not exists ( select * from tournee t where not exists ( select * from paticiper p2 where p2.id_tournee=t.id_tournee and p2.id_intermittent=p1.id_intermittent )) ;
--autre méthode :
select id_intermittent from paticiper GROUP BY id_intermittent HAVING COUNT(distinct id_tournee)=(select count(*) from tournee);

--Requête 19 : Quels sont les identifiants des intermittents qui ont participé à toutes les tournées d'Europe 
select distinct id_intermittent from paticiper p1 where not exists ( select id_tournee from tournee t where nom_tournee='Tournee Europe' minus ( select id_tournee from paticiper p2 where p2.id_intermittent=p1.id_intermittent )) ;
--autre méthode : 
select distinct id_intermittent from paticiper p1 where not exists ( select * from tournee t where nom_tournee='Tournee Europe' and not exists ( select * from paticiper p2 where p2.id_tournee=t.id_tournee and p2.id_intermittent=p1.id_intermittent )) ;
--autre méthode :
select id_intermittent from paticiper where id_tournee in ( select id_tournee from tournee where nom_tournee='Tournee Europe') GROUP BY id_intermittent HAVING COUNT(distinct id_tournee)=(select count(*) from tournee where nom_tournee='Tournee Europe');

--Requête 20 : quel est le poids total des traineaux 
SELECT SUM(poid) FROM traineau ;

-- Requête 21 : Quelle est la somme du poids des traîneaux ayant une capacité de 8
select SUM(poid) from traineau where capacite_traineau=8;

--Requête 22 : Quel est le total du poids des traîneaux envoyés à chaque entrepôt 
SELECT s.id_entrepot, SUM(poid)  FROM send s, traineau t  WHERE s.id_traineau = t.id_traineau GROUP BY s.id_entrepot;

-- Requête 23 : Quels sont les identifiants des traîneaux envoyés par un seul entrepôt
SELECT id_traineau FROM send  GROUP BY id_traineau HAVING COUNT(DISTINCT id_entrepot) = 1;


-- Requête 24 : Quel est   le nombre de traîneaux ayant une capacité de 8 
SELECT COUNT(id_traineau)  FROM traineau WHERE capacite_traineau = 8;


-- Requête 25 : Quel est  le poid minimal parmi les traîneaux ayant une capacité de 8 
SELECT MIN(poid)  FROM traineau WHERE capacite_traineau = 8;

-- Requête 26 : Quel est  le poid maximal parmi les traîneaux ayant une capacité de 8 
SELECT MAX(poid)  FROM traineau WHERE capacite_traineau = 8;


-- Requête 27 : Quelles sont les spécialités ayant moin de 2 elfes affecté à une spécialité secondaire
SELECT id_specialite FROM a_comme_specialite_secondaire GROUP BY id_specialite HAVING COUNT(id_elfe) < 2;



--Requête 28 :  creer une table provisoir contenat la liste des enfants avec le total des jouets qu'ils ont commandés  
create view jouenfant as select e.id_enfant, e.nom_enfant, sum(l.qte) as somme
from enfant e , lister l where e.id_enfant = l.id_enfant
group by e.id_enfant, e.nom_enfant ;



--Requete 29 : calculer la charge de chaque traîneau afin de s’assurer qu’ils soient dans la capacité définie pour le traîneau (somme des capacités de chaque renne) 
SELECT r.id_traineau_tirer AS id_traineau, t.nom_traineau, SUM(t.capacite_traineau) AS charge_totale FROM renne r , traineau t where r.id_traineau_tirer = t.id_traineau GROUP BY r.id_traineau_tirer, t.nom_traineau;

--Requete 30 : quels elfes s’occupent de quel traîneau/renne
SELECT e.nom_elfe,t.nom_traineau,r.nom_renne,r.pos_renne FROM elfe e , entretenir en, renne r, traineau t where e.id_elfe = en.id_elfe and en.id_puce = r.id_puce and r.id_traineau_tirer = t.id_traineau;


--Requete 31 : quel est le jouet le plus produit 
SELECT pa.id_jouet, j.nom_jouet, COUNT(*) AS nombre_productions FROM produireAtelier pa, jouet j where pa.id_jouet = j.id_jouet GROUP BY pa.id_jouet, j.nom_jouet HAVING COUNT(*) = (SELECT MAX(COUNT(*)) FROM produireAtelier GROUP BY id_jouet) ORDER BY nombre_productions DESC;


-- Requete 32 : Lors d’un problème avec un jouet, il devra être possible de remonter toute la chaîne permettant ainsi de vérifier les autres jouets issus de cette chaîne 

SELECT
    j.id_jouet,
    j.nom_jouet,
    pa.id_atelier,
    a.nom_atelier,
    st.nom_sstraitant,
    mp.nom_matiere,
    e.nom_elfe
FROM
    produireAtelier pa
JOIN
    jouet j ON pa.id_jouet = j.id_jouet
JOIN
    atelier a ON pa.id_atelier = a.id_atelier
LEFT JOIN
    produireSousTraitant pst ON j.id_jouet = pst.id_jouet
LEFT JOIN
    sous_traitant st ON pst.id_sstraitant = st.id_sstraitant
LEFT JOIN
    fabriquer f ON j.id_jouet = f.id_cadeau
LEFT JOIN
    matiere_premiere mp ON f.id_matiere = mp.id_matiere
LEFT JOIN
    elfe e ON pa.id_atelier = e.id_equipe_se_regroupe
ORDER BY
    pa.id_atelier, j.id_jouet;
*/

