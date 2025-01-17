<?php
/**
 * File CommandLine.php.
 * @copyright 2020
 * @version 1.0
 */

namespace Kutia\Helpers;


use Symfony\Component\Process\Process;
use function Kutia\user;

class CommandLine
{
    /**
     * Simple global function to run commands.
     *
     * @param  string  $command
     * @return void
     */
    function quietly($command)
    {
        $this->runCommand($command.' > /dev/null 2>&1');
    }

    /**
     * Simple global function to run commands.
     *
     * @param  string  $command
     * @return void
     */
    function quietlyAsUser($command)
    {
        $this->quietly('sudo -u "'.user().'" '.$command.' > /dev/null 2>&1');
    }

    /**
     * Pass the command to the command line and display the output.
     *
     * @param  string  $command
     * @return void
     */
    function passthru($command)
    {
        passthru($command);
    }

    /**
     * Run the given command as the non-root user.
     *
     * @param  string  $command
     * @param  callable $onError
     * @return string
     */
    function run($command, callable $onError = null)
    {
        return $this->runCommand($command, $onError);
    }

    /**
     * Run the given command.
     *
     * @param  string  $command
     * @param  callable $onError
     * @return string
     */
    function runAsUser($command, callable $onError = null)
    {
        return $this->runCommand('sudo -u "'.user().'" '.$command, $onError);
    }

    /**
     * Run the given command.
     *
     * @param  string  $command
     * @param  callable $onError
     * @return string
     */
    function runCommand($command, callable $onError = null)
    {
        $onError = $onError ?: function () {};

        // Symfony's 4.x Process component has deprecated passing a command string
        // to the constructor, but older versions (which Laraserve's Composer
        // constraints allow) don't have the fromShellCommandLine method.
        if (method_exists(Process::class, 'fromShellCommandline')) {
            $process = Process::fromShellCommandline($command);
        } else {
            $process = new Process($command);
        }

        $processOutput = '';
        $process->setTimeout(null)->run(function ($type, $line) use (&$processOutput) {
            $processOutput .= $line;
        });

        if ($process->getExitCode() > 0) {
            $onError($process->getExitCode(), $processOutput);
        }

        return $processOutput;
    }
}
