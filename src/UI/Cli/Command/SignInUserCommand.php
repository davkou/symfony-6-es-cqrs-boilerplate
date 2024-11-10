<?php

namespace UI\Cli\Command;

use App\Shared\Application\Command\CommandBusInterface;
use App\User\Application\Command\SignIn\SignInCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:sign-in-user',
    description: 'Given proper credentials allows a user to sign in.',
)]
class SignInUserCommand extends Command
{
    public function __construct(private readonly CommandBusInterface $commandBus)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
	        ->addArgument('email', InputArgument::REQUIRED, 'User email')
	        ->addArgument('password', InputArgument::REQUIRED, 'User password')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('email');
	    $arg2 = $input->getArgument('password');

        if ($arg1) {
            $io->note(sprintf('You passed an email as argument: %s', $arg1));
        }

	    if ($arg2) {
		    $io->note(sprintf('You passed an password as argument: %s', $arg2));
	    }

		$this->commandBus->handle(new SignInCommand($arg1, $arg2));

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
