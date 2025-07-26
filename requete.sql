
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

--Requete 13 :Lister les noms des intermittents qui sont affectés à un entrepôt situé en Europe 
 select DISTINCT nom_intermittent from intermittent i where id_entrepot IN (select id_entrepot from entrepot where region='Europe');
 --autre méthode
 select DISTINCT nom_intermittent from intermittent i , entrepot e where e.id_entrepot=i.id_entrepot and region='Europe';


--Requête 14 : Lister les noms des rennes qui tirent un traineau de poid 500
 select nom_renne from renne r ,  traineau t  where r.id_traineau_tirer=t.id_traineau and poid=500;

--Requête 15 : Lister les noms des enfants dont la taille de l dresse ne dépasse pas 30 caractères
 SELECT nom_enfant FROM enfant WHERE LENGTH(adresse_enfant) <=30;


--Requête 16 : Lister tous les rennes (identifiant et nom) avec, si ils existent, les traîneaux qui les concernent (qu ils  tirent)
SELECT  id_puce , nom_renne , id_traineau FROM renne LEFT OUTER JOIN traineau ON traineau.id_traineau=renne.id_traineau_tirer;

--autre méthode :
SELECT id_puce, nom_renne , id_traineau FROM renne, traineau WHERE renne.id_traineau_tirer = traineau.id_traineau(+);

--Requête 17 : Quels sont les identifiants des intermittents qui ont participé à toutes les tournées 
select distinct id_intermittent from paticiper p1 where not exists ( select id_tournee from tournee minus ( select id_tournee from paticiper p2 where p2.id_intermittent=p1.id_intermittent )) ;
--autre méthode : 
select distinct id_intermittent from paticiper p1 where not exists ( select * from tournee t where not exists ( select * from paticiper p2 where p2.id_tournee=t.id_tournee and p2.id_intermittent=p1.id_intermittent )) ;
--autre méthode :
select id_intermittent from paticiper GROUP BY id_intermittent HAVING COUNT(distinct id_tournee)=(select count(*) from tournee);

--Requête 18 : Quels sont les identifiants des intermittents qui ont participé à toutes les tournées d'Europe 
select distinct id_intermittent from paticiper p1 where not exists ( select id_tournee from tournee t where nom_tournee='Tournee Europe' minus ( select id_tournee from paticiper p2 where p2.id_intermittent=p1.id_intermittent )) ;
--autre méthode : 
select distinct id_intermittent from paticiper p1 where not exists ( select * from tournee t where nom_tournee='Tournee Europe' and not exists ( select * from paticiper p2 where p2.id_tournee=t.id_tournee and p2.id_intermittent=p1.id_intermittent )) ;
--autre méthode :
select id_intermittent from paticiper where id_tournee in ( select id_tournee from tournee where nom_tournee='Tournee Europe') GROUP BY id_intermittent HAVING COUNT(distinct id_tournee)=(select count(*) from tournee where nom_tournee='Tournee Europe');

--Requête 19 : quel est le poids total des traineaux 
SELECT SUM(poid) FROM traineau ;

-- Requête 20 : Quelle est la somme du poids des traîneaux ayant une capacité de 8
select SUM(poid) from traineau where capacite_traineau=8;

--Requête 21 : Quel est le total du poids des traîneaux envoyés à chaque entrepôt 
SELECT s.id_entrepot, SUM(poid)  FROM send s, traineau t  WHERE s.id_traineau = t.id_traineau GROUP BY s.id_entrepot;

-- Requête 22 : Quels sont les identifiants des traîneaux envoyés par un seul entrepôt
SELECT id_traineau FROM send  GROUP BY id_traineau HAVING COUNT(DISTINCT id_entrepot) = 1;


-- Requête 23 : Quel est   le nombre de traîneaux ayant une capacité de 8 
SELECT COUNT(id_traineau)  FROM traineau WHERE capacite_traineau = 8;


-- Requête 24 : Quel est  le poid minimal parmi les traîneaux ayant une capacité de 8 
SELECT MIN(poid)  FROM traineau WHERE capacite_traineau = 8;

-- Requête 25 : Quel est  le poid maximal parmi les traîneaux ayant une capacité de 8 
SELECT MAX(poid)  FROM traineau WHERE capacite_traineau = 8;


-- Requête 26 : Quelles sont les spécialités ayant moin de 2 elfes affecté à une spécialité secondaire
SELECT id_specialite FROM a_comme_specialite_secondaire GROUP BY id_specialite HAVING COUNT(id_elfe) < 2;



--Requête 27 :  creer une table provisoir contenat la liste des enfants avec le total des jouets qu'ils ont commandés  
create view jouenfant as select e.id_enfant, e.nom_enfant, sum(l.qte) as somme
from enfant e , lister l where e.id_enfant = l.id_enfant
group by e.id_enfant, e.nom_enfant ;



--Requete 28 : calculer la charge de chaque traîneau afin de s’assurer qu’ils soient dans la capacité définie pour le traîneau (somme des capacités de chaque renne) 
SELECT r.id_traineau_tirer AS id_traineau, t.nom_traineau, SUM(t.capacite_traineau) AS charge_totale FROM renne r , traineau t where r.id_traineau_tirer = t.id_traineau GROUP BY r.id_traineau_tirer, t.nom_traineau;

--Requete 29 : quels elfes s’occupent de quel traîneau/renne
SELECT e.nom_elfe,t.nom_traineau,r.nom_renne,r.pos_renne FROM elfe e , entretenir en, renne r, traineau t where e.id_elfe = en.id_elfe and en.id_puce = r.id_puce and r.id_traineau_tirer = t.id_traineau;


--Requete 30 : quel est le jouet le plus produit 
SELECT pa.id_jouet, j.nom_jouet, COUNT(*) AS nombre_productions FROM produireAtelier pa, jouet j where pa.id_jouet = j.id_jouet GROUP BY pa.id_jouet, j.nom_jouet HAVING COUNT(*) = (SELECT MAX(COUNT(*)) FROM produireAtelier GROUP BY id_jouet) ORDER BY nombre_productions DESC;


-- Requete 31 : Lors d’un problème avec un jouet, il devra être possible de remonter toute la chaîne permettant ainsi de vérifier les autres jouets issus de cette chaîne 

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

