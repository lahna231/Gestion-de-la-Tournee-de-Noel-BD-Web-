DROP TABLE  send;
DROP TABLE  paticiper ;
DROP TABLE  entretenir ;
DROP TABLE  mener ;
DROP TABLE  gestion_ ;
DROP TABLE  produireSousTraitant ;
DROP TABLE  produireAtelier ;
DROP TABLE  lister ;
DROP TABLE  fabriquer ;
DROP TABLE  passer ;
DROP TABLE  a_comme_specialite_secondaire ;
DROP TABLE  a_comme_specialite_principale ;
DROP TABLE  renne ;
DROP TABLE  traineau ;
DROP TABLE  elfe ;
DROP TABLE  equipe ;
DROP TABLE  cadeau ;
DROP TABLE  intermittent ;
DROP TABLE  entrepot ;
DROP TABLE  enfant ;
DROP TABLE tournee ;
DROP TABLE sous_traitant ;
DROP TABLE jouet ;
DROP TABLE matiere_premiere ;
DROP TABLE atelier ;
DROP TABLE specialite ;

/* Nous avons supprimé les tables dans un ordre spécifique, mais si nous ne souhaitons pas les supprimer dans cet ordre, l'utilisation des contraintes CASCADE (cascade constraints)suffira car ils permettent  de supprimer automatiquement les enregistrements liés dans d'autres tables lorsque la table principale est supprimée. */
