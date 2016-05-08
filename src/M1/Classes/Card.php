<?php

namespace M1\Classes;

class Card extends Post
{

    // Placeholder - Card post types are dynamically generated
    public static $post_type = 'card';

    public static $post_type_args = array(
        'public'       => true,
        'hierarchical' => true,
        'supports'     => array(
            'title',
            'author',
            'comments',
            'page-attributes'
        ),
        'menu_icon' => 'dashicons-schedule'
    );

    public static $post_type_labels = array(
        'singular_name'         => 'Card',
        'archives'              => 'Card Archives',
        'parent_item_colon'     => 'Parent Card:',
        'add_new_item'          => 'Add New Card',
        'add_new'               => 'Add New Card',
        'new_item'              => 'New Card',
        'edit_item'             => 'Edit Card',
        'update_item'           => 'Update Card',
        'view_item'             => 'View Card',
        'search_items'          => 'Search Cards',
        'not_found'             => 'Not found',
        'not_found_in_trash'    => 'Not found in Trash',
        'featured_image'        => 'Featured Image',
        'set_featured_image'    => 'Set featured image',
        'remove_featured_image' => 'Remove featured image',
        'use_featured_image'    => 'Use as featured image',
        'insert_into_item'      => 'Insert into card',
        'uploaded_to_this_item' => 'Uploaded to this card',
        'items_list'            => 'Cards list',
        'items_list_navigation' => 'Cards list navigation',
        'filter_items_list'     => 'Filter cards list',
    );


    public static $post_tag = 'card_tag';

    public static $post_tag_labels = array(
            'name'                       => 'Card Tags',
            'singular_name'              => 'Card Tag',
            'menu_name'                  => 'Card Tag',
            'all_items'                  => 'All Items',
            'parent_item'                => 'Parent Tag',
            'parent_item_colon'          => 'Parent Tag:',
            'new_item_name'              => 'New Tag',
            'add_new_item'               => 'Add New Tag',
            'edit_item'                  => 'Edit Tag',
            'update_item'                => 'Update Tag',
            'view_item'                  => 'View Tag',
            'separate_items_with_commas' => 'Separate tags with commas',
            'add_or_remove_items'        => 'Add or remove tags',
            'choose_from_most_used'      => 'Choose from the most used tags',
            'popular_items'              => 'Popular tags',
            'search_items'               => 'Search tags',
            'not_found'                  => 'Not tags found',
            'no_terms'                   => 'No tags',
            'items_list'                 => 'Tags list',
            'items_list_navigation'      => 'Tags list navigation',
        );

    public static $post_tag_args = array(
            'hierarchical'               => false,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => false,
            'show_tagcloud'              => true,
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
     * If this is a card post type
     * @param  string | null  $post_type The post type (optional)
     * @return boolean                   Return true if this is a card post type
     * @category function
     */
    public function is_card_post_type($post_type = null)
    {
        if (is_null($post_type)) {
            if (!$this->is_post()) {
                return false;
            }
            $post_type = $this->post->post_type;
        }

        $parts = explode('-', $post_type);

        if (count($parts) == 2) {
            if ($parts[0] == Game::$post_type && is_numeric($parts[1])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Return the parent of a card
     * @return null | \WP_Post Return a post on success, else null
     * @category function
     */
    public function get_parent()
    {
        if (!$this->is_post()) {
            return null;
        }

        $parents = get_post_ancestors($this->post->ID);

        if (isset($parents[ 0 ])) {
            return get_post($parents[ 0 ]);
        }

        return null;
    }

    /**
     * Get the card type of a post
     * @param  int $post_id     The post id
     * @return string           The card type
     */
    public static function get_card_type($post_id)
    {
        return get_post_meta($post_id, Config::$plugin_prefix . '_card_type', true);
    }

    /**
     * Registers the card post tags
     * @return null
     * @category function
     */
    public static function register_post_tags()
    {
        self::$post_tag_args['labels'] = self::$post_tag_labels;

        register_taxonomy(self::$post_tag, Game::get_post_types(), self::$post_tag_args);
    }

    /**
     * Registers the card post types
     * @return null
     * @category function
     */
    public static function register_post_type()
    {
        foreach (Game::get_data() as $game_data) {
            $args = array_merge(array(
              'label'        => $game_data->post_title,
              'labels'       => self::$post_type_labels,
              'show_in_menu' => 'edit.php?post_type='.Game::$post_type,
          ), self::$post_type_args);

            register_post_type($game_data->post_type, $args);
        }
    }

    /**
     * Register the card post types meta boxes
     * @return null
     */
    public static function register_meta_boxes()
    {
        $post_type = Helper::get_post_type_admin_params();

        if (!empty($post_type)) {
            $options = Game::get_card_configurations_by_post_type($post_type);

            $card_details = new_cmb2_box(array(
                'id'           => Config::$plugin_prefix.'_game_card_details',
                'title'        => 'Card Details',
                'object_types' => Game::get_post_types(),
            ));

            $card_details->add_field(array(
                'name'        => 'Card Type',
                'description' => '',
                'id'          => Config::$plugin_prefix . '_card_type',
                'type'        => 'select',
                'options'     => $options,
            ));
        }
    }
}
