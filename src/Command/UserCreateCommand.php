<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsCommand(
    name: 'app:user:create',
    description: 'Create user from this command.',
)]
class UserCreateCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ValidatorInterface $validator
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $fullName = $io->ask('Full Name');
        $email = $io->ask('Email');
        $password = $io->askHidden('Password');
        $object = (new User())->setFullName($fullName)->setEmail($email)->setPlainPassword($password);
        if ($this->validator->validate($object)->count() > 0) {
            foreach ($this->validator->validate($object) as $value) {
                $io->error($value->getMessage());

                return self::FAILURE;
            }
        }
        $this->entityManager->persist($object);
        $this->entityManager->flush();
        $io->success("User(#{$object->getId()}) created!");

        return Command::SUCCESS;
    }
}
