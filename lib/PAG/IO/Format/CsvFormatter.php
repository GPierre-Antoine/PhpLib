<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 28/08/18
 * Time: 14:07
 */

namespace PAG\IO\Format;


class CsvFormatter implements ArrayFormatter
{

    private $delimiter;
    private $enclosure;
    private $escape_char;

    public function __construct($delimiter = ',', $enclosure = '"', $escape_char = '\\')
    {
        $this->delimiter   = $delimiter;
        $this->enclosure   = $enclosure;
        $this->escape_char = $escape_char;
    }

    public function generateOutputFromArray(array $array): string
    {
        $handle = fopen("php://memory", 'w+');
        foreach ($array as $item) {
            fputcsv($handle, $item, $this->delimiter, $this->delimiter, $this->escape_char);
        }
        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return $content;

    }
}