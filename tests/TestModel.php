<?php
/*
 * @copyright 2019-2022 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license GPL-3.0-or-later
 * @version 05.01.22 03:25:56
 */

declare(strict_types = 1);
namespace dicr\tests;

use dicr\settings\AbstractSettingsModel;

/**
 * Тестовая модель
 */
class TestModel extends AbstractSettingsModel
{
    /** @var array набор тестовых данных модели */
    public const TEST_DATA = [
        'null' => null,
        'boolean' => false,
        'zero' => 0,
        'float' => -1.23,
        'string' => "Иванов Иван\nИванович",
        'array' => [
            1, 2, 'a' => 'b'
        ]
    ];

    public mixed $null = null;

    public ?bool $boolean = null;

    public ?int $zero = null;

    public ?float $float = null;

    public ?string $string = null;

    public ?array $array = null;

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            [['null', 'boolean', 'zero', 'float', 'string', 'array'], 'safe']
        ];
    }
}
