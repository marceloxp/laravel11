<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ExportExport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export site to static HTML, CSS, and JS files.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $package_json_filepath = base_path('../package.json');
        if (!File::exists($package_json_filepath)) {
            $initial_json = [
                'exporter' => [
                    'env' => 'homolog',
                    'host' => [
                        'local' => 'http://127.0.0.1:8000',
                        'dest' => 'https://job.umstudio.com.br'
                    ]
                ],
                'statusbartext' => [
                    'active' => true,
                    'text' => '📦 Export: homolog'
                ]
            ];
            File::put($package_json_filepath, json_encode($initial_json, JSON_PRETTY_PRINT));
        }
        $package_json_content = File::get($package_json_filepath);
        $package_json = json_decode($package_json_content, true);
        $hosts_keys = array_keys($package_json['br_com_umstudio']['hosts']);
        $use_host = $this->choice('Qual host deseja utilizar?', $hosts_keys);
        $this->info('Exportando site para ' . $use_host . '...');

        $host_local = $package_json['br_com_umstudio']['hosts']['local'];
        $host_dest = $package_json['br_com_umstudio']['hosts'][$use_host];

        $settings_json_filepath = base_path('../.vscode/settings.json');
        $settings_json_content = File::get($settings_json_filepath);
        $settings_json = json_decode($settings_json_content, true);
        $settings_json['exporter']['env'] = $use_host;
        $settings_json['exporter']['host']['local'] = $host_local;
        $settings_json['exporter']['host']['dest'] = $host_dest;
        $settings_json['statusbartext']['text'] = '📦 Export: ' . $use_host;
        $settings_json_content = json_encode($settings_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        File::put($settings_json_filepath, $settings_json_content);

        // call to php artisan php artisan export
        $this->info('Exportando o site...');
        $this->call('export');

        $this->info('Export concluído com sucesso.');
    }
}
