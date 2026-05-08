Voici le contenu du fichier PDF converti au format Markdown :

---

PROJET S4 

Concept général 

* 
**Composition :** Groupe de 3 (Mixte fille/garçon) 


* 
**Date limite :** À rendre ce lundi 11 mai 2026 


* 
**Livraison :** via formulaire Google Forms 


* Lien du code source (GitLab ou GitHub) 


* Script SQL de la base de données 


* Liste des membres du groupe 


* Liste Google Sheet pour le suivi des tâches 




* **Gestion de version :**
* Les commits et push doivent être effectués tout au long du projet.


* La branche **Main** est la branche de référence (nécessite un merge).





---

Thème 

Mise en place d'une application pour sélectionner un régime alimentaire adapté selon ses objectifs.

* L'utilisateur saisit ses informations : genre, taille, poids.


* Le système affiche l'Indice de Masse Corporelle (**IMC**).



---

Front Office 

### Fonctionnalités minimum :

* 
**Inscription et login :** L'inscription doit séparer les informations utilisateur (nom, email, genre) des informations de santé (taille, poids) sur deux pages distinctes.


* 
**Profil :** Complétion du profil utilisateur.


* 
**Objectifs :** L'utilisateur peut choisir entre 3 objectifs:


1. Augmenter son poids.


2. Réduire son poids.


3. Atteindre son IMC idéal.




* 
**Suggestions :** L'application suggère des régimes et l'activité sportive nécessaire pour une durée déterminée.


* 
**Export :** Possibilité d'exporter les données sur PDF.


* 
**Porte-monnaie :** Rechargement par code.


* 
**Option Gold :** Option payante (prix et accès au choix du développeur) offrant **15% de remise** sur tous les régimes.



---

Back Office 

### Fonctionnalités :

* 
**Authentification :** Page de connexion au démarrage.


* 
**Dashboard :** Statistiques avec graphiques et tableaux croisés.


* **Gestion (CRUD) :**
* Régimes : Prix variant selon la durée, impact sur le poids.


* Composition des régimes : Définition du % de viande, % de poisson et % de volaille.


* Activités sportives.


* Paramètres nécessaires.




* 
**Validation :** Validation des codes pour le porte-monnaie des utilisateurs.



---

Technologies et Données 

### Stack Technique :

* 
**Langage/Framework :** PHP + Codeigniter 


* 
**Interface :** HTML/CSS, Javascript, AJAX 


* 
**Base de données :** MySQL ou Postgres 



### Données minimales à inclure :

* 5 utilisateurs 


* 15 codes 


* 5 régimes 


* 5 activités sportives