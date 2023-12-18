<?php namespace Nord\Lumen\Elasticsearch\Parsers;

class AbstractStringParser
{
    /**
     * @var non-empty-string
     */
    private $separator = ';';

    /**
     * @var non-empty-string
     */
    private $delimiter = ':';


    /**
     * Configuration constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->configure($config);
    }


    /**
     * @param string $string
     *
     * @return array
     */
    public function parse(string $string)
    {
        $array = [];

        foreach ($this->splitItems($string) as $item) {
            $array[] = $this->splitItem($item);
        }

        return $array;
    }


    /**
     * @param array $config
     */
    protected function configure(array $config): void
    {
        if (isset($config['separator'])) {
            $this->separator = $config['separator'];
        }

        if (isset($config['delimiter'])) {
            $this->delimiter = $config['delimiter'];
        }
    }


    /**
     * @param string $string
     *
     * @return array
     */
    protected function splitItems(string $string)
    {
        return strpos($string, $this->separator) !== false ? explode($this->separator, $string) : [$string];
    }


    /**
     * @param string $string
     *
     * @return array
     */
    protected function splitItem(string $string)
    {
        return explode($this->delimiter, $string);
    }
}
