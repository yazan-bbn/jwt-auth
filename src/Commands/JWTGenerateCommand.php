<?php

namespace Tymon\JWTAuth\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class JWTGenerateCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'jwt:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the JWT Auth secret key used to sign the tokens';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $key = $this->getRandomKey();

        if ($this->option('show')) {
            return $this->line('<comment>'.$key.'</comment>');
        }

        $path = base_path('.env');

        if (file_exists($path)) {
            file_put_contents($path, str_replace(
                $this->laravel['config']['jwt.secret'], $key, file_get_contents($path)
            ));
        }

        $this->laravel['config']['jwt.secret'] = $key;

        $this->info("jwt-auth secret [$key] set successfully.");
    }

    /**
     * Generate a random key for the JWT Auth secret.
     *
     * @return string
     */
    protected function getRandomKey()
    {
        return Str::random(32);
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['show', null, InputOption::VALUE_NONE, 'Simply display the key instead of modifying files.'],
        ];
    }
}