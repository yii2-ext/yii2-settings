<?php
/*
 * @copyright 2019-2022 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license GPL-3.0-or-later
 * @version 05.01.22 03:26:56
 */

declare(strict_types = 1);
namespace dicr\tests;

use PHPUnit\Framework\TestCase;
use yii\base\Exception;

/**
 * Базовый класс для всех тестов
 */
abstract class AbstractTestCase extends TestCase
{
    /** @var string файл тестоа */
    protected static string $filename = __DIR__ . '/test.dat';

    /**
     * Удаляет файлы данных
     */
    protected static function deleteFiles() : void
    {
        /** @noinspection PhpUsageOfSilenceOperatorInspection */
        @unlink(self::$filename);
    }

    /**
     * @inheritDoc
     */
    public static function setUpBeforeClass() : void
    {
        static::deleteFiles();
    }

    /**
     * @inheritDoc
     */
    public static function tearDownAfterClass() : void
    {
        static::deleteFiles();
    }

    /**
     * Тест модели
     *
     * @throws Exception
     */
    public function testModel() : void
    {
        // создаем новую модель
        $testModel = TestModel::instance(true);
        self::assertNull($testModel->float);

        // проверка singleton экземпляра
        self::assertSame($testModel, TestModel::instance());

        // загружаем в модель данные
        $testModel->setAttributes(TestModel::TEST_DATA);
        self::assertSame(TestModel::TEST_DATA, $testModel->attributes);

        // сохраняем модель
        self::assertTrue($testModel->save());

        // пересоздаем модель
        $testModel2 = TestModel::instance(true);
        self::assertNotEquals($testModel, $testModel2);

        // проверяем загруженные данные
        self::assertSame(TestModel::TEST_DATA, $testModel2->attributes);
    }
}
