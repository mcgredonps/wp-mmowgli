<?php

namespace M1\Classes;

use \Hashids\Hashids;

class Helper
{

    /**
     * Accesses the global post object to determine if we are on a game page
     * @return boolean Returns true if we are on a game page
     * @category function
     */
    public static function is_game_page()
    {
        if (is_single()) {
            global $post;

            if (Card::instance()->set_post($post)->is_card_post_type()) {
                return true;
            }

            if (Game::instance()->set_post($post)->is_game_post_type()) {
                return true;
            }
        }

        return false;
    }


    /**
     * Get the card type square as html
     * @param  string $card_config_id The card configuration id
     * @return string                 HTML
     * @category function
     */
    public static function get_card_type_square($card_config_id = '', $title = '')
    {
        $card_type_column_key = Config::$card_type_column_key;

        return "<i class='fa fa-square fa-game-{$card_type_column_key} {$card_config_id} inverse' title='{$title}'></i>";
    }

    /**
     * Generate a hash id as a short link
     * @param  int    $post_id The post id
     * @return string          The encoded post id.
     * @category function
     */
    public static function generate_hash_id($post_id)
    {
        $hash_ids = new \Hashids\Hashids();

        return $hash_ids->encode($post_id);
    }

    /**
     * Get the html cards list
     * @param  string $post_type The post type
     * @param  array  $args      Misc args
     * @return string            HTML
     * @category function
     */
    public static function get_html_list($post_type, $args = array())
    {
        $list = wp_list_pages(array_merge(array(
            'child_of'    => null,
            'depth'       => 0,
            'echo'        => false,
            'post_type'   => $post_type,
            'sort_column' => 'post_parent, post_date',
            'title_li'    => '',
        ), $args));

        if (empty($list)) {
            return $list;
        } else {
            return '<ul>' . $list . '</ul>';
        }
    }

    /**
     * Get the child cards of a card
     * @param  int    $post_id The post id
     * @return array           An array of posts
     * @category function
     */
    public static function get_child_cards($post_id)
    {
        return get_children(array(
            'post_parent' => $post_id,
            'numberposts' => -1,
            'post_status' => 'publish'
        ), OBJECT);
    }

    /**
     * Get the game description
     * @param  int    $post_id The post id
     * @return string          The game description
     * @category function
     */
    public static function get_game_description($post_id)
    {
        return get_post_meta($post_id, Config::$plugin_prefix . '_game_description', true);
    }

    /**
     * Get the post type from admin area new / edit post page
     * @return string
     * @category function
     */
    public static function get_post_type_admin_params()
    {
        $post_type = '';

        // If we are editing an existing card
        if (isset($_GET[ 'post' ])) {
            $post = get_post($_GET[ 'post' ]);

            $post_type = $post->post_type;

        // If we are creating a new card
        } elseif (isset($_GET[ 'post_type' ])) {
            $post_type = $_GET[ 'post_type' ];
        }

        return $post_type;
    }
}
