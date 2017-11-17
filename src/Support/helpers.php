<?php

if (!function_exists('alias')) {
    /**
     * @param string $alias
     * @param string $table
     * @return string
     */
    function alias(string $alias, string $table): string
    {
        return "`{$alias}`.`{$table}";
    }
}
