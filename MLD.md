### **Modèle Logique de Données (MLD) Complet**

---

#### **1. Tables Principales (Vol & Infrastructure)**
| Table          | Attributs (Clés en gras)                                                                 | Relations                     |
|----------------|-----------------------------------------------------------------------------------------|-------------------------------|
| **TypeAvion**  | **type_avion_id**, modele, constructeur, capacite, autonomie_km                         | → Vol (1-N)                   |
| **Aeroport**   | **code_oaci**, code_iata, nom, ville, pays, altitude                                    | ← Vol (départ/arrivée, 1-N)   |
| **Vol**        | **flight_id**, numero_vol, date_vol, heure_depart, heure_arrivee, statut,               | → ParametreVol, Equipage, Incident, Reservation, Meteorologie, TrajectoirePoint |
|                | **type_avion_id** (FK), **aeroport_depart** (FK), **aeroport_arrivee** (FK)             |                               |

---

#### **2. Données Techniques & Sécurité**
| Table               | Attributs                                                                               | Relations                     |
|---------------------|-----------------------------------------------------------------------------------------|-------------------------------|
| **ParametreVol**    | **parametre_id**, **flight_id** (FK), nom_param, type_donnee, phase_vol, valeur, unite, horodatage | ← Vol (1-N), → SystemeEmbarque (1-N) |
| **SystemeEmbarque** | **systeme_id**, nom, type, statut, **parametre_id** (FK)                                | ← ParametreVol (1-1)          |
| **Incident**        | **incident_id**, **flight_id** (FK), type, gravite, description, date_incident, resolution | ← Vol (1-N)                   |

---

#### **3. Gestion du Personnel & Passagers**
| Table          | Attributs                                                                               | Relations                     |
|----------------|-----------------------------------------------------------------------------------------|-------------------------------|
| **Employe**    | **employe_id**, nom, prenom, poste, date_embauche                                       | → Equipage, Technicien (1-N)  |
| **Equipage**   | **equipage_id**, **flight_id** (FK), **employe_id** (FK), role                          | ← Vol (1-N), ← Employe (1-N)  |
| **Passager**   | **passager_id**, nom, prenom, passeport, nationalite                                    | → Reservation (1-N)           |
| **Reservation**| **reservation_id**, **flight_id** (FK), **passager_id** (FK), siege, classe             | ← Vol (1-N), ← Passager (1-N) |

---

#### **4. Maintenance & Météo**
| Table               | Attributs                                                                               | Relations                     |
|---------------------|-----------------------------------------------------------------------------------------|-------------------------------|
| **Technicien**      | **technicien_id**, **employe_id** (FK), specialite, certification                       | ← Employe (1-1), → Maintenance (1-N) |
| **Maintenance**     | **maintenance_id**, **type_avion_id** (FK), **technicien_id** (FK), date_debut, date_fin, type, observations | ← TypeAvion (1-N), ← Technicien (1-N) |
| **Meteorologie**    | **meteo_id**, **flight_id** (FK UNIQUE), temperature, pression, vent_vitesse, vent_direction, visibilite, conditions | ← Vol (1-1)                  |
| **TrajectoirePoint**| **point_id**, **flight_id** (FK), latitude, longitude, altitude, horodatage, vitesse    | ← Vol (1-N)                   |

---

### **Règles de Gestion & Contraintes**
1. **Vol** :  
   - Un vol a **exactement 1** aéroport de départ et d'arrivée (`NOT NULL`).  
   - `statut` est contraint à 5 valeurs possibles (planifié/décollé/atterri/annulé/retardé).  

2. **ParametreVol** :  
   - `type_donnee` et `phase_vol` utilisent des `ENUM` pour éviter les erreurs.  
   - Chaque paramètre est lié à **un seul vol** (`flight_id` obligatoire).  

3. **Incident** :  
   - La `gravite` doit être renseignée (mineur/majeur/critique).  

4. **Equipage** :  
   - Un employé ne peut être assigné qu'à **un seul vol** à la fois (controle applicatif).  

5. **Maintenance** :  
   - `date_debut` est obligatoire, `date_fin` peut être `NULL` (si maintenance en cours).  



---

### **Visualisation des Relations**
```
Vol ────┬── Aeroport (Départ/Arrivée)
        ├── ParametreVol ─── SystemeEmbarque
        ├── Incident
        ├── Equipage ─── Employe
        ├── Reservation ─── Passager
        ├── Meteorologie
        └── TrajectoirePoint

TypeAvion ─── Maintenance ─── Technicien ─── Employe
```

Ce MLD intègre **toutes les entités du MCD** avec leurs relations, types de données et contraintes, tout en restant optimisé pour la gestion de la sécurité aérienne.