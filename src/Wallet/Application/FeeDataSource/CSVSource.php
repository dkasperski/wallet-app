<?php

declare(strict_types=1);

namespace App\Wallet\Application\FeeDataSource;

use App\Wallet\Application\FeeDataSource\Exception\FileNotFoundException;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(bind: ['$projectDir' => '%kernel.project_dir%'])]
class CSVSource implements FeeDataSourceInterface
{
    private const PATH_TO_CSV_FILE = '/data/operations.csv';

    private string $projectDir;

    private array $keys = [
        'operation_date',
        'user_id',
        'user_type',
        'operation_type',
        'operation_amount',
        'operation_currency',
    ];

    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
    }

    /**
     * @throws FileNotFoundException
     */
    public function read()
    {
        $handle = fopen($this->projectDir . self::PATH_TO_CSV_FILE, "rb");

        if ($handle === false) {
            throw new FileNotFoundException();
        }

        while (($data = fgetcsv($handle)) !== false) {
            yield array_combine($this->keys, $data);
        }
        fclose($handle);
    }
}
