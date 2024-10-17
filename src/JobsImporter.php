<?php

declare(strict_types=1);

final class JobsImporter
{
    private PDO $db;

    private string $file;

    private array $importers = [];

    public function __construct(string $host, string $username, string $password, string $databaseName, string $file, array $importers = [])
    {
        $this->file = $file;
        $this->importers = $importers;
        
        /* connect to DB */
        try {
            $this->db = new PDO('mysql:host=' . $host . ';dbname=' . $databaseName, $username, $password);
        } catch (Exception $e) {
            die('DB error: ' . $e->getMessage() . "\n");
        }
    }

    public function importJobs(): int
    {
        /* remove existing items */
        $this->db->exec('DELETE FROM job');

        $count = 0;
        foreach (glob($this->file . '*') as $file) {
            foreach ($this->importers as $importer) {
                if (!$importer->support(basename($file))) {
                    continue;
                }

                $items = $importer->parse($file);
                foreach ($items as $item) {
                    $this->db->exec('INSERT INTO job (reference, title, description, url, company_name, publication) VALUES ('
                        . '\'' . addslashes((string) $item->reference) . '\', '
                        . '\'' . addslashes((string) $item->title) . '\', '
                        . '\'' . addslashes((string) $item->description) . '\', '
                        . '\'' . addslashes((string) $item->url) . '\', '
                        . '\'' . addslashes((string) $item->company_name) . '\', '
                        . '\'' . addslashes((string) $item->publication) . '\')'
                    );

                    $count++;
                }
            }
        }

        return $count;
    }
}
