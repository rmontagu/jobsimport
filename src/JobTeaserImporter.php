<?php

declare(strict_types=1);

class JobTeaserImporter implements JobImporterInterface
{
    public function support(string $fileName): bool
    {
        return $fileName === 'jobteaser.json';
    }
    public function parse(string $file): array
    {
        $json = json_decode(file_get_contents($file));

        $items = [];
        foreach ($json->offers as $item) {
            $items[] = $this->match($item);

        }

        return $items;
    }

    public function match(object $data): object
    {
        return (object) [
            'reference' => (string) $data->reference,
            'title' => (string) $data->title,
            'description' => (string) $data->description,
            'url' => (string) $data->urlPath,
            'company_name' => (string) $data->companyname,
            'publication' => (new \DateTime($data->publishedDate))->format('Y/m/d'),
        ];
    }
}
