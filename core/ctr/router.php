<?php

/**
 * Router Module
 *
 * Author Jerry Shaw <jerry-shaw@live.com>
 * Author 秋水之冰 <27206617@qq.com>
 *
 * Copyright 2017 Jerry Shaw
 * Copyright 2017 秋水之冰
 *
 * This file is part of NervSys.
 *
 * NervSys is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * NervSys is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with NervSys. If not, see <http://www.gnu.org/licenses/>.
 */

namespace core\ctr;

use core\ctr\router\cgi, core\ctr\router\cli;

class router
{
    //Argument cmd
    public static $cmd = '';

    //Argument data
    public static $data = [];

    //Result data
    public static $result = [];

    //Data Structure
    public static $struct = [];

    //Argument hash
    private static $argv_hash = '';

    /**
     * Router start
     */
    public static function start(): void
    {
        'cli' !== PHP_SAPI ? cgi::run() : cli::run();

        if (2 > DEBUG) return;

        //Debug with Runtime Values
        debug('duration', round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 4) . 'ms');
        debug('memory', round(memory_get_usage(true) / 1048576, 4) . 'MB');
        debug('peak', round(memory_get_peak_usage(true) / 1048576, 4) . 'MB');
    }

    /**
     * Build data structure
     */
    protected static function build_struct(): void
    {
        $struct = array_keys(self::$data);
        $hash = hash('sha256', implode('|', $struct));
        if (self::$argv_hash === $hash) return;
        self::$struct = &$struct;
        self::$argv_hash = &$hash;
        unset($struct, $hash);
    }

    /**
     * Output result
     */
    public static function output(): void
    {
        if (empty(self::$result)) exit;

        $result = 1 === count(self::$result) ? json_encode(current(self::$result)) : json_encode(self::$result);
        'cli' !== PHP_SAPI ? print $result : fwrite(STDOUT, $result . PHP_EOL);

        unset($result);
        exit;
    }
}