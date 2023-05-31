<?php declare(strict_types=1);

namespace Yireo\AdditionalUserCommands\Command;

use InvalidArgumentException;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Yireo\AdditionalUserCommands\Exception\UserNotFoundException;

class UserDeleteCommand extends Command
{
    protected static $defaultName = 'user:delete';
    protected static $defaultDescription = 'Delete a specific user';

    /**
     * @var EntityRepository
     */
    private $userRepository;

    /**
     * UserListCommand constructor.
     * @param EntityRepository $userRepository
     */
    public function __construct(EntityRepository $userRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
    }

    protected function configure()
    {
        $this->addOption('email', false, InputArgument::OPTIONAL, 'Email of user to delete');
        $this->addOption('username', false, InputArgument::OPTIONAL, 'Username of user to delete');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = $input->getOption('email');
        $username = $input->getOption('username');

        if (empty($email) && empty($username)) {
            throw new InvalidArgumentException('Either enter a username or an email');
        }

        $fieldName = $email ? 'email' : 'username';
        $fieldValue = $email ? $email : $username;

        try {
            $this->deleteUserByField($fieldName, $fieldValue);
            $output->writeln('Deleted user with '.$fieldName.' "'.$fieldValue.'"');
            return 1;
        } catch (UserNotFoundException $e) {
            $output->writeln('<error>'.$e->getMessage().'</error>');
            return 0;
        }
    }

    /**
     * @param string $field
     * @param string $value
     * @throws UserNotFoundException
     */
    public function deleteUserByField(string $field, string $value)
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter($field, $value));
        $context = Context::createDefaultContext();
        $result = $this->userRepository->search($criteria, $context);
        $userIds = $result->getIds();

        if (empty($userIds)) {
            throw new UserNotFoundException('No such user with '.$field.' "'.$value.'"');
        }

        $userId = array_shift($userIds);
        $this->userRepository->delete([['id' => $userId]], $context);
    }
}
