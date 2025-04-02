# Projet_Vol


# Gestion des vols  
## Objectif :  
Gestion des vols d’une compagnie aérienne.  

## Contexte :  
Une compagnie aérienne veut mettre en place un outil de surveillance des vols pour améliorer sa sécurité en vol afin de réduire le nombre d’incident/d’accident.   
 
## Besoins :
Cette compagnie aérienne a donc besoin de rassembler tous ces vols en service.  

Chaque vol regroupe :  
- « Données » de vol (date vol, heure départ/arrivée vol, type avion,…)
- Aéroport (code OACI, code IATA)
- Paramètres de vol (nom para, type de données (DFDR, CVR, compute), phase de vol (taxi, take-off, cruise, approach, landing),...)
- Météorologie pendant le vol
- Trajectoire du vol (coordonnée google earth)  


## Livrable :
Voir grille d'évaluation



Qualité du MCD : Entités, relations et cardinaux bien identifiés (6 pts)	
MLD : Correctement transformé à partir du MCD avec les clés bien indiquées (4pts)	
MPD : Schéma SQL cohérent avec des bons types de données et des clés correctement choisies (4pts)	
Tout est logique et relié sans erreur (2pts)	Clarté : Schémas bien présentés et faciles à comprendre (2pts)	
Respect du besoin : Le modèle correspond au cahier des charges donné (2pts)



## Architecture du projet
```

```

## Notre projet

On ne doit faire que un MCD, MLD et un MPD, rien de plus, pas de site, pas de PHPMyAdmin, rien de tout ça.



## Tentative:

**Modèle Logique de Données (MLD):**

1. **Vol** (`flight_id` PK, date_vol, heure_depart, heure_arrivee, `type_avion_id` FK, `aeroport_depart` FK, `aeroport_arrivee` FK)
2. **Aéroport** (`code_oaci` PK, code_iata, nom, ville)
3. **ParamètreVol** (`parametre_id` PK, `flight_id` FK, nom_param, type_donnee, phase_vol)
4. **Météorologie** (`meteo_id` PK, `flight_id` FK UNIQUE, temperature, pression)
5. **Trajectoire** (`trajectoire_id` PK, `flight_id` FK, coordonnees, horodatage)
6. **TypeAvion** (`type_avion_id` PK, modele, constructeur)
7. **Compagnie** (`compagnie_id` PK, nom, code_iata, code_oaci)
8. **TrajectoirePoint** (`id` PK, `flight_id` FK, latitude, longitude, altitude, horodatage)
9. **Employe** (`employe_id` PK, nom, prenom, poste, date_embauche, salaire, `compagnie_id` FK)
10. **Pilote** (`pilote_id` PK, `employe_id` FK, `compagnie_id` FK)
11. **Hotesse** (`hotesse_id` PK, `employe_id` FK, `compagnie_id` FK)
12. **Passager** (`passager_id` PK, nom, prenom, date_naissance, nationalite, `compagnie_id` FK)
13. **Reservation** (`reservation_id` PK, `passager_id` FK, `flight_id` FK, date_reservation, nombre_places)
14. **Bagage** (`bagage_id` PK, `passager_id` FK, poids, description)
15. **Repas** (`repas_id` PK, `passager_id` FK, type_repas, description)
16. **Service** (`service_id` PK, `compagnie_id` FK, type_service, description)
17. **Colis** (`colis_id` PK, `service_id` FK, poids, description)
18. **Marchandise** (`marchandise_id` PK, `service_id` FK, poids, description)
19. **Carburant** (`carburant_id` PK, `compagnie_id` FK, quantite, description)
20. **Entretien** (`entretien_id` PK, `type_avion_id` FK, `compagnie_id` FK, date_entretien, description)
21. **Piece** (`piece_id` PK, `entretien_id` FK, type_piece, description)
22. **Mecanicien** (`mecanicien_id` PK, `employe_id` FK, `compagnie_id` FK)
23. **Maintenance** (`maintenance_id` PK, `mecanicien_id` FK, `entretien_id` FK, date_maintenance, description)
24. **PassageFrontiere** (`passage_id` PK, `passager_id` FK, `aeroport_id` FK, date_passage, description)
25. **Douane** (`douane_id` PK, `passage_id` FK, type_controle, description)
26. **Securite** (`securite_id` PK, `passage_id` FK, type_controle, description)
27. **Police** (`police_id` PK, `passage_id` FK, type_controle, description)
28. **Controle** (`controle_id` PK, `passage_id` FK, type_controle, description)
29. **BagagePassager** (`bagage_id` PK, `passager_id` FK, poids, description)
30. **BagageCabine** (`bagage_id` PK, `passager_id` FK, poids, description)
31. **BagageSoute** (`bagage_id` PK, `passager_id` FK, poids, description)
32. **BagageMain** (`bagage_id` PK, `passager_id` FK, poids, description)
33. **BagageEnregistre** (`bagage_id` PK, `passager_id` FK, poids, description)
34. **Animaux** (`animal_id` PK, `passager_id` FK, type_animal, description)
35. **AnimauxCabine** (`animal_id` PK, `passager_id` FK, type_animal, description)
36. **AnimauxSoute** (`animal_id` PK, `passager_id` FK, type_animal, description)

**Modèle Physique de Données (MPD) - SQL Schema:**

```sql
-- Suppression des tables si elles existent déjà (sécurité pour recréation propre)
DROP TABLE IF EXISTS TrajectoirePoint, Trajectoire, ParametreVol, Meteorologie, Vol, TypeAvion, Aeroport, Compagnie;

-- Table des compagnies aériennes
CREATE TABLE Compagnie (
    compagnie_id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    code_iata VARCHAR(3) UNIQUE,
    code_oaci VARCHAR(4) UNIQUE
);

-- Table des aéroports
CREATE TABLE Aeroport (
    code_oaci VARCHAR(4) PRIMARY KEY,
    code_iata VARCHAR(3) UNIQUE,
    nom VARCHAR(100) NOT NULL,
    ville VARCHAR(100) NOT NULL
);

-- Table des types d'avions
CREATE TABLE TypeAvion (
    type_avion_id INT PRIMARY KEY AUTO_INCREMENT,
    modele VARCHAR(50) NOT NULL,
    constructeur VARCHAR(50) NOT NULL
);

-- Table des vols
CREATE TABLE Vol (
    flight_id INT PRIMARY KEY AUTO_INCREMENT,
    date_vol DATE NOT NULL,
    heure_depart TIME NOT NULL,
    heure_arrivee TIME NOT NULL,
    type_avion_id INT NOT NULL,
    aeroport_depart VARCHAR(4) NOT NULL,
    aeroport_arrivee VARCHAR(4) NOT NULL,
    compagnie_id INT NOT NULL,
    FOREIGN KEY (type_avion_id) REFERENCES TypeAvion(type_avion_id),
    FOREIGN KEY (aeroport_depart) REFERENCES Aeroport(code_oaci),
    FOREIGN KEY (aeroport_arrivee) REFERENCES Aeroport(code_oaci),
    FOREIGN KEY (compagnie_id) REFERENCES Compagnie(compagnie_id)
);

-- Table des relevés météorologiques (permet plusieurs relevés par vol)
CREATE TABLE Meteorologie (
    meteo_id INT PRIMARY KEY AUTO_INCREMENT,
    flight_id INT NOT NULL,
    temperature DECIMAL(5,2),
    pression DECIMAL(7,2),
    horodatage DATETIME NOT NULL,
    FOREIGN KEY (flight_id) REFERENCES Vol(flight_id)
);

-- Table des paramètres de vol
CREATE TABLE ParametreVol (
    parametre_id INT PRIMARY KEY AUTO_INCREMENT,
    flight_id INT NOT NULL,
    nom_param VARCHAR(50) NOT NULL,
    type_donnee VARCHAR(50) NOT NULL,
    phase_vol VARCHAR(50) NOT NULL,
    FOREIGN KEY (flight_id) REFERENCES Vol(flight_id)
);

-- Table des trajectoires de vol (avec coordonnées détaillées)
CREATE TABLE TrajectoirePoint (
    id INT PRIMARY KEY AUTO_INCREMENT,
    flight_id INT NOT NULL,
    latitude DECIMAL(9,6) NOT NULL,
    longitude DECIMAL(9,6) NOT NULL,
    altitude INT,
    horodatage DATETIME NOT NULL,
    FOREIGN KEY (flight_id) REFERENCES Vol(flight_id)
);
```
