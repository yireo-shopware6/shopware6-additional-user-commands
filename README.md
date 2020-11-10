# Shopware 6 additional user commands
Shopware 6 plugin adding additional user commands to the CLI. The default console only supports two commands (`user:create` and `user:change-password`). This plugin adds the commands `user:list` and `user:delete`. Additionally, this plugin offers a `UserRepository` with handy methods.

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
bin/console user:delete admin
bin/console user:delete admin@shopware.com
```

## Programming with the UserRepository
Inject the `\YireoAdditionalUserCommands\Repository\UserRepository` into your code and use its methods:

- `getAll`
- `getByUsername`
- `getByEmail`
- `deleteByUsername`
- `deleteByEmail`
- `delete`
