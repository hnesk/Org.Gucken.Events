<?php
namespace Util\Lookup\Strategy;

use \Util\Lookup\Strategy;

class CountWordMatches implements Strategy {

    protected $cachedPreparedWords = array();

    /**
     *
     * @param string $value
     * @param array $data
     * @return int
     */
    public function lookup($value, $data) {

        $index = $this->prepareWords($data);

        $results = array();
        
        foreach ($this->stringToTokens($value) as $token) {
            if (isset($index[$token])) {
                $votes = $index[$token];
                
                foreach($votes as $item) {
                    $results[$item] = isset($results[$item]) ? $results[$item] + 1 : 1;
                }
            }
        }
        if (!count($results)) {
            return false;
        }
        arsort($results, SORT_NUMERIC);

        $choice = key($results);
        $data = \array_flip($data);
        
        if (!isset($data[$choice]) || empty($data[$choice])) {
            $similarity = 0.0;
        } else {
            $foundValue = $data[$choice];
            $similarity =
                    (max(\strlen($value) , \strlen($foundValue)) - \levenshtein($value,$foundValue)) /
                    min(\strlen($value), \strlen($foundValue));

            #echo $similarity.PHP_EOL;
        }

        return array($choice,$similarity);
    }

    protected function prepareWords($data) {
        if (empty($this->cachedPreparedWords)) {
            $this->cachedPreparedWords = array();
            foreach ($data as $string => $key) {                
                foreach ($this->stringToTokens($string) as $token) {
                    if (!isset($this->cachedPreparedWords[$token])) {
                        $this->cachedPreparedWords[$token] = array($key);
                    } else {
                        $this->cachedPreparedWords[$token][] = $key;
                    }
                }
            }
        }
        return $this->cachedPreparedWords;
    }

    /**
     *
     * @param string $string
     * @return array
     */
    protected function stringToTokens($string) {
        return array_filter(preg_split('#[^\wÄÖÜäöüß]#', mb_strtolower(trim($string),'UTF-8')));
    }



}