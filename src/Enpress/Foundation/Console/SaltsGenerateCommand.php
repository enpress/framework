<?php

namespace Enpress\Foundation\Console;

use Illuminate\Console\Command;
use Illuminate\Encryption\Encrypter;
use Illuminate\Console\ConfirmableTrait;

class SaltsGenerateCommand extends Command
{
    use ConfirmableTrait;

    protected $saltKeys = [
        'auth_key',
        'secure_auth_key',
        'logged_in_key',
        'nonce_key',
        'auth_salt',
        'secure_auth_salt',
        'logged_in_salt',
        'nonce_salt'
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'salts:generate
                    {--show : Display the salts instead of modifying files}
                    {--force : Force the operation to run when in production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the Wordpress salts';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {

        $generated = [];
        foreach ($this->saltKeys as $key) {
            $generated[$key] = $this->generateRandomSalt();
        }

        if ($this->option('show')) {

            foreach ($generated as $key => $value) {
                $this->line("<comment>'{$key}' => '{$value}'</comment>");
            }
            return;
        }

        // Perform replacement
        if (! $this->setSaltsInConfigFile($generated)) {
            return;
        }

        foreach ($generated as $key => $value) {
            $this->laravel['config']['cms.'.$key] = $value;
        }


        $this->info("Wordpress salts set successfully.");
    }

    /**
     * Generate a random salt.
     *
     * @return string
     */
    protected function generateRandomSalt()
    {
        return base64_encode(
            Encrypter::generateKey($this->laravel['config']['app.cipher'])
        );
    }

    /**
     * Set the salts in the configuration file.
     *
     * @param  array  $generated
     * @return bool
     */
    protected function setSaltsInConfigFile($generated)
    {

        if (! $this->confirmToProceed()) {
            return false;
        }

        foreach ($generated as $key => $value) {
            $this->writeNewConfigFileWith($key, $value);
        }

        return true;
    }

    /**
     * Write a new configuration file with the given key.
     *
     * @param  string  $config
     * @param  string  $key
     * @return void
     */
    protected function writeNewConfigFileWith($config, $key)
    {
        file_put_contents($this->laravel->configPath('cms.php'), preg_replace(
            $this->saltReplacementPattern($config),
            "'{$config}' => '{$key}',",
            file_get_contents($this->laravel->configPath('cms.php'))
        ));
    }

    /**
     * Get a regex pattern that will match the configuration file current settings
     *
     * @param string $config
     * @return string
     */
    protected function saltReplacementPattern($config)
    {
        $escaped = preg_quote($this->laravel['config']['cms.'.$config], '/');

        return "/'{$config}' \=\> '{$escaped}',/m";
    }
}
