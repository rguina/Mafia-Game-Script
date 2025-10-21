# Guide: Cron Auto Runner HTML/JavaScript

## Description

Une solution simple et visuelle pour exécuter automatiquement les crons du jeu sans avoir besoin de configurer Windows Task Scheduler ou Python.

## Comment ça marche?

Le fichier `cron_auto.html` utilise JavaScript pour appeler automatiquement les fichiers PHP cron à intervalles réguliers:
- **Cron Minute** : Toutes les 60 secondes (jail, hospital, travel, bodyguard)
- **Cron 5 Minutes** : Toutes les 5 minutes (energy, will, health, brave)
- **Cron Hourly** : Toutes les heures
- **Cron Daily** : Une fois par jour

## Utilisation

### Méthode 1: Via le navigateur (RECOMMANDÉ)

1. **Ouvrez le fichier dans votre navigateur**:
   ```
   http://mafia-game-script.test/cron_auto.html
   ```

2. **Cliquez sur le bouton "▶️ Démarrer Tous"**

3. **Gardez la page ouverte** - Les crons tourneront automatiquement!

### Méthode 2: Double-cliquez sur le fichier

1. Allez dans `C:\laragon\www\Mafia-Game-Script\`
2. Double-cliquez sur `cron_auto.html`
3. Cliquez sur "▶️ Démarrer Tous"

## Fonctionnalités

### Boutons de contrôle

- **▶️ Démarrer Tous** : Lance tous les crons automatiquement
- **⏹️ Arrêter Tous** : Arrête tous les crons
- **🧪 Test Minute** : Teste le cron minute immédiatement

### Statut en temps réel

Chaque cron affiche son statut:
- 🔴 **Arrêté** : Le cron n'est pas actif
- 🟢 **Actif** : Le cron tourne en arrière-plan
- 🔵 **En cours...** : Le cron est en train de s'exécuter

### Logs

Tous les événements sont enregistrés dans la section logs:
- ✅ Succès (vert)
- ❌ Erreurs (rouge)
- ⚠️ Avertissements (orange)
- 📱 Info (bleu)

## Avantages

✅ **Simple** : Pas besoin de configuration complexe  
✅ **Visuel** : Vous voyez en temps réel ce qui se passe  
✅ **Rapide** : Fonctionne immédiatement  
✅ **Pas d'installation** : Juste un fichier HTML  
✅ **Portable** : Fonctionne sur n'importe quel navigateur  

## Inconvénients

⚠️ **La page doit rester ouverte** : Si vous fermez le navigateur, les crons s'arrêtent  
⚠️ **Pas automatique au démarrage** : Vous devez manuellement ouvrir la page et cliquer sur "Démarrer"  

## Solutions pour garder les crons actifs

### Option 1: Onglet épinglé (Simple)

1. Ouvrez `http://mafia-game-script.test/cron_auto.html`
2. Cliquez sur "Démarrer Tous"
3. Faites un clic droit sur l'onglet → "Épingler l'onglet"
4. Gardez le navigateur ouvert en arrière-plan

### Option 2: Navigateur en arrière-plan

1. Ouvrez Chrome/Firefox
2. Chargez `cron_auto.html`
3. Démarrez les crons
4. Minimisez le navigateur (ne le fermez pas!)

### Option 3: Deuxième écran

Si vous avez 2 écrans, gardez la page ouverte sur le second écran.

## Dépannage

### Les crons ne démarrent pas

1. Vérifiez que vous êtes sur `http://mafia-game-script.test/cron_auto.html` (pas `file://`)
2. Ouvrez la console du navigateur (F12) et vérifiez les erreurs
3. Testez manuellement : `http://mafia-game-script.test/cron_run_minute.php`

### Les timers ne diminuent pas

1. Vérifiez que les crons sont "Actif" (vert)
2. Regardez les logs - des erreurs en rouge?
3. Cliquez sur "Test Minute" et attendez 1 minute

### Page se ferme automatiquement

Le navigateur vous demandera confirmation avant de fermer si les crons sont actifs.

## Comparaison avec Windows Task Scheduler

| Caractéristique | HTML/JS | Task Scheduler |
|-----------------|---------|----------------|
| Installation | ✅ Aucune | ❌ Configuration complexe |
| Interface visuelle | ✅ Oui | ❌ Non |
| Démarre automatiquement | ❌ Non | ✅ Oui |
| Fonctionne fenêtre fermée | ❌ Non | ✅ Oui |
| Facile à débugger | ✅ Oui | ❌ Difficile |

## Recommandation

- **Pour le développement/test** : Utilisez `cron_auto.html` (plus simple!)
- **Pour la production** : Utilisez Windows Task Scheduler ou un vrai serveur Linux

## Notes importantes

- Les crons s'exécutent via HTTP, donc Laragon doit tourner
- Si vous redémarrez l'ordinateur, vous devez rouvrir la page
- Les logs sont gardés en mémoire (max 50 entrées)
- Aucune donnée n'est sauvegardée après fermeture du navigateur

## Astuces

### Garder le navigateur actif

```
Windows: Téléchargez "Don't Sleep" pour empêcher la mise en veille
```

### Lancer automatiquement au démarrage

1. Créez un raccourci de Chrome avec:
   ```
   "C:\Program Files\Google\Chrome\Application\chrome.exe" --new-window "http://mafia-game-script.test/cron_auto.html"
   ```
2. Mettez le raccourci dans:
   ```
   shell:startup
   ```

## Support

Si vous avez des problèmes:
1. Vérifiez les logs dans la page
2. Ouvrez la console navigateur (F12)
3. Testez manuellement les fichiers PHP
