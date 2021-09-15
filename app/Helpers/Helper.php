<?php

namespace App\Helpers;

/**
 * Предоставляет вспомогательный функционал.
 */
class Helper {

    #region Constants

    const JSON_UNESCAPED_SLASHES = 64;
    const JSON_PRETTY_PRINT      = 128;
    const JSON_UNESCAPED_UNICODE = 256;

    #endregion

    /**
     * Возвращает представление значения в формате JSON.
     *
     * @param  mixed $data  Кодируемое значение.
     * @param  int   $flags Битовая маска, состоящая из <b>JSON_PRETTY_PRINT</b>, <b>JSON_UNESCAPED_SLASHES</b>, <b>JSON_UNESCAPED_UNICODE</b>.<br>
     *                      Поведение этих констант описано на странице <a href="https://www.php.net/manual/en/json.constants.php">Константы JSON</a>.
     * @return false|string
     * @link   https://php.net/manual/en/function.json-encode.php
     */
    public static function json_encode($data, $flags = 448) {
        return (version_compare(PHP_VERSION, '5.4', '>='))
            ? json_encode($data, $flags)
            : self::json_format(json_encode($data), $flags);
    }

    /**
     * Возвращает представление значения в формате JSON.
     *
     * @param  mixed $json  Строка формата JSON.
     * @param  int   $flags Битовая маска, состоящая из <b>JSON_PRETTY_PRINT</b>, <b>JSON_UNESCAPED_SLASHES</b>, <b>JSON_UNESCAPED_UNICODE</b>.<br>
     *                      Поведение этих констант описано на странице <a href="https://www.php.net/manual/en/json.constants.php">Константы JSON</a>.
     * @return false|string
     * @link   https://php.net/manual/en/function.json-encode.php
     */
    private static function json_format($json, $flags) {
        $prettyPrint     = (bool)($flags & self::JSON_PRETTY_PRINT);
        $unescapeUnicode = (bool)($flags & self::JSON_UNESCAPED_UNICODE);
        $unescapeSlashes = (bool)($flags & self::JSON_UNESCAPED_SLASHES);

        if (!$prettyPrint && !$unescapeUnicode && !$unescapeSlashes)
            return $json;

        $result = '';
        $pos = 0;
        $strLen = strlen($json);
        $indentStr = '    ';
        $newLine = "\n";
        $outOfQuotes = true;
        $buffer = '';
        $noescape = true;

        for ($i = 0; $i < $strLen; $i++) {

            // Grab the next character in the string
            $char = substr($json, $i, 1);

            // Are we inside a quoted string?
            if ('"' === $char && $noescape)
                $outOfQuotes = !$outOfQuotes;

            if (!$outOfQuotes) {
                $buffer .= $char;
                $noescape = '\\' === $char ? !$noescape : true;
                continue;
            }
            elseif ('' !== $buffer) {
                if ($unescapeSlashes)
                    $buffer = str_replace('\\/', '/', $buffer);

                if ($unescapeUnicode && function_exists('mb_convert_encoding')) {
                    // http://stackoverflow.com/questions/2934563/how-to-decode-unicode-escape-sequences-like-u00ed-to-proper-utf-8-encoded-cha
                    $buffer = preg_replace_callback(
                        '/\\\\u([0-9a-f]{4})/i',
                        function ($match) {
                            return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
                        },
                        $buffer);
                }

                $result .= $buffer . $char;
                $buffer  = '';
                continue;
            }
            elseif(false !== strpos(" \t\r\n", $char)) {
                continue;
            }

            if (':' === $char) {

                // Add a space after the : character
                $char .= ' ';
            }
            elseif (('}' === $char || ']' === $char)) {
                $pos--;
                $prevChar = substr($json, $i - 1, 1);

                if ('{' !== $prevChar && '[' !== $prevChar) {
                    // If this character is the end of an element,
                    // output a new line and indent the next line
                    $result .= $newLine;
                    for ($j = 0; $j < $pos; $j++)
                        $result .= $indentStr;
                }
                else {
                    // Collapse empty {} and []
                    $result = rtrim($result) . "\n\n" . $indentStr;
                }
            }

            $result .= $char;

            // If the last character was the beginning of an element,
            // output a new line and indent the next line
            if (',' === $char || '{' === $char || '[' === $char) {
                $result .= $newLine;

                if ('{' === $char || '[' === $char) {
                    $pos++;
                }

                for ($j = 0; $j < $pos; $j++) {
                    $result .= $indentStr;
                }
            }
        }
        // If buffer not empty after formating we have an unclosed quote
        if (strlen($buffer) > 0) {

            //json is incorrectly formatted
            $result = false;
        }

        return $result;
    }
}
