<?php

namespace M1\Classes;

use \utilphp\util;

class Game extends Post
{
    public static $post_type = 'game';

    public static $game_data = null;

    public static $configurations_mappings = null;

    public static $post_type_args = array(
      'label'               => 'Game',
      'description'         => 'Game Description',
      'supports'            => array('title'),
      'taxonomies'          => array(),
      'hierarchical'        => false,
      'public'              => true,
      'show_ui'             => true,
      'show_in_menu'        => true,
      'menu_position'       => 2,
      'menu_icon'           => 'dashicons-schedule',
      'show_in_admin_bar'   => true,
      'show_in_nav_menus'   => true,
      'can_export'          => true,
      'has_archive'         => true,
      'exclude_from_search' => false,
      'publicly_queryable'  => true,
      'capability_type'     => 'page',
    );

    public static $post_type_labels = array(
        'name'                  => 'Games',
        'singular_name'         => 'Game',
        'menu_name'             => 'Games',
        'name_admin_bar'        => 'Game',
        'archives'              => 'Game Archives',
        'parent_item_colon'     => 'Parent Game:',
        'all_items'             => 'All Games',
        'add_new_item'          => 'Add New Game',
        'add_new'               => 'Add New Game',
        'new_item'              => 'New Game',
        'edit_item'             => 'Edit Game',
        'update_item'           => 'Update Game',
        'view_item'             => 'View Game',
        'search_items'          => 'Search Games',
        'not_found'             => 'Not found',
        'not_found_in_trash'    => 'Not found in Trash',
        'featured_image'        => 'Featured Image',
        'set_featured_image'    => 'Set featured image',
        'remove_featured_image' => 'Remove featured image',
        'use_featured_image'    => 'Use as featured image',
        'insert_into_item'      => 'Insert into game',
        'uploaded_to_this_item' => 'Uploaded to this game',
        'items_list'            => 'Games list',
        'items_list_navigation' => 'Games list navigation',
        'filter_items_list'     => 'Filter games list',
    );

    public static $card_configuration_fields = array(
        array(
              'name'          => 'ID',
              'id'            => 'id',
              'type'          => 'text',
              'description'   => 'Automatically generated based on save. If you delete this card, all card responses will be deleted!',
              'attributes'    => array('readonly' => 'readonly'),
            ),
        array(
              'name'        => 'Background Color',
              'id'          => 'background-color',
              'type'        => 'colorpicker',
              'description' => 'Pick the background color for your card.',
            ),
        array(
              'name'        => 'Text Color',
              'id'          => 'text-color',
              'type'        => 'colorpicker',
              'description' => 'Pick the text color for your card.',
            ),
        array(
              'name'        => 'Points',
              'id'          => 'points',
              'type'        => 'text',
              'default'     => 0,
              'description' => 'Set the number of points a person should earn for responding to this card type.',
            ),
        array(
              'name'        => 'Title',
              'id'          => 'title',
              'type'        => 'text',
              'description' => 'We recommend a simple word to describe your card.',
            ),
        array(
              'name'        => 'Description',
              'description' => 'Write a short description to help others understand this card.',
              'id'          => 'description',
              'type'        => 'textarea_small',
            ),
      );

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
     * Return true if the post is a game post type
     * @return boolean
     * @category function
     */
    public function is_game_post_type()
    {
        return ($this->post->post_type == Game::$post_type);
    }

    /**
     * Generate card type ids for a post id
     * @return null
     * @category function
     */
    public function generate_card_type_ids()
    {
        $game_card_configurations = get_post_meta($this->post->ID, Config::$plugin_prefix.'_game_card_configuration', true);

        if (!empty($game_card_configurations) && is_array($game_card_configurations)) {
            foreach ($game_card_configurations as $key => $game_card_configuration) {
                if (empty($game_card_configurations[ $key ][ 'id' ])) {
                    $game_card_configurations[ $key ][ 'id' ] = self::generate_card_type_id($this->post->ID);
                }
            }
            update_post_meta($this->post->ID, Config::$plugin_prefix . '_game_card_configuration', $game_card_configurations);
        }
    }

    /**
     * Generate a random card type id
     * @param  int $post_id The post id
     * @return string
     * @category function
     */
    public static function generate_card_type_id($post_id)
    {
        return "card-{$post_id}-" . uniqid();
    }

    /**
     * Get game data (type, title, card configuration)
     * @return array The game data
     * @category function
     */
    public static function get_data()
    {
        if (!self::$game_data) {
            $games = get_posts(array(
                'post_type'      => Game::$post_type,
                'post_status'    => 'publish',
                'posts_per_page' => 250,
            ));

            if (is_array($games) && !empty($games)) {
                foreach ($games as $post) {
                    $game_data = new \stdClass();

                    $game_data->post_id = $post->ID;

                    $game_data->post_type = Game::$post_type . '-' . $post->ID;

                    $game_data->post_title = $post->post_title;

                    $game_data->card_configurations = self::get_card_configurations($post->ID);

                    self::$game_data[ Game::$post_type . '-' . $post->ID ] = $game_data;
                }
            } else {
                self::$game_data = array( );
            }
        }

        return self::$game_data;
    }

    /**
     * The game configurations mappings
     * @return array The configuration mappings
     * @category function
     */
    public static function get_card_configurations_mappings()
    {
        if (!self::$configurations_mappings) {
            foreach (Game::get_data() as $post_type => $data) {
                if (!is_array($data->card_configurations)) {
                    continue;
                }
                foreach ($data->card_configurations as $config) {
                    self::$configurations_mappings[ $config['id'] ] = $config;
                }
            }
        }

        return self::$configurations_mappings;
    }

    /**
     * Get the card configurations by post type (used to populate lists)
     * @param    string   $post_type The post type
     * @param    array    $configs
     * @return   array    $configs
     * @category function
     */
    public static function get_card_configurations_by_post_type($post_type, $configs = array())
    {
        // Get the game data
        $game_data = Game::get_data();

        // If the card configurations are set
        if (isset($game_data[$post_type]->card_configurations)) {

            // For each card configuration
            foreach ((array) $game_data[$post_type]->card_configurations as $config) {

                // If the id is not set
                if (empty($config[ 'id' ])) {

                    // Continue
                    continue;
                }

                // Build the configs array
                $configs[ $config[ 'id' ] ] = (!empty($config[ 'title' ]) ? $config[ 'title' ] : 'No Title') . (!empty($config[ 'description' ]) ? ' - ' . $config[ 'description' ] : 'No Description');
            }
        }

        // Return the configs
        return $configs;
    }

    /**
     * Get the configuration from meta data
     * @param  int $post_id The post id
     * @return int          The post id
     * @category function
     */
    public static function get_card_configurations($post_id)
    {
        return get_post_meta($post_id, Config::$plugin_prefix . '_game_card_configuration', true);
    }

    /**
     * Get the post types
     * @return    array Post types
     * @category  function
     */
    public static function get_post_types()
    {
        return util::array_pluck(Game::get_data(), 'post_type');
    }

    /**
     * Register the game post type
     * @return null
     */
    public static function register_post_type()
    {
        self::$post_type_args = array_merge(self::$post_type_args, array('labels' => self::$post_type_labels));

        register_post_type(self::$post_type, self::$post_type_args);
    }

    /**
     * Register the post type meta boxes
     * @return null
     */
    public static function register_meta_boxes()
    {
        $game_details_group = new_cmb2_box(array(
          'id'           => Config::$plugin_prefix.'_game_details',
          'title'        => 'Game Details',
          'object_types' => array(self::$post_type),
        ));

        $game_details_group->add_field(array(
          'name'        => 'Description',
          'description' => 'Write a short description to help others understand the game.',
          'id'          => Config::$plugin_prefix.'_game_description',
          'type'        => 'textarea_small',
        ));

        $card_configuration_group = new_cmb2_box(array(
          'id'           => Config::$plugin_prefix.'_game_card_configuration',
          'title'        => 'Card Configuration',
          'object_types' => array(self::$post_type),
        ));

        $card_configuration_id = $card_configuration_group->add_field(array(
              'id'          => Config::$plugin_prefix.'_game_card_configuration',
              'type'        => 'group',
              'description' => 'We recommend you create four cards. You can sort the cards and this will change the order on the front end.',
              'options'     => array(
                  'group_title'   => 'Card {#}',
                  'add_button'    => 'Add Card',
                  'remove_button' => 'Remove Card',
                  'sortable'      => true,
                  'closed'        => true,
              ),
        ));

        foreach (self::$card_configuration_fields as $card_configuration_field) {
            $card_configuration_group->add_group_field($card_configuration_id, $card_configuration_field);
        }
    }
}
