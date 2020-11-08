<?php

namespace App\Commands;

use Kutia\Helpers\CommandLine;
use Kutia\Laravel\Setup;
use LaravelZero\Framework\Commands\Command;

class SetupCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'install';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Install your environment';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(){

        $php = $this->task('PHP Version', function(){
            return strnatcmp(phpversion(),'7.2.5') >= 0;
        });

        $composer = $this->task("Composer", function(){
            $composer = shell_exec('composer --version');
            strpos($composer, 'Composer version');
        });

        $make = $this->task('GNU Make', function(){
            $make = shell_exec('make --version');
            return strpos($make, 'GNU Make');
        });

        $docker = $this->task("Docker", function() {
           $docker = shell_exec('docker --version');
           return strpos($docker, 'Docker version');
        });

        if(!$php || !$composer || !$make || !$docker){
            $this->info("This CLI not will work if any of them not installed");
            exit();
        }

        $option = $this->menu("Setup", [
            "Laravel",
            "Wordpress",
            "React",
            "Vue",
            "Angular"
        ])->setForegroundColour('green')
            ->setBackgroundColour('black')
            ->disableDefaultItems()
            ->open();

        if(!is_null($option)) {

            if($option == 0)
            {
                $this->call(Setup::class);
            }
        }
    }

}
