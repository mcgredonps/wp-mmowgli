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
                            'defaults'    => array( 'widget_title' => 'All Games', 'sort_by' => 'post_title', 'sort_order' => 'asc' ),
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

        echo Helper::get_html_list(Game::$post_type, array( 'sort_column' => $instance['sort_by'], 'sort_order' => $instance['sort_order']));

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

        // We create a default value using shorthand ternary operator
        $instance['sort_by'] = (! empty($new_instance['sort_by'])) ? strip_tags($new_instance['sort_by']) : '';

        // We create a default value using shorthand ternary operator
        $instance['sort_order'] = (! empty($new_instance['sort_order'])) ? strip_tags($new_instance['sort_order']) : '';

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
        echo Former::label('Widget Title:')->for($this->get_field_id('widget_title'));
        echo Former::text()->setAttribute('class', 'widefat')
                           ->setAttribute('id', $this->get_field_id('widget_title'))
                           ->setAttribute('name', $this->get_field_name('widget_title'))
                           ->setAttribute('value', $instance['widget_title']);
        echo '</p>';

        echo '<p>';
        echo Former::label('Sort by:')->for($this->get_field_id('sort_by'));
        echo Former::select()->setAttribute('class', 'widefat')
                           ->setAttribute('id', $this->get_field_id('sort_by'))
                           ->setAttribute('name', $this->get_field_name('sort_by'))
                           ->options(array('post_date' => 'Game Created (Date)', 'post_title' => 'Game Title'))
                           ->select($instance['sort_by']);
        echo '</p>';

        echo '<p>';
        echo Former::label('Sort order:')->for($this->get_field_id('sort_order'));
        echo Former::select()->setAttribute('class', 'widefat')
                           ->setAttribute('id', $this->get_field_id('sort_order'))
                           ->setAttribute('name', $this->get_field_name('sort_order'))
                           ->options(array('asc' => 'Ascending (ASC)', 'desc' => 'Descending (DESC)'))
                           ->select($instance['sort_order']);
        echo '</p>';
    }
}
