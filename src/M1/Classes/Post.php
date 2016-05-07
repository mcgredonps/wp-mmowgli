<?php

namespace M1\Classes;

class Post
{
    public $post;

    /**
     * Set the post object
     * @param \WP_Post $post The post object
     * @category function
     */
    public function set_post(\WP_Post $post)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Returns true if this is a post object
     * @return boolean true if a post object
     * @category function
     */
    public function is_post()
    {
        return ($this->post instanceof \WP_Post);
    }

    /**
     * Returns true if a post has a parent
     * @return boolean
     */
    public function has_parent()
    {
        return !empty(get_post_ancestors($this->post->ID));
    }
}
