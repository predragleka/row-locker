<?php
declare(strict_types = 1);

namespace RowLocker;

/**
 * Describes an entity that can be exclusively locked for changes
 * by a user.
 */
interface LockableInterface
{

    /**
     * Mark the entity as locked by a user identified with a session
     *
     * @param string $by The user identifier
     * @param string $session The session identifier
     *
     * @return void
     * @throw \LockingException if the row is already locked
     */
    function lock($by = null, string $session = null) : void;

    /**
     * Unlocks the entity
     *
     * @return void
     */
    public function unlock(): void;

    /**
     * Returns true if the entity is locked
     *
     * @return bool
     */
    public function isLocked(): bool;

    /**
     * Returns the user that locked the entity if any
     *
     * @return mixed|null
     */
    public function lockOwner();

    /**
     * Returns the session that locked the entity if any
     *
     * @return string|null
     */
    public function lockSession(): ?string;

    /**
     * Sets the amount of seconds the lock can be valid
     *
     * @param int $seconds AMount of seconds
     * @return void
     */
    public static function setLockTimeout($seconds): void;

    /**
     * Returs the amout of seconds a lock is valid for
     *
     * @return int
     */
    public static function getLockTimeout(): int;
}
