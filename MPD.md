# **Modèle Physique de Données (MPD) Complet**

## **1. Tables Principales (Vol & Infrastructure)**

```sql
CREATE TABLE TypeAvion (
    type_avion_id INT PRIMARY KEY AUTO_INCREMENT,
    modele VARCHAR(100) NOT NULL,
    constructeur VARCHAR(100) NOT NULL,
    capacite INT,
    autonomie_km INT
);

CREATE TABLE Aeroport (
    code_oaci VARCHAR(4) PRIMARY KEY,
    code_iata VARCHAR(3),
    nom VARCHAR(100) NOT NULL,
    ville VARCHAR(100) NOT NULL,
    pays VARCHAR(100) NOT NULL,
    altitude INT  -- en pieds
);

CREATE TABLE Vol (
    flight_id INT PRIMARY KEY AUTO_INCREMENT,
    numero_vol VARCHAR(10) NOT NULL,
    date_vol DATE NOT NULL,
    heure_depart TIME NOT NULL,
    heure_arrivee TIME NOT NULL,
    statut ENUM('planifié', 'décollé', 'atterri', 'annulé', 'retardé') NOT NULL,
    type_avion_id INT NOT NULL,
    aeroport_depart VARCHAR(4) NOT NULL,
    aeroport_arrivee VARCHAR(4) NOT NULL,
    FOREIGN KEY (type_avion_id) REFERENCES TypeAvion(type_avion_id),
    FOREIGN KEY (aeroport_depart) REFERENCES Aeroport(code_oaci),
    FOREIGN KEY (aeroport_arrivee) REFERENCES Aeroport(code_oaci)
);
```

## **2. Données Techniques & Sécurité**

```sql
CREATE TABLE ParametreVol (
    parametre_id INT PRIMARY KEY AUTO_INCREMENT,
    flight_id INT NOT NULL,
    nom_param VARCHAR(100) NOT NULL,
    type_donnee ENUM('DFDR', 'CVR', 'compute', 'capteur') NOT NULL,
    phase_vol ENUM('taxi', 'take-off', 'montée', 'croisière', 'descente', 'approach', 'landing') NOT NULL,
    valeur FLOAT,
    unite VARCHAR(20),
    horodatage DATETIME NOT NULL,
    FOREIGN KEY (flight_id) REFERENCES Vol(flight_id)
);

CREATE TABLE SystemeEmbarque (
    systeme_id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    type ENUM('avionique', 'moteur', 'navigation', 'communication') NOT NULL,
    statut ENUM('opérationnel', 'dégradé', 'hors_service') NOT NULL,
    parametre_id INT,
    FOREIGN KEY (parametre_id) REFERENCES ParametreVol(parametre_id)
);

CREATE TABLE Incident (
    incident_id INT PRIMARY KEY AUTO_INCREMENT,
    flight_id INT NOT NULL,
    type VARCHAR(100) NOT NULL,
    gravite ENUM('mineur', 'majeur', 'critique') NOT NULL,
    description TEXT,
    date_incident DATETIME NOT NULL,
    resolution TEXT,
    FOREIGN KEY (flight_id) REFERENCES Vol(flight_id)
);
```

## **3. Gestion du Personnel & Passagers**

```sql
CREATE TABLE Employe (
    employe_id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    poste ENUM('pilote', 'copilote', 'hôtesse', 'technicien', 'agent_sol') NOT NULL,
    date_embauche DATE NOT NULL
);

CREATE TABLE Equipage (
    equipage_id INT PRIMARY KEY AUTO_INCREMENT,
    flight_id INT NOT NULL,
    employe_id INT NOT NULL,
    role ENUM('commandant', 'copilote', 'hôtesse_chef', 'hôtesse', 'mécanicien') NOT NULL,
    FOREIGN KEY (flight_id) REFERENCES Vol(flight_id),
    FOREIGN KEY (employe_id) REFERENCES Employe(employe_id)
);

CREATE TABLE Passager (
    passager_id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    passeport VARCHAR(50) UNIQUE,
    nationalite VARCHAR(50)
);

CREATE TABLE Reservation (
    reservation_id INT PRIMARY KEY AUTO_INCREMENT,
    flight_id INT NOT NULL,
    passager_id INT NOT NULL,
    siege VARCHAR(10) NOT NULL,
    classe ENUM('economique', 'affaires', 'premiere') NOT NULL,
    FOREIGN KEY (flight_id) REFERENCES Vol(flight_id),
    FOREIGN KEY (passager_id) REFERENCES Passager(passager_id)
);
```

## **4. Maintenance & Météo**

```sql
CREATE TABLE Technicien (
    technicien_id INT PRIMARY KEY AUTO_INCREMENT,
    employe_id INT NOT NULL,
    specialite ENUM('moteurs', 'avionique', 'structure') NOT NULL,
    certification VARCHAR(100),
    FOREIGN KEY (employe_id) REFERENCES Employe(employe_id)
);

CREATE TABLE Maintenance (
    maintenance_id INT PRIMARY KEY AUTO_INCREMENT,
    type_avion_id INT NOT NULL,
    technicien_id INT NOT NULL,
    date_debut DATETIME NOT NULL,
    date_fin DATETIME,
    type ENUM('pré-vol', 'post-vol', 'révision', 'réparation') NOT NULL,
    observations TEXT,
    FOREIGN KEY (type_avion_id) REFERENCES TypeAvion(type_avion_id),
    FOREIGN KEY (technicien_id) REFERENCES Technicien(technicien_id)
);

CREATE TABLE Meteorologie (
    meteo_id INT PRIMARY KEY AUTO_INCREMENT,
    flight_id INT UNIQUE NOT NULL,
    temperature DECIMAL(5,2),  -- en °C
    pression DECIMAL(7,2),     -- en hPa
    vent_vitesse DECIMAL(5,2), -- en km/h
    vent_direction INT,        -- en degrés (0-360)
    visibilite DECIMAL(5,2),   -- en km
    conditions ENUM('clair', 'nuageux', 'pluie', 'neige', 'brouillard', 'orage'),
    FOREIGN KEY (flight_id) REFERENCES Vol(flight_id)
);

CREATE TABLE TrajectoirePoint (
    point_id INT PRIMARY KEY AUTO_INCREMENT,
    flight_id INT NOT NULL,
    latitude DECIMAL(9,6) NOT NULL,
    longitude DECIMAL(9,6) NOT NULL,
    altitude DECIMAL(7,2) NOT NULL,  -- en pieds
    horodatage DATETIME NOT NULL,
    vitesse DECIMAL(6,2),            -- en km/h
    FOREIGN KEY (flight_id) REFERENCES Vol(flight_id)
);
```

---

### **Clés & Optimisations**
- **Clés étrangères** : Toutes les relations sont correctement définies (`FOREIGN KEY`).  
- **Types de données précis** :  
  - `DECIMAL` pour les coordonnées GPS et météo.  
  - `ENUM` pour les statuts prédéfinis (évite les erreurs de saisie).  
- **Index recommandés** :  
  ```sql
  CREATE INDEX idx_vol_date ON Vol(date_vol);
  CREATE INDEX idx_incident_gravite ON Incident(gravite);
  CREATE INDEX idx_trajectoire_vol ON TrajectoirePoint(flight_id);
  ```

