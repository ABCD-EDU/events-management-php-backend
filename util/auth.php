<?php

    $API_KEY = "hotdogs";

    function checkKey($header) {
        $key = "";
        foreach ($header as $headers => $value) {
            if ($headers == "x-api-key") {
                $key = $value;
                break;
            }
        }
        if ($key == "hotdogs") {
            return true;
        }
        return false;
    }