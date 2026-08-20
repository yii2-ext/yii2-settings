<?php

declare(strict_types=1);

namespace dicr\tests;

use PHPUnit\Framework\TestCase;
use yii\base\Exception;

/**
 * Базовый класс для всех тестов
 */
abstract class AbstractTestCase extends TestCase
{
    /** @var string файл тестовых данных */
    protected static string $filename = __DIR__ . '/test.dat';

    /**
     * Удаляет файлы данных
     */
    protected static function deleteFiles(): void
    {
        /** @noinspection PhpUsageOfSilenceOperatorInspection */
        @unlink(self::$filename);
    }

    /**
     * {@inheritDoc}
     */
    public static function setUpBeforeClass(): void
    {
        static::deleteFiles();
    }

    /**
     * {@inheritDoc}
     */
    public static function tearDownAfterClass(): void
    {
        static::deleteFiles();
    }

    /**
     * Тест модели
     *
     * @throws Exception
     */
    public function testModel(): void
    {
        $testModel = TestModel::instance(true);
        self::assertNull($testModel->float);

        self::assertSame($testModel, TestModel::instance());

        $testModel->setAttributes(TestModel::TEST_DATA);
        self::assertSame(TestModel::TEST_DATA, $testModel->attributes);

        self::assertTrue($testModel->save());

        $testModel2 = TestModel::instance(true);
        self::assertNotEquals($testModel, $testModel2);

        self::assertSame(TestModel::TEST_DATA, $testModel2->attributes);
    }
}
