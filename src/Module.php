<?php

namespace kibrycker\api\validator;

use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\base\InvalidConfigException;
use yii\base\InvalidArgumentException;

/**
 * Модуль для проверки корректности семантической разметки страницы
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    /** @var string Урл запроса для получения расписания */
    const URL_REQUEST = 'https://validator-api.semweb.yandex.ru';

    /** @var string Версия запроса для получения расписания */
    const VERSION_URL_REQUEST = 'v1.1';

    /** @var string API Yandex-ключ, полученный в кабинете Разработчика */
    public string $apiYandexKey;

    /** @var string Собранный урл для получения расписания */
    public string $apiUrl;

    /**
     * Загрузчик модуля
     *
     * @param Application $app Текущее приложение
     * @throws InvalidConfigException
     */
    public function bootstrap($app): void
    {
        $this->apiUrl = self::URL_REQUEST . '/' . self::VERSION_URL_REQUEST;
    }

    /**
     * Логирование сообщений отладки
     * @param string $type Тип сообщения: debug, info, warning, error
     * @param string $message Текст сообщения отладки
     * @param string|null $category Категория сообщения отладки. Если =null, то берется по имени модуля ($this->id)
     */
    public static function log(string $type, string $message, string $category = null): void
    {
        if (!in_array($type, ['debug', 'info', 'warning', 'error'])) {
            throw new InvalidArgumentException('Invalid log message type');
        }
        Yii::$type($message, $category ?? Module::getInstance()->id);
    }

    /**
     * Получение экземпляра модуля
     * @return Module|null
     */
    public static function getInstance(): ?Module
    {
        $module = parent::getInstance();
        if ($module === null) {
            throw new \RuntimeException('Failed to instantiate `' . static::class . '` object');
        }
        return $module;
    }
}