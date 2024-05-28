<?php

use App\Models\User;

if (!function_exists('shortenDescription')) {
    /**
     * Shortens a description to a maximum number of words.
     *
     * @param string $description The original description.
     * @param int $maxWords The maximum number of words to include.
     * @return string The shortened description.
     */
    function shortenDescription($description, $maxWords = 5) {
        $words = explode(' ', $description);
        if (count($words) > $maxWords) {
            $shortenedDescription = implode(' ', array_slice($words, 0, $maxWords));
            return $shortenedDescription . '...';
        }
        return $description;
    }
}

if (!function_exists('shortenText')) {
    /**
     * Shortens a text to a maximum number of characters.
     *
     * @param string $text The original text.
     * @param int $maxChars The maximum number of characters to include.
     * @return string The shortened text.
     */
    function shortenText($text, $maxChars = 50) {
        if (strlen($text) > $maxChars) {
            return substr($text, 0, $maxChars) . '...';
        }
        return $text;
    }
}


if (!function_exists('permission_allow')) {
    /**
     * Checks if the role of one user grants higher permissions than the role of another user.
     *
     * @param User $user1 The first user.
     * @param User $user2 The second user.
     * @return bool True if user1 has a higher permission level than user2, otherwise false.
     */
    function permission_allow(User $user1, User $user2): bool {
        $roles = config('roles');
        $user1RolePermission = $roles[$user1->role] ?? PHP_INT_MAX;
        $user2RolePermission = $roles[$user2->role] ?? PHP_INT_MAX;

        if ($user1RolePermission < $user2RolePermission) {
            // Check if user1 is a leader or sub-leader and user2 is a senior-developer or junior-developer
            if (($user1->role === 'leader' || $user1->role === 'sub-leader') && 
                ($user2->role === 'senior-developer' || $user2->role === 'junior-developer')) {
                // Check if user2 is assigned to user1
                return $user2->assigned_to === $user1->id;
            } else {
                // If not in the leader-developer relationship, return true based on role permission
                return true;
            }
        } else {
            // If user1 does not have higher permission, return false
            return false;
        }
    }
}
