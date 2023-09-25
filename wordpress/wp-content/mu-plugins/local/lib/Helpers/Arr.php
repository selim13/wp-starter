<?php

namespace Local\Helpers;

class Arr
{
    /**
     * Tests whether at least one element in the array
     * passes the test implemented by the provided function.
     *
     * @param array $array
     * @param callable $callback
     * @return bool
     */
    public static function any(array $array, callable $callback): bool
    {
        foreach ($array as $value) {
            if (call_user_func($callback, $value) === true) {
                return true;
            }
        }
        return false;
    }

    /**
     * Returns the value of the first element in the array
     * that satisfies the provided testing function.
     *
     * @param array $array
     * @param callable $callback
     * @return mixed|null
     */
    public static function find(array $array, callable $callback): mixed
    {
        foreach ($array as $value) {
            if (call_user_func($callback, $value) === true) {
                return $value;
            }
        }
        return null;
    }

    /**
     * Returns the index of the first element in the array
     * that satisfies the provided testing function.
     *
     * @param array $array
     * @param callable $callback
     * @return int|string|null
     */
    public static function find_index(array $array, callable $callback): int|string|null
    {
        foreach ($array as $key => $value) {
            if (call_user_func($callback, $value) === true) {
                return $key;
            }
        }
        return null;
    }
}