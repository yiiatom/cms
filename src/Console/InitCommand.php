<?php

declare(strict_types=1);

namespace Atom\Console;

use Atom\Entity\User;
use Atom\Entity\UserRole;
use Atom\Entity\UserStatus;
use Atom\Repository\UserRepository;
use Atom\Security\PasswordHasherInterface;
use DomainException;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand('cms:init', 'Initializes the CMS System and Creates the Root Super Admin.')]
final class InitCommand extends Command
{
    public function __construct(
        private PasswordHasherInterface $passwordHasher,
        private UserRepository $userRepository,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setHelp('This command runs the initial CMS setup by creating the root Super Admin account.')
            ->addOption('username', 'u', InputOption::VALUE_OPTIONAL, 'The username of the Super Admin')
            ->addOption('password', 'p', InputOption::VALUE_OPTIONAL, 'The password of the Super Admin');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('CMS Atom Initialization: Create Super Admin Account');

        if ($this->userRepository->superAdminExist()) {
            $io->error('Initialization failed: Root Super Admin already exists in the system.');
            return Command::FAILURE;
        }

        $username = $input->getOption('username');
        if (empty($username)) {
            $question = new Question('Enter Super Admin username: ');
            $question->setValidator(function ($answer) {
                if (empty($answer) || strlen($answer) < 3) {
                    throw new RuntimeException('Username must be at least 3 characters long.');
                }
                return $answer;
            });
            $username = $io->askQuestion($question);
        }

        $password = $input->getOption('password');
        if (empty($password)) {
            $question = new Question('Enter Super Admin password: ');
            $question->setHidden(true);
            $question->setHiddenFallback(false);
            $question->setValidator(function ($answer) {
                if (empty($answer)) {
                    throw new RuntimeException('Password cannot be empty.');
                }
                return $answer;
            });
            $password = $io->askQuestion($question);
        }

        try {
            if ($this->userRepository->superAdminExist()) {
                throw new DomainException('Root Super Admin already exists.');
            }

            $superAdmin = User::create(
                username: $username,
                isSuperAdmin: true,
                status: UserStatus::ACTIVE,
                role: UserRole::ADMIN,
            );
            $superAdmin->changePassword($password, $this->passwordHasher);

            $this->userRepository->save($superAdmin);

            $io->success(sprintf('CMS successfully initialized! Super Admin "%s" created (UUID: %s).',
                $username,
                $superAdmin->getUuid(),
            ));
            return Command::SUCCESS;

        } catch (DomainException $e) {
            $io->error('Domain Logic Error: ' . $e->getMessage());
            return Command::FAILURE;
        } catch (\Throwable $e) {
            $io->error('Infrastructure Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}

