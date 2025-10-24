<?php
namespace App\Command;

use App\Entity\TracktikToken;
use App\Service\TokenEncryptor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'tracktik:set-token')]
class SetTracktikTokenCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private TokenEncryptor $encryptor
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Stores or updates the TrackTik refresh token.')
            ->addArgument('refresh_token', InputArgument::REQUIRED, 'TrackTik refresh token');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $refresh = $input->getArgument('refresh_token');
        $repo = $this->em->getRepository(TracktikToken::class);
        $token = $repo->findOneBy([]) ?? new TracktikToken();

        $token->setRefreshToken($this->encryptor->encrypt($refresh));
        $this->em->persist($token);
        $this->em->flush();

        $output->writeln('<info>TrackTik refresh token stored successfully.</info>');
        return Command::SUCCESS;
    }
}
