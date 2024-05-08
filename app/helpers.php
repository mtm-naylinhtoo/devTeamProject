<?php

if (!function_exists('shortenDescription')) {
    function shortenDescription($description, $maxWords = 5) {
        $words = explode(' ', $description);
        if (count($words) > $maxWords) {
            $shortenedDescription = implode(' ', array_slice($words, 0, $maxWords));
            return $shortenedDescription . '...';
        }
        return $description;
    }
}
