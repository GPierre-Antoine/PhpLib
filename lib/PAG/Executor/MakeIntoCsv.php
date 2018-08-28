<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 28/08/18
 * Time: 14:10
 */

namespace PAG\Executor;


class MakeIntoCsv implements Executable
{

    private $in  = STDIN;
    private $out = STDOUT;

    private $buffer = 100000;

    private $delimiter;
    private $enclosure;
    private $escape_char;
    private $ending = "\n";

    public function __construct(string $delimiter = ",", string $enclosure = '"', string $escape_char = '\\',
        int $buffer = 100000)
    {
        $this->buffer      = $buffer;
        $this->delimiter   = $delimiter;
        $this->enclosure   = $enclosure;
        $this->escape_char = $escape_char;
    }


    public function run()
    {
        while ($string = stream_get_line($this->in, $this->buffer, $this->ending)) {
            fputcsv($this->out, preg_split('/\s+/', $string), $this->delimiter, $this->enclosure, $this->escape_char);
        }
    }
}
