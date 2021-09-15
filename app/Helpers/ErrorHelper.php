<?php

namespace App\Helpers;

use ErrorException;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

/**
 *
 */
class ErrorHelper {

    /**
     * Инициализирует новый экземпляр для обработки вывода ошибок.
     */
    private function __constructor() {
        $this->operation = null;
        $this->message   = null;
        $this->exception = null;
    }

    #region Properties

    private $operation;
    private $message;
    private $exception;

    #endregion

    #region Helpers

    /**
     * Форматирует ошибку и преобразует в объект <b>JSON</b>.
     *
     * @param string $operation Проводимая операция.
     * @param string $message Текст ошибки.
     * @param Exception $e Экземпляр исключения.
     * @return false|string
     * @throws ErrorException
     */
    private static function getFormattedMessage($operation, $message, $e) {
        if (empty($operation) || empty($e))
            throw new ErrorException('Входящие параметры заданы неверно.', 500);

        return Helper::json_encode([
            'operation'   => $operation,
            'message'     => $message,
            'error'       => [
                'code'    => $e->getCode(),
                'message' => str_replace(PHP_EOL, ' ', $e->getMessage()),
                'file'    => $e->getFile(),
                'line'    => $e->getLine()
            ]
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_FORCE_OBJECT);
    }

    #endregion

    /**
     * Возвращает экземпляр для вывода ошибки.
     *
     * @return $this
     */
    public static function throw() {
        return new ErrorHelper();
    }

    /**
     * Задаёт входящие параметры.
     *
     * @param  string $operation Проводимая операция.
     * @param  string $message Текст ошибки.
     * @param  Exception $e Экземпляр исключения.
     * @return $this
     * @throws ErrorException
     */
    public function data($operation, $message, $e) {
        if (empty($operation) || empty($e))
            throw new ErrorException('Входящие параметры заданы неверно.', 500);

        $this->operation = $operation;
        $this->message   = $message;
        $this->exception = $e;

        return $this;
    }

    /**
     * Выполняет запись в журнал.
     *
     * @param  string $channel
     * @return $this
     * @throws ErrorException
     */
    public function log($channel = '') {
        $channel = (!empty($channel))
            ? $channel
            : config('logging.default', 'daily');

        Log::channel($channel)->error(self::getFormattedMessage($this->operation, $this->message, $this->exception));
        Log::channel($channel)->error(str_repeat('-', 100));

        return $this;
    }

    /**
     * Возвращает ответ в формате <b>JSON</b>.
     *
     * @return JsonResponse
     * @throws ErrorException
     */
    public function response() {
        return response()->json(self::getFormattedMessage($this->operation, $this->message, $this->exception), 500);
    }

    /**
     * Выводит на экран.
     *
     * @return void
     * @throws ErrorException
     */
    public function dd() {
        $error_string = self::getFormattedMessage($this->operation, $this->message, $this->exception);
        $error_array  = json_decode($error_string, true);

        dd($error_array);
    }

    /**
     * Выдаёт указанное исключение.
     *
     * @return void
     */
    public function exception() {
        throw $this->exception;
    }
}
