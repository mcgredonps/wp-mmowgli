<?php

namespace M1\Widgets;

use Former\Facades\Former;
use M1\Classes\Helper;

class ReplyCurrentCardButton extends \WP_Widget
{

    public $widget = array( 'id'          => 'mmowgli-reply-current-card-button',
                            'name'        => 'Game - Reply to Current Card Button',
                            'description' => 'Prints a button that enables a user to reply to the current card from the front end.',
                            'defaults'    => array( 'button_text' => 'Reply to this card!' ),
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
        if (!Helper::is_mmowgli_page()) {
            return;
        }

        if (isset($args['before_widget'])) {
            echo $args['before_widget'];
        }

        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }

        $button_text = $instance['button_text'];

        do_action('print_reply_card_button', $button_text);

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
        $instance['button_text'] = (! empty($new_instance['button_text'])) ? strip_tags($new_instance['button_text']) : '';

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
        echo $this->widget['description'];
        echo '</p>';

        echo '<p>';
        echo Former::label('Button Title:')->for($this->get_field_id('button_text'));
        echo Former::text()->setAttribute('class', 'widefat')
                           ->setAttribute('id', $this->get_field_id('button_text'))
                           ->setAttribute('name', $this->get_field_name('button_text'))
                           ->setAttribute('value', strip_tags($instance['button_text']));
        echo '</p>';
    }
}
