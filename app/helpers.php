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

        return $user1RolePermission < $user2RolePermission;
    }
}
