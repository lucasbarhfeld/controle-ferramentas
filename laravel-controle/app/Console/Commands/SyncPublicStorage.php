<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class SyncPublicStorage extends Command
{
    protected $signature = 'storage:sync-public';

    protected $description = 'Copia uploads antigos para a pasta pública usada na hospedagem';

    public function handle(Filesystem $files): int
    {
        $source = storage_path('app/public');
        $destination = public_path('storage');

        if (! $files->isDirectory($source)) {
            $this->warn("A pasta de origem não existe: {$source}");

            return self::SUCCESS;
        }

        $files->ensureDirectoryExists($destination, 0755, true);

        $copied = 0;
        foreach ($files->allFiles($source) as $file) {
            $relativePath = $file->getRelativePathname();
            $target = $destination.DIRECTORY_SEPARATOR.$relativePath;

            $files->ensureDirectoryExists(dirname($target), 0755, true);

            if (! $files->exists($target) || $files->lastModified($file->getPathname()) > $files->lastModified($target)) {
                $files->copy($file->getPathname(), $target);
                $copied++;
            }
        }

        $this->info("{$copied} arquivo(s) copiado(s) para {$destination}.");

        return self::SUCCESS;
    }
}
