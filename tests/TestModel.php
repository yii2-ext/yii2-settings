<?php

declare(strict_types=1);

namespace dicr\tests;

use proweb\\settings\models\AbstractSettingsModel;

/**
 * Тестовая модель
 */
class TestModel extends AbstractSettingsModel
{
    /** @var array<string, mixed> набор тестовых данных модели */
    public const array TEST_DATA = [
        'null' => null,
        'boolean' => false,
        'zero' => 0,
        'float' => -1.23,
        'string' => "Иванов Иван\nИванович",
        'array' => [
            1, 2, 'a' => 'b',
        ],
    ];

    public mixed $null = null;

    public ?bool $boolean = null;

    public ?int $zero = null;

    public ?float $float = null;

    public ?string $string = null;

    /** @var array<mixed>|null */
    public ?array $array = null;

    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            [['null', 'boolean', 'zero', 'float', 'string', 'array'], 'safe'],
        ];
    }
}
