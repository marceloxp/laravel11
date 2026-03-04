<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class IncVersion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'incversion
                            {part=patch : Part to increment: major, minor or patch (default: patch)}
                            {--dry-run : Show the new version without saving}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Increment the application version in config/app.php';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $configPath = config_path('app.php');

        // Read current version
        $currentVersion = config('app.version');

        if (! $currentVersion) {
            $this->error('Could not read the current version from config/app.php.');
            $this->line('Make sure the file has a "version" key.');
            return self::FAILURE;
        }

        // Validate semver format
        if (! preg_match('/^\d+\.\d+\.\d+$/', $currentVersion)) {
            $this->error("Version \"{$currentVersion}\" is not in valid semver format (MAJOR.MINOR.PATCH).");
            return self::FAILURE;
        }

        [$major, $minor, $patch] = array_map('intval', explode('.', $currentVersion));

        $part = strtolower($this->argument('part'));

        switch ($part) {
            case 'major':
                $major++;
                $minor = 0;
                $patch = 0;
                break;

            case 'minor':
                $minor++;
                $patch = 0;
                break;

            case 'patch':
                $patch++;
                break;

            default:
                $this->error("Invalid part \"{$part}\". Use: major, minor or patch.");
                return self::FAILURE;
        }

        $newVersion = "{$major}.{$minor}.{$patch}";

        $this->line("Current version : <fg=yellow>{$currentVersion}</>");
        $this->line("New version     : <fg=green>{$newVersion}</>");
        $this->line("Part incremented: <fg=cyan>{$part}</>");

        if ($this->option('dry-run')) {
            $this->newLine();
            $this->warn('Dry-run mode: no changes were saved.');
            return self::SUCCESS;
        }

        // Read file content and replace the version string
        $content = file_get_contents($configPath);

        $updatedContent = preg_replace(
            "/('version'\s*=>\s*')[^']+(')/",
            "\${1}{$newVersion}\${2}",
            $content,
            1,
            $count
        );

        if ($count !== 1) {
            $this->error('Could not find the version string or has multiple occurrences in config/app.php to replace. Occurrences found: ' . $count);
            return self::FAILURE;
        }

        file_put_contents($configPath, $updatedContent);

        $this->newLine();
        $this->info("Version updated successfully to {$newVersion} in config/app.php.");

        return self::SUCCESS;
    }
}
