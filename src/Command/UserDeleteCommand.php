<?php declare(strict_types=1);

namespace YireoAdditionalUserCommands\Command;

use InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use YireoAdditionalUserCommands\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UserDeleteCommand extends Command
{
    protected static $defaultName = 'user:delete';
    protected static $defaultDescription = 'Delete a specific user';

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * UserListCommand constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
    }

    protected function configure()
    {
        $this->addArgument('email', InputArgument::OPTIONAL, 'Email of user to delete');
        $this->addArgument('username', InputArgument::OPTIONAL, 'Username of user to delete');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = $input->getArgument('email');
        $username = $input->getArgument('username');

        if (empty($email) && empty($username)) {
            throw new InvalidArgumentException('Either enter a username or an email');
        }

        if ($email) {
            $this->userRepository->deleteByEmail($email);
            return 1;
        }

        $this->userRepository->deleteByUsername($username);
        return 1;
    }
}
