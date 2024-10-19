<?php

declare (strict_types=1);

namespace Gambol\Commands\SecureCommand;

use Gambol\Commands\Command;
use Gambol\Commands\ExitStatus;
use Gambol\Utils\Configuration\WithConfig;
use Gambol\Utils\SSH\SSHCommand;

#[WithConfig]
final class SecureCommand extends Command {
    protected static string $name = 'secure';
    protected static string $description = 'Secure the server';

    #[\Override]
    protected function execute(): ExitStatus {
        $sshCmd = new SSHCommand("pwd");
        echo $sshCmd->getOut();
        return ExitStatus::SUCCESS;
    }
}