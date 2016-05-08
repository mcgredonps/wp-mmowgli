<?php

namespace M1\Widgets;

class NewCard extends \WP_Widget
{

    public $widget = array( 'id' => 'mmowgli-new-card-button', 'name' => 'Game - New Card Button', 'description' => 'Prints a button that enables a user to create a new game card from the front end.');

    /**
     * Instantiate the parent object
     */
    public function __construct()
    {
        parent::__construct($this->widget['id'], $this->widget['name'], array('description' => $this->widget['description']));
    }

    /**
     * Print the widget contents on the front-end
     * @param  array $args      [description]
     * @param  array $instance  [description]
     * @return null             [description]
     */
    public function widget($args, $instance)
    {
        if (array_key_exists('before_widget', $args)) {
            echo $args['before_widget'];
        }

        echo "HI";

        if (array_key_exists('after_widget', $args)) {
            echo $args['after_widget'];
        }
    }

    /**
     * Save the widget options
     * @param  [type] $new_instance [description]
     * @param  [type] $old_instance [description]
     * @return [type]               [description]
     */
    public function update($new_instance, $old_instance)
    {
        return $instance;
    }

    /**
     * Print the widget form
     * @param  [type] $instance [description]
     */
    public function form($instance)
    {
        // Output admin widget options form
    }
}
