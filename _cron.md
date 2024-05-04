# Tâche planifiée

## Cron executer un nettoyage des Token expirés(côter serveur)

### Executer un script .php côter serveur tous les jours a 0H

=>    0 0 * * * /path/to/php /var/www/html/reset_token.php

### Executer un script .php côter serveur toutes les heures

=>    0 * * * * /path/to/php /var/www/html/reset_token.php

### Executer un script .php côter serveur toutes les minutes

=>    * * * * * /path/to/php /var/www/html/reset_token.php

(script à prévoir)