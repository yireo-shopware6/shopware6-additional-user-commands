<?php declare(strict_types=1);

namespace YireoAdditionalUserCommands\Command;

use Shopware\Core\System\User\UserEntity;
use Symfony\Component\Console\Helper\Table;
use YireoAdditionalUserCommands\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UserListCommand extends Command
{
    protected static $defaultName = 'user:list';
    protected static $defaultDescription = 'Show a listing of all current users';

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

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $headers = ['Username', 'First name', 'Last name', 'Email', 'Active', 'Admin'];
        $rows = [];

        foreach ($this->userRepository->getAll() as $user) {
            /** @var UserEntity $user */
            $rows[] = [
                $user->getUsername(),
                $user->getFirstname(),
                $user->getLastname(),
                $user->getEmail(),
                $user->getActive(),
                $user->isAdmin()
            ];
        }

        $table = new Table($output);
        $table
            ->setHeaders($headers)
            ->setRows($rows);
        $table->render();

        return 0;
    }
}
