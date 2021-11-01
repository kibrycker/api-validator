<?php

namespace kibrycker\api\validator\controllers;

use yii\helpers\Json;
use GuzzleHttp\Client;
use JsonRpc2\Controller;

/**
 * Контроллер для проверки корректности семантической разметки страницы
 */
class ApiController extends Controller
{
    /**
     * Тестовое действие для проверки работы модуля
     * @return string
     */
    public function actionTest(): string
    {
        return 'Api:Test';
    }

    /**
     * Получение результатов проверки по Урл
     *
     * @return []|array|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function actionUrlParser(): ?array
    {
        $params = array_merge([
            'apikey' => $this->module->apiYandexKey,
        ], $this->getRequestParams());
        $urlQuery = http_build_query($params);
        $url = "{$this->module->apiUrl}/url_parser?{$urlQuery}";
        $client = new Client();
        $response = $client->get($url);
        $body = $response->getBody();
        $data = $body->getContents();
        return Json::decode($data, true);
    }

    /**
     * Получение результатов проверки по HTML-коду
     *
     * @return []|array|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function actionDocumentParser(): ?array
    {
        $queryParams = $this->getRequestParams();
        $body = $queryParams['body'];
        unset($queryParams['body']);
        $params = array_merge([
            'apikey' => $this->module->apiYandexKey,
        ], $queryParams);
        $urlQuery = http_build_query($params);
        $url = "{$this->module->apiUrl}/document_parser?{$urlQuery}";
        $client = new Client();
        $response = $client->post($url, [
           'body' => $body
        ]);
        $body = $response->getBody();
        $data = $body->getContents();
        return Json::decode($data, true);
    }

    /**
     * Получение параметров и их обработка, чтобы вернуть массив
     *
     * @return array
     */
    public function getRequestParams(): array
    {
        $reqParams = $this->requestObject->params;
        if (empty($reqParams)) {
            $reqParams = new \StdClass();
        }
        return $this->objectToArray($reqParams);
    }

    /**
     * Перевод данных из объекта в массив
     *
     * @param object $data Объект \StdClass, который нужно преобразовать в массив
     *
     * @return array
     */
    public function objectToArray(object $data): array
    {
        if (gettype($data) == 'object') {
            $data = Json::encode($data);
        }

        if (gettype($data) == 'string') {
            $data = Json::decode($data, true);
        }

        return $data;
    }
}