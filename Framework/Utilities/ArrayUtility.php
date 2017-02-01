<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 19.01.2017
 * Time: 15:01
 */

namespace PM\Bundle\ToolBundle\Framework\Utilities;

/**
 * Class ArrayUtility
 *
 * @package PM\Bundle\ToolBundle\Framework\Utilities
 */
class ArrayUtility
{
    /**
     * Does given array has array with elements for key?
     *
     * @param array  $array
     * @param string $key
     *
     * @return bool
     */
    public static function isKeyWithElements($array, $key)
    {
        if (false === is_array($array) || false === isset($array[$key])) {
            return false;
        }

        if (false === is_array($array[$key])) {
            return false;
        }

        if (0 === count($array[$key])) {
            return false;
        }

        return true;
    }

    /**
     * Get Multidimensional-Array as string
     *
     * @param mixed  $input
     * @param string $glue
     *
     * @return string
     */
    public static function getFlat($input, $glue = ',')
    {
        if (false === is_array($input)) {
            return $input;
        }

        $result = [];

        foreach ($input as $inputKey => $inputText) {
            if (true === is_numeric($inputKey)) {
                $result[] = self::getFlat($inputText, $glue);
            }

            $result[] = sprintf('%s: %s', $inputKey, self::getFlat($inputText, $glue));
        }

        return implode($glue, $result);
    }

}