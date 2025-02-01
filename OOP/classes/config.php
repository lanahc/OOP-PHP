<?php
class Config{
    public static function get($path = null) {
        if($path) {
            $config = $GLOBALS['config'];
        }
    }
}