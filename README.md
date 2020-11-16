# Shopware 6 additional user commands
Shopware 6 plugin adding additional user commands to the CLI. The default console only supports two commands (`user:create` and `user:change-password`). This plugin adds the commands `user:list` and `user:delete`.

## Installation
```bash
composer require yireo/shopware6-additional-user-commands
bin/console plugin:refresh
bin/console plugin:install YireoAdditionalUserCommands
bin/console cache:clear
```

## Usage
```bash
bin/console user:list
bin/console user:delete --username=admin
bin/console user:delete --email=admin@shopware.com
```
