<?php declare(strict_types=1);

namespace YireoAdditionalUserCommands\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Exception\InvalidArgumentException;
use Shopware\Core\System\User\UserCollection;
use Shopware\Core\System\User\UserEntity;
use YireoAdditionalUserCommands\Exception\CannotDeleteLastUserException;

class UserRepository
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * UserRepository constructor.
     * @param Connection $connection
     */
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
            $user = $this->getUserEntityByData($row);
            $collection->add($user);
        }

        return $collection;
    }

    /**
     * @param string $username
     * @return UserEntity
     */
    public function getByUsername(string $username): UserEntity
    {
        $builder = $this->connection->createQueryBuilder();
        $query = $builder->select('*')
            ->from('user')
            ->where('username = :username')
            ->setParameter('username', $username)
            ->execute();
        $row = $query->fetch();
        return $this->getUserEntityByData($row);
    }

    /**
     * @param string $email
     * @return UserEntity
     */
    public function getByEmail(string $email): UserEntity
    {
        $builder = $this->connection->createQueryBuilder();
        $query = $builder->select('*')
            ->from('user')
            ->where('email = :email')
            ->setParameter('email', $email)
            ->execute();
        $row = $query->fetch();
        return $this->getUserEntityByData($row);
    }

    /**
     * @param string $username
     * @return bool
     * @throws DBALException
     * @throws InvalidArgumentException
     */
    public function deleteByUsername(string $username): bool
    {
        $user = $this->getByUsername($username);
        $this->delete($user);
        return true;
    }

    /**
     * @param string $email
     * @return bool
     * @throws DBALException
     * @throws InvalidArgumentException
     */
    public function deleteByEmail(string $email): bool
    {
        $user = $this->getByEmail($email);
        $this->delete($user);
        return true;
    }

    /**
     * @param UserEntity $user
     * @throws DBALException
     * @throws InvalidArgumentException
     */
    public function delete(UserEntity $user)
    {
        $users = $this->getAll();
        if (count($users) <= 1) {
            throw new CannotDeleteLastUserException('Cannot delete last user in this application');
        }

        $this->connection->delete('user', ['id' => $user->getId()]);
    }

    /**
     * @param array $data
     * @return UserEntity
     */
    private function getUserEntityByData(array $data): UserEntity
    {
        $user = new UserEntity();
        $user->setId($data['id']);
        $user->setUsername((string)$data['username']);
        $user->setFirstName((string)$data['first_name']);
        $user->setLastName((string)$data['last_name']);
        $user->setEmail((string)$data['email']);
        $user->setActive((bool)$data['active']);
        $user->setAdmin((bool)$data['admin']);
        return $user;
    }
}
