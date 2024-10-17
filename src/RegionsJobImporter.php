<?php

declare(strict_types=1);

class RegionsJobImporter implements JobImporterInterface
{
    public function support(string $fileName): bool
    {
        return $fileName === 'regionsjob.xml';
    }
    public function parse(string $file): array
    {
        $xml = simplexml_load_file($file);

        $items = [];
        foreach ($xml->item as $item) {
            $items[] = $this->match($item);

        }

        return $items;
    }

    public function match(object $data): object
    {
        return (object) [
            'reference' => (string) $data->ref,
            'title' => (string) $data->title,
            'description' => (string) $data->description,
            'url' => (string) $data->url,
            'company_name' => (string) $data->company,
            'publication' => (string) $data->pubDate,
        ];
    }
}
