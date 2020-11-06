<?php declare(strict_types=1);

namespace YireoAdditionalUserCommands\Repository;

use Doctrine\DBAL\Connection;
use Shopware\Core\System\User\UserCollection;
use Shopware\Core\System\User\UserEntity;

class UserRepository
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return UserCollection
     */
    public function getAll(): UserCollection
    {
        $builder = $this->connection->createQueryBuilder();
        $collection = new UserCollection;

        $rows = $builder->select(['*'])
            ->from('user')
            ->execute()
            ->fetchAll();

        foreach ($rows as $row) {
            $user = new UserEntity();
            $user->setId($row['id']);
            $user->setUsername((string)$row['username']);
            $user->setFirstName((string)$row['first_name']);
            $user->setLastName((string)$row['last_name']);
            $user->setEmail((string)$row['email']);
            $user->setActive((bool)$row['active']);
            $user->setAdmin((bool)$row['admin']);
            $collection->add($user);
        }

        return $collection;
    }
}
