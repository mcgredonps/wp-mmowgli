<?php

namespace M1\Classes;

class Points
{

    private $user;

    private static $instance;

  /**
   * Return an instance of this object
   * @return $this An instance of this object
   * @category function
   */
    public static function instance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

  /**
   * Returns true if this is a user object
   * @return boolean true if a user object
   * @category function
   */
    public function is_user()
    {
        return ($this->user instanceof \WP_User);
    }

  /**
   * Set the user object
   * @param WP_User $user The user object
   * @category function
   */
    public function set_user(\WP_User $user = null)
    {
        if (is_null($user)) {
            $this->user = wp_get_current_user();
        } else {
            $this->user = $user;
        }
    }

    /**
     * Add points to a users
     * 
     */
    public function add() {

    }

    /**
     * Award the user points
     * @param  int $points The points to be awarded
     * @return null
     * @category function
     */
    public function set( (int)$points)
    {
    }

    /**
     * Get the users total points from metadata
     * @return int The users points
     * @category function
     */
    public function get() {

    }
}
