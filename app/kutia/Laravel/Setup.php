<?php

namespace Kutia\Laravel;

use Illuminate\Filesystem\Filesystem;

class Setup extends LaravelCommand
{

    protected $packs = [];

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'laravel-install';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Install your laravel environment';


    public function handle()
    {
        # Selected all packages
        $selected_packages = $this->packagist->install();


        $this->task('Creating docker files', function(){

            $filesystem = new Filesystem();
            $dockerComposeStub = base_path()."/app/kutia/Docker/stubs/docker-compose.stub";
            $stub = $filesystem->get($dockerComposeStub);

            $content = str_replace(['${DEV_DOMAIN}'], ['kutia.net'], $stub);
            $dir = getcwd()."/src";
            $file = $dir."/docker-compose.yml";

            if (!$filesystem->isDirectory($dir)) {
                $filesystem->makeDirectory($dir, 0755, true);
            }

            return $filesystem->put($file, $content);
        });

        $this->task('Docker builded', function(){
            sleep(2);
            return true;
        });

        $this->task('installing laravel', function(){
            sleep(2);
            return true;
        });

        $this->task('installing packages', function() use ($selected_packages){
//            sleep(2);
            $this->newLine();
            foreach ($selected_packages as $selected_package) {
                $this->info("installing {$selected_package} ...");
            }

            return true;
        });

        $this->info("Laravel has been installed");
        $this->notify("Successfully has been installed", "Your laravel architecture has been structured");
    }


}
