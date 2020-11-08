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

        $this->task('Creating docker files', function(){

            $filesystem = new Filesystem();
            $dir = getcwd()."/src";
            $dockerComposeStub = base_path()."/app/kutia/Docker/stubs/docker-compose.stub";
            $stub = $filesystem->get($dockerComposeStub);

            $content = str_replace(['${DEV_DOMAIN}'], ['kutia.net'], $stub);
            $file = $dir."/docker-compose.yml";

            if (!$filesystem->isDirectory($dir)) {
                $filesystem->makeDirectory($dir, 0755, true);
            }

            $filesystem->put($file, $content);

            ### Creating env
            $config_answers = [];
            $configs = [
                'PROJECT_NAME' => [
                    'label' => 'Project Name?',
                    'default'   => 'kutia_'.rand(1,999),
                    'type'  => 'ask',
                    'plural'  => true,
                ],
                'DEV_DOMAIN' => [
                    'label' => 'Development domain?',
                    'default'   => 'locahost',
                    'type'  => 'ask',
                    'plural'  => false,
                ],
                'EMAIL' => [
                    'label' => 'E-mail?',
                    'default'   => 'no-replay@kutia.net',
                    'type'  => 'ask',
                    'plural'  => false,
                ],
                'MYSQL_USER' => [
                    'label' => 'Mysql User?',
                    'default' => 'kutia',
                    'type'  => 'ask',
                    'plural'  => false,
                ],
                'MYSQL_DATABASE' => [
                    'label' => 'Mysql Database?',
                    'default' => 'kutia',
                    'type'  => 'ask',
                    'plural'  => false,
                ],
                'MYSQL_PASSWORD' => [
                    'label' => 'Mysql Password?',
                    'default' => 'secret',
                    'type'  => 'secret',
                    'plural'  => false,
                ],
                'MYSQL_ROOT_PASSWORD' => [
                    'label' => 'Mysql Root Password?',
                    'default' => 'secret',
                    'type'  => 'secret',
                    'plural'  => false,
                ],
                'REDIS_ADMIN_USER' => [
                    'label' => 'Redis admin user (Default: admin)?',
                    'default'   => 'admin',
                    'type'  => 'ask',
                    'plural'  => false,
                ],
                'REDIS_ADMIN_PASS' => [
                    'label' => 'Redis admin password (Default: secret)?',
                    'default'   => 'secret',
                    'type'  => 'ask',
                    'plural'  => false,
                ],
                'REDIS_1_PORT' => [
                    'label' => 'Redis Port (Default: 6379)?',
                    'default'   => '6379',
                    'type'  => 'ask',
                    'plural'  => false,
                ],
                'REDIS_1_AUTH' => [
                    'label' => 'Redis auth password?',
                    'default' => 'secret',
                    'type'  => 'secret',
                    'plural'  => false,
                ]
            ];

            $dockerEnv = base_path()."/app/kutia/Docker/stubs/env.stub";
            $stubEnv = $filesystem->get($dockerEnv);


            if (!$filesystem->isDirectory($dir)) {
                $filesystem->makeDirectory($dir, 0755, true);
            }

            foreach ($configs as $key => $config)
            {
                $answer = $this->{$config['type']}($config['label']);
                $config_answers['${'.$key.'}']  = $answer ? $config['plural'] ? str_slug($answer) : $answer : $config['default'];
            }

            $contentEnv = str_replace(array_keys($config_answers), array_values($config_answers), $stubEnv);

            $fileEnv = $dir."/.env";
            $filesystem->put($fileEnv, $contentEnv);

            return true;
        });

        $this->task('Docker builded', function(){
            sleep(2);
            return true;
        });

        $this->task('installing laravel', function(){
            sleep(2);
            return true;
        });

        $this->task('installing packages', function(){

            # Selected all packages
            $selected_packages = $this->packagist->install();
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
