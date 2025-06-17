<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ExportAfter extends Command
{
    protected $signature = 'export:after';
    protected $description = 'Trata URLs em arquivos .html: <meta> usa host destino, assets viram relativos. Ajusta datasite JSON.';

    public function handle()
    {
        $htmlFiles = collect(File::allFiles(base_path('dist')))
            ->filter(fn($f) => $f->getExtension() === 'html');

        $settingsPath = base_path('../.vscode/settings.json');
        if (!File::exists($settingsPath)) {
            $this->error("Arquivo settings.json não encontrado: {$settingsPath}");
            return Command::FAILURE;
        }

        $settings = json_decode(File::get($settingsPath), true);
        $hostLocal = rtrim($settings['exporter']['host']['local'] ?? '', '/');
        $hostDest = rtrim($settings['exporter']['host']['dest'] ?? '', '/');
        $envDest = $settings['exporter']['env'] ?? 'production';

        if (!$hostLocal || !$hostDest) {
            $this->error("Host local ou destino não definidos corretamente.");
            return Command::FAILURE;
        }

        $this->info("Local: $hostLocal");
        $this->info("Destino: $hostDest");
        $this->info("Env destino: $envDest");
        $this->info("Arquivos HTML encontrados: " . $htmlFiles->count());

        foreach ($htmlFiles as $file) {
            $path = $file->getRealPath();
            $this->info("Processando: $path");

            $lines = explode("\n", File::get($path));
            $inHead = false;

            foreach ($lines as $i => &$line) {
                if (stripos($line, '<head') !== false) $inHead = true;
                if (stripos($line, '</head>') !== false) $inHead = false;

                if (strpos($line, $hostLocal) === false) continue;

                if ($inHead) {
                    if (stripos($line, '<link') !== false || stripos($line, '<script') !== false) {
                        $line = $this->removeHostLocal($line, $hostLocal);
                    } else {
                        $line = str_replace($hostLocal, $hostDest, $line);
                    }
                } else {
                    $line = $this->removeHostLocal($line, $hostLocal);
                }
            }

            // ✅ Segunda etapa: Ajuste do datasite.json
            $lines = $this->ajustarDatasiteJson($lines, $envDest);

            File::put($path, implode("\n", $lines));
        }

        $this->info("Finalizado com sucesso!");
        return Command::SUCCESS;
    }

    /**
     * Remove o host local da linha, substituindo por caminho relativo
     */
    private function removeHostLocal(string $line, string $hostLocal): string
    {
        $line = str_replace($hostLocal . '/', '/', $line);
        $line = str_replace($hostLocal, '', $line);
        return $line;
    }

    /**
     * Trata o JSON do bloco window.datasite
     */
    private function ajustarDatasiteJson(array $lines, string $envDest): array
    {
        foreach ($lines as $i => $line) {
            if (strpos($line, '"datasite":true') !== false) {
                // Tenta decodificar
                $json = json_decode($line, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    unset($json['csrf_token']);
                    $json['env'] = $envDest;
                    $lines[$i] = json_encode($json, JSON_UNESCAPED_SLASHES);
                } else {
                    $this->warn("⚠️ JSON inválido detectado em datasite, linha $i");
                }
                break;
            }
        }
        return $lines;
    }
}
