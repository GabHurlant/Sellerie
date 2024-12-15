<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:add-user',
    description: 'Ajoute un utilisateur à la base de données'
)]
class AddUserCommand extends Command
{
    private $entityManager;
    private $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    protected function configure()
    {
        $this
            ->addArgument('mail', InputArgument::OPTIONAL, 'L\'email de l\'utilisateur')
            ->addArgument('password', InputArgument::OPTIONAL, 'Le mot de passe de l\'utilisateur')
            ->addArgument('role', InputArgument::OPTIONAL, 'role de l\'utilisateur');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');

        $blue = "\033[34m";
        $reset = "\033[0m";

        $mail = $input->getArgument('mail') ?? $helper->ask($input, $output, new Question($blue . 'Veuillez entrer l\'email de l\'utilisateur : ' . $reset));
        $output->writeln('Email: ' . $mail);

        $password = $input->getArgument('password') ?? $helper->ask($input, $output, (new Question($blue . 'Veuillez entrer le mot de passe de l\'utilisateur : ' . $reset))->setHidden(true));
        $output->writeln('Password: [hidden]');

        $role = $input->getArgument('role') ?? $helper->ask($input, $output, new Question($blue . 'Voulez-vous créer un administrateur ? y/n : ' . $reset));
        $output->writeln('Role: ' . $role);

        $roles = [];
        if (strtolower($role) === 'y') {
            $roles[] = 'ROLE_ADMIN';
        } else {
            $roles[] = 'ROLE_USER';
        }
        $output->writeln('Roles: ' . implode(', ', $roles));

        $user = new User();
        $user->setEmail($mail);
        $user->setPassword($this->passwordHasher->hashPassword($user, $password));
        $user->setRoles($roles);

        $output->writeln('Persisting user...');
        $this->entityManager->persist($user);
        $output->writeln('Flushing to database...');
        $this->entityManager->flush();

        $output->writeln('Utilisateur ajouté avec succès !');

        return Command::SUCCESS;
    }
}