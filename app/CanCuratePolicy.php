<?php
namespace CityHapps;

class CanCuratePolicy {

    public static function can($user, $resource) {
        return (new static($user))->canCurate($resource);
    }

    public function __construct($user) {
        $this->user = $user;
    }

    public function canCurate($resource) {
        if (!$this->user || !$this->user->isAdmin() && !$this->user->isCurator()) return false;
        if ($this->user->isAdmin()) return true;

        // Must be a curator
        return !$resource || // There is no resource
            !$resource->user_id || // Resource is unowned
            $resource->user_id === $this->user->id; // User is owner
    }
}