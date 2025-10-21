# Configuration des Crons sous Windows (Task Scheduler)

## Vue d'ensemble

Les crons sont des tâches automatiques qui maintiennent le jeu à jour:
- **Minute** : Met à jour jail, hospital, voyage, bodyguard
- **5 Minutes** : Met à jour energy, will, health, brave
- **Hourly** : Tâches de maintenance horaires
- **Daily** : Réinitialise quotidiennement certaines statistiques

## Fichiers créés

4 fichiers batch ont été créés:
- `cron_minute.bat` - S'exécute chaque minute
- `cron_five.bat` - S'exécute toutes les 5 minutes
- `cron_hour.bat` - S'exécute chaque heure
- `cron_day.bat` - S'exécute une fois par jour

## Configuration du Task Scheduler (Planificateur de tâches)

### Méthode 1: Via PowerShell (Automatique - RECOMMANDÉ)

Ouvrez PowerShell **en tant qu'administrateur** et copiez-collez ces commandes:

```powershell
# Tâche 1: Cron Minute (chaque minute)
$action = New-ScheduledTaskAction -Execute "C:\laragon\www\Mafia-Game-Script\cron_minute.bat"
$trigger = New-ScheduledTaskTrigger -Once -At (Get-Date) -RepetitionInterval (New-TimeSpan -Minutes 1) -RepetitionDuration ([TimeSpan]::MaxValue)
Register-ScheduledTask -TaskName "Mafia Game - Cron Minute" -Action $action -Trigger $trigger -Description "Updates jail, hospital, travel, bodyguard every minute"

# Tâche 2: Cron 5 Minutes (toutes les 5 minutes)
$action = New-ScheduledTaskAction -Execute "C:\laragon\www\Mafia-Game-Script\cron_five.bat"
$trigger = New-ScheduledTaskTrigger -Once -At (Get-Date) -RepetitionInterval (New-TimeSpan -Minutes 5) -RepetitionDuration ([TimeSpan]::MaxValue)
Register-ScheduledTask -TaskName "Mafia Game - Cron Five" -Action $action -Trigger $trigger -Description "Updates energy, will, health, brave every 5 minutes"

# Tâche 3: Cron Hourly (chaque heure)
$action = New-ScheduledTaskAction -Execute "C:\laragon\www\Mafia-Game-Script\cron_hour.bat"
$trigger = New-ScheduledTaskTrigger -Once -At (Get-Date) -RepetitionInterval (New-TimeSpan -Hours 1) -RepetitionDuration ([TimeSpan]::MaxValue)
Register-ScheduledTask -TaskName "Mafia Game - Cron Hour" -Action $action -Trigger $trigger -Description "Hourly game maintenance"

# Tâche 4: Cron Daily (une fois par jour à minuit)
$action = New-ScheduledTaskAction -Execute "C:\laragon\www\Mafia-Game-Script\cron_day.bat"
$trigger = New-ScheduledTaskTrigger -Daily -At "00:00"
Register-ScheduledTask -TaskName "Mafia Game - Cron Day" -Action $action -Trigger $trigger -Description "Daily game resets and maintenance"
```

### Méthode 2: Manuellement via l'interface graphique

1. **Ouvrir le Planificateur de tâches**:
   - Appuyez sur `Win + R`
   - Tapez `taskschd.msc`
   - Appuyez sur Entrée

2. **Créer une tâche** (répétez pour chaque cron):
   - Cliquez sur "Créer une tâche de base..." dans le panneau de droite
   
3. **Configuration de la tâche**:
   
   **Pour cron_minute.bat:**
   - Nom: `Mafia Game - Cron Minute`
   - Description: `Updates jail, hospital, travel, bodyguard every minute`
   - Déclencheur: Quotidiennement
   - Heure: Maintenant (ou 00:00)
   - Action: Démarrer un programme
   - Programme: `C:\laragon\www\Mafia-Game-Script\cron_minute.bat`
   - Après création, double-cliquez sur la tâche → Déclencheurs → Modifier
   - Cochez "Répéter la tâche toutes les" → Sélectionnez "1 minute"
   - Durée: "Indéfiniment"
   
   **Pour cron_five.bat:**
   - Nom: `Mafia Game - Cron Five`
   - Description: `Updates energy, will, health, brave every 5 minutes`
   - Répéter toutes les: **5 minutes**
   
   **Pour cron_hour.bat:**
   - Nom: `Mafia Game - Cron Hour`
   - Description: `Hourly game maintenance`
   - Répéter toutes les: **1 heure**
   
   **Pour cron_day.bat:**
   - Nom: `Mafia Game - Cron Day`
   - Description: `Daily game resets and maintenance`
   - Déclencheur: **Quotidiennement** à 00:00 (pas de répétition)

## Vérification

### 1. Vérifier que les tâches sont créées

```powershell
Get-ScheduledTask | Where-Object {$_.TaskName -like "*Mafia Game*"}
```

Vous devriez voir 4 tâches.

### 2. Tester manuellement un cron

Dans PowerShell:
```powershell
C:\laragon\www\Mafia-Game-Script\cron_minute.bat
```

Ou testez directement le fichier PHP:
```powershell
C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64\php.exe C:\laragon\www\Mafia-Game-Script\cron_run_minute.php
```

### 3. Vérifier les logs

Vous pouvez vérifier dans la base de données si les timers diminuent:
```sql
-- Vérifier le temps de jail
SELECT username, jail FROM users WHERE jail > 0;

-- Vérifier le temps d'hôpital
SELECT username, hospital FROM users WHERE hospital > 0;
```

## Désactiver les tâches

Pour désactiver temporairement:
```powershell
Disable-ScheduledTask -TaskName "Mafia Game - Cron Minute"
Disable-ScheduledTask -TaskName "Mafia Game - Cron Five"
Disable-ScheduledTask -TaskName "Mafia Game - Cron Hour"
Disable-ScheduledTask -TaskName "Mafia Game - Cron Day"
```

## Supprimer les tâches

```powershell
Unregister-ScheduledTask -TaskName "Mafia Game - Cron Minute" -Confirm:$false
Unregister-ScheduledTask -TaskName "Mafia Game - Cron Five" -Confirm:$false
Unregister-ScheduledTask -TaskName "Mafia Game - Cron Hour" -Confirm:$false
Unregister-ScheduledTask -TaskName "Mafia Game - Cron Day" -Confirm:$false
```

## Dépannage

### Les tâches ne s'exécutent pas

1. Vérifiez que le chemin PHP est correct:
   ```
   C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64\php.exe
   ```

2. Vérifiez les logs Windows:
   - Planificateur de tâches → Cliquez sur la tâche → Onglet "Historique"

3. Exécutez manuellement le batch pour voir les erreurs:
   ```
   C:\laragon\www\Mafia-Game-Script\cron_minute.bat
   ```

### Le timer de jail ne diminue pas

1. Vérifiez que le cron minute s'exécute bien
2. Vérifiez dans la base de données:
   ```sql
   SELECT userid, username, jail FROM users WHERE userid = 1;
   ```
3. Attendez 1 minute puis vérifiez à nouveau

## Notes importantes

- Les crons doivent tourner **en permanence** pour que le jeu fonctionne correctement
- Si vous éteignez l'ordinateur, les tâches ne s'exécuteront pas
- Pour un serveur de production, utilisez un VPS Linux avec de vrais cron jobs
- Sur Windows, les tâches peuvent avoir un léger délai (quelques secondes)

## Commandes utiles

Lister toutes les tâches:
```powershell
Get-ScheduledTask | Where-Object {$_.TaskName -like "*Mafia*"}
```

Voir le statut d'une tâche:
```powershell
Get-ScheduledTaskInfo -TaskName "Mafia Game - Cron Minute"
```

Activer toutes les tâches:
```powershell
Enable-ScheduledTask -TaskName "Mafia Game - Cron Minute"
Enable-ScheduledTask -TaskName "Mafia Game - Cron Five"
Enable-ScheduledTask -TaskName "Mafia Game - Cron Hour"
Enable-ScheduledTask -TaskName "Mafia Game - Cron Day"
```
