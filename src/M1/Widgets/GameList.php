<?php

namespace M1\Widgets;

use \M1\Classes\Game;
use \M1\Classes\Helper;
use Former\Facades\Former;

class GameList extends \WP_Widget
{

    public $widget = array( 'id'          => 'mmowgli-game-lst',
                            'name'        => 'Game - List of Games',
                            'description' => 'Prints a list of games.',
                            'defaults'    => array( 'widget_title' => 'Create a new card' ),
                          );


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
        if (isset($args['before_widget'])) {
            echo $args['before_widget'];
        }

        if (!empty($instance['widget_title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['widget_title']) . $args['after_title'];
        }

        echo Helper::get_html_list(Game::$post_type);

        if (isset($args['after_widget'])) {
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

        // Create a new array instance
        $instance = array();

        // We create a default value using shorthand ternary operator
        $instance['widget_title'] = (! empty($new_instance['widget_title'])) ? strip_tags($new_instance['widget_title']) : '';

        // Return the array to save it
        return $instance;
    }

    /**
     * Print the admin widget form
     * @param  [type] $instance [description]
     */
    public function form($instance)
    {
        $instance = wp_parse_args((array) $instance, $this->widget['defaults']);

        echo '<p>';
        echo Former::label('Title:')->for($this->get_field_id('widget_title'));
        echo Former::text()->setAttribute('class', 'widefat')
                           ->setAttribute('id', $this->get_field_id('widget_title'))
                           ->setAttribute('name', $this->get_field_name('widget_title'))
                           ->setAttribute('value', strip_tags($instance['widget_title']));
        echo '</p>';
    }
}
