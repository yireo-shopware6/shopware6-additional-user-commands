<?php declare(strict_types=1);

namespace Yireo\AdditionalUserCommands\Command;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\System\User\UserEntity;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UserListCommand extends Command
{
    protected static $defaultName = 'user:list';
    protected static $defaultDescription = 'Show a listing of all current users';

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

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $headers = ['Username', 'First name', 'Last name', 'Email', 'Active', 'Admin'];
        $rows = [];

        $criteria = new Criteria();
        $context = Context::createDefaultContext();
        foreach ($this->userRepository->search($criteria, $context) as $user) {
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
