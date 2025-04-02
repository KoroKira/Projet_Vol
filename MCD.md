### **Modèle Conceptuel de Données (MCD) Complet**  

#### **Entités Principales**  
1. **Vol** (_flight_id_, date_vol, heure_départ, heure_arrivée)  
   - **Relations** :  
     - **Aéroport (Départ)** : (1,1) → (0,N)  
     - **Aéroport (Arrivée)** : (1,1) → (0,N)  
     - **TypeAvion** : (1,1) → (0,N)  
     - **Équipage** : (1,1) → (1,N)  
     - **Incident** : (0,N) ← (1,1)  
     - **Réservation** : (0,N) ← (1,1)  

2. **Aéroport** (_code_oaci_, code_iata, nom, ville)  

3. **TypeAvion** (_type_avion_id_, modèle, constructeur)  
   - **Relations** :  
     - **Maintenance** : (1,N) ← (1,1)  

4. **ParamètreVol** (_paramètre_id_, nom_param, type_donnée, phase_vol)  
   - **Relations** :  
     - **SystèmeEmbarqué** : (1,N) → (1,1)  

5. **Météorologie** (_meteo_id_, température, pression, vent)  
   - **Relations** :  
     - **Vol** : (1,1) ↔ (1,1)  

6. **TrajectoirePoint** (_point_id_, latitude, longitude, altitude, horodatage)  
   - **Relations** :  
     - **Vol** : (1,N) ← (1,1)  

---

#### **Nouvelles Entités (Sécurité & Gestion)**  
7. **Équipage** (_equipage_id_, rôle)  
   - **Relations** :  
     - **Employé** : (1,1) → (0,N)  
     - **Vol** : (1,N) ← (1,1)  

8. **Employé** (_employe_id_, nom, prénom, poste)  
   - **Sous-classes** :  
     - **Pilote** (licence)  
     - **Technicien** (qualifications)  

9. **Incident** (_incident_id_, type, gravité, résolution)  
   - **Relations** :  
     - **Vol** : (1,N) ← (1,1)  

10. **Maintenance** (_maintenance_id_, date, statut)  
    - **Relations** :  
      - **TypeAvion** : (1,N) ← (1,1)  
      - **Technicien** : (1,N) ← (1,1)  

11. **Passager** (_passager_id_, nom, prénom)  
    - **Relations** :  
      - **Réservation** : (1,1) → (0,N)  

12. **Réservation** (_reservation_id_, siège)  
    - **Relations** :  
      - **Vol** : (1,N) ← (1,1)  

13. **SystèmeEmbarqué** (_systeme_id_, nom, statut)  
    - **Relations** :  
      - **ParamètreVol** : (1,N) ← (1,1)  

---

