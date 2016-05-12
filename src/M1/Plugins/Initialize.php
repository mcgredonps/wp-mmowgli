<?php

namespace M1\Plugins;

use M1\Classes\Card;
use M1\Classes\Config;
use M1\Classes\Game;
use M1\Classes\Helper;

class Initialize
{

    private static $instance;

    private $endpoints = array( 'edit_current_card', 'new_main_card', 'reply_current_card' );

    private $endpoint_types = array('action', 'view');

    private $widgets = array( 'AllGamesList', 'EditCurrentCardButton', 'NewMainCardButton', 'ReplyCurrentCardButton' );

    /**
     * Ensure that we are only working with one instance of this Classes
     * @return object self
     */
    public static function instance()
    {
        if (!self::$instance) {
            self::$instance = new self();

            self::$instance->initialize_hooks();
        }

        return self::$instance;
    }

  /**
   * Initializes hooks
   * @return null
   * @category function
   */
    private function initialize_hooks()
    {
        add_action('widgets_init', array(&$this, 'register_widgets'), 10);



        add_action('enter_title_here', array(&$this, 'enter_title_here'), 10, 2);



        add_action('admin_head', array(&$this, 'admin_head'), 10, 0);

        add_action('wp_head', array(&$this, 'wp_head'), 10, 0);



        add_action('transition_post_status', array(&$this, 'register_publish_actions'), 10, 3);

        add_action('save_post', array(&$this, 'register_save_actions'), 100, 3);

        add_filter('the_content', array(&$this, 'register_content_filters'), 10, 1);

        add_filter('the_title', array(&$this, 'title_card_type_square_prefix'), 10, 2);



        add_filter('manage_posts_columns', array(&$this, 'add_card_type_square_column'), 10, 2);

        add_filter('manage_pages_custom_column', array(&$this, 'add_card_type_square_column_data'), 10, 2);



        add_filter('manage_posts_columns', array(&$this, 'add_game_card_count_column'), 10, 2);

        add_filter('manage_game_posts_custom_column', array(&$this, 'add_game_card_count_column_data'), 10, 2);



        add_action('init', array(&$this, 'register_game_post_type'), 2);

        add_action('cmb2_admin_init', array(&$this, 'register_game_post_type_meta_boxes'), 10);

        add_action('admin_menu', array(&$this, 'remove_game_post_type_default_meta_boxes'), 10);



        add_action('init', array(&$this, 'register_game_card_post_tags'), 2);

        add_action('init', array(&$this, 'register_game_card_post_types'), 1);

        add_action('cmb2_admin_init', array(&$this, 'register_game_card_post_types_meta_boxes'), 10);



        add_action('wp_enqueue_scripts', array(&$this, 'wp_enqueue_scripts'), 10);

        add_action('admin_enqueue_scripts', array(&$this, 'admin_enqueue_scripts'), 10);



        add_action('admin_init', array(&$this, 'refresh_permalinks'), 10);

        add_action('game_save', array(&$this, 'redirect_post_location'), 10, 3);

        add_action('game_save', array(&$this, 'generate_card_type_ids'), 100, 3);

        add_action('game_card_save', array(&$this, 'save_short_permalink'), 10, 3);



        add_filter('game_content', array(&$this, 'add_game_description'), 15, 2);

        add_filter('game_content', array(&$this, 'add_game_cards_list'), 25, 2);

        add_filter('game_card_content', array(&$this, 'add_game_card_parent'), 15, 2);

        add_filter('game_card_content', array(&$this, 'add_game_card_children'), 25, 2);



        add_action('print_edit_card_button', array(&$this, 'print_edit_card_button'), 10, 1);

        add_action('print_reply_card_button', array(&$this, 'print_reply_card_button'), 10, 1);

        add_action('print_new_card_button', array(&$this, 'print_new_card_button'), 10, 1);

        add_action('wp_footer', array(&$this, 'print_new_card_modal'), 10, 0);



        foreach ($this->endpoints as $endpoint) {
            foreach ($this->endpoint_types as $endpoint_type) {
                $callback = $endpoint . '_' . $endpoint_type;

                add_action('wp_ajax_' . $callback, array(&$this, $callback), 10, 0);

                add_action('wp_ajax_nopriv_' . $callback, array(&$this, $endpoint), 10, 0);
            }
        }
    }

    /**
     * Custom ajax action
     * @todo
     * @return wp_die
     * @category hook
     */
    public function edit_current_card_action()
    {
        wp_die($_GET['action']);
    }

    /**
     * Custom ajax view
     * @todo
     * @return wp_die
     * @category hook
     */
    public function edit_current_card_view()
    {
        wp_die($_GET['action']);
    }

    /**
     * Custom ajax action
     * @todo
     * @return wp_die
     * @category hook
     */
    public function new_main_card_action()
    {
        wp_die($_GET['action']);
    }

    /**
     * Custom ajax view
     * @todo
     * @return wp_die
     * @category hook
     */
    public function new_main_card_view()
    {
        wp_die($_GET['action']);
    }

    /**
     * Custom ajax action
     * @todo
     * @return wp_die
     * @category hook
     */
    public function reply_current_card_action()
    {
        wp_die($_GET['action']);
    }

    /**
     * Custom ajax view
     * @todo
     * @return wp_die
     * @category hook
     */
    public function reply_current_card_view()
    {
        wp_die($_GET['action']);
    }

    /**
     * Registers all widgets in the widgets namespace
     * @return null
     * @category hook
     */
    public function register_widgets()
    {
        foreach ($this->widgets as $widget) {
            register_widget("\M1\Widgets\\{$widget}");
        }
    }

    /**
     * Change the 'enter_title_here' placeholder on game and title post edit
     * @return string The 'enter_title_here' placeholder
     * @category hook
     */
    public function enter_title_here($title, $post)
    {
        if (Game::instance()->set_post($post)->is_game_post_type()) {
            return 'Enter ' . Game::$post_type . ' title here';
        }

        if (Card::instance()->set_post($post)->is_card_post_type()) {
            return 'Enter ' . Card::$post_type . ' description here';
        }

        return $title;
    }

    /**
     * Print dynamic styles to the backend head
     * @return null
     * @category hook
     */
    public function admin_head()
    {
        $card_type_column_key = Config::$card_type_column_key;

        echo "<style>
                .manage-column.column-{$card_type_column_key} {
                    text-align: center;
                    width: 40px;
                }
                .manage-column.column-{$card_type_column_key} i {
                    margin-top: 4px;

                }
                .{$card_type_column_key}.column-{$card_type_column_key} {
                    text-align: center;
                 }
               .fa-game-{$card_type_column_key} {
                    color: #333;
                    margin-right: 5px;
                  }
             </style>";

        echo "<style>";

        foreach (Game::get_card_configurations_mappings() as $card_id => $color) {
            echo ".inverse.{$card_id} { background-color: inherit; color: {$color['background-color']}; } ";
            echo ".{$card_id} { background-color: {$color['background-color']}; color: {$color['text-color']}; } ";
        }

        echo "</style>";
    }

    /**
     * Print dynamic styles to the frontend head
     * @return null
     * @category hook
     */
    public function wp_head()
    {
        $card_type_column_key = Config::$card_type_column_key;

        echo "<style> .fa-game-{$card_type_column_key} { margin-right: 5px; color: #333; } </style>";

        echo "<style>";

        foreach (Game::get_card_configurations_mappings() as $card_id => $color) {
            echo ".inverse.{$card_id} { background-color: inherit; color: {$color['background-color']}; } ";
            echo ".{$card_id} { background-color: {$color['background-color']}; color: {$color['text-color']}; } ";
        }

        echo "</style>";
    }

    /**
     * Add the card count column to the backend game post type list
     * @param array $posts_columns  The posts column
     * @param string $post_type     The post type of the current page
     * @category hook
     */
    public function add_game_card_count_column($posts_columns, $post_type)
    {
        if (!Game::instance()->is_game_post_type($post_type)) {
            return $posts_columns;
        }

        $new = array();

        foreach ($posts_columns as $key => $title) {
            if ($key == 'date') {
                $new[ Config::$game_card_count_column_key ] = 'Card Count';
            }

            $new[ $key ] = $title;
        }

        return $new;
    }

    /**
     * Print the card type square in the column on the backedn post type list
     * @param string $column  The column namespace
     * @param int $post_id The post id of the printed row
     * @category hook
     */
    public function add_game_card_count_column_data($column, $post_id)
    {
        global $post;

        if (Game::instance()->set_post($post)->is_game_post_type()) {
            if ($column == Config::$game_card_count_column_key) {
                $card_count = Game::instance()->set_post($post)->get_card_count();

                echo Game::instance()->set_post($post)->get_card_admin_list($card_count);
            }
        }
    }

    /**
     * Add the card type square column to the backend post type list
     * @param array $posts_columns  The posts column
     * @param string $post_type     The post type of the current page
     * @category hook
     */
    public function add_card_type_square_column($posts_columns, $post_type)
    {
        if (!Card::instance()->is_card_post_type($post_type)) {
            return $posts_columns;
        }

        $new = array();

        foreach ($posts_columns as $key => $title) {
            if ($key == 'title') {
                $new[ Config::$card_type_column_key ] = '-';
            }

            $new[ $key ] = $title;
        }

        return $new;
    }

    /**
     * Print the card type square in the column on the backedn post type list
     * @param string $column  The column namespace
     * @param int $post_id The post id of the printed row
     * @category hook
     */
    public function add_card_type_square_column_data($column, $post_id)
    {
        global $post;

        if (Card::instance()->set_post($post) ->is_card_post_type()) {
            if ($column == Config::$card_type_column_key) {
                echo Helper::get_card_type_square(Card::get_card_type($post_id));
            }
        }
    }

    /**
     * Print the card type square before titles
     * @param  string $title   The title of a post.
     * @param  int $post_id    The post id
     * @return string $title   The return title
     * @category hook
     */
    public function title_card_type_square_prefix($title, $post_id = null)
    {
        $post = get_post($post_id);

        if (Card::instance()->set_post($post) ->is_card_post_type()) {
            $card_type = Card::get_card_type($post_id);

            $configurations_mappings = Game::get_card_configurations_mappings();

            if (isset($configurations_mappings[ $card_type ]['title'])) {
                $title = $configurations_mappings[ $card_type ]['title'] . ": ". $title;
            }

            if (!is_admin()) {
                $title = Helper::get_card_type_square($card_type) . $title;
            }
        }

        return $title;
    }

    /**
     * Print the children cards of a card
     * @param string $content The content being filtered
     * @param \WP_Post  $post The post object
     * @category hook
     */
    public function add_game_card_children($content, $post)
    {
        if (!is_single()) {
            return $content;
        }

        $cards_list = Helper::get_html_list($post->post_type, array('child_of' => $post->ID, 'depth' => 3));

        if (!empty($cards_list)) {
            return $content . "<div class='bootstrap-mmowgli'>" . "<span style='display: block;'>Child Cards: </span>" . $cards_list  . '</div>';
        }

        return $content;
    }

    /**
     * Print the parent card of a card
     * @param string $content The content being filtered
     * @param \WP_Post  $post The post object
     * @category hook
     */
    public function add_game_card_parent($content, $post)
    {
        if (!is_single()) {
            return $content;
        }

        $parent_post = Card::instance()->set_post($post)->get_parent();

        if ($parent_post) {
            return $content . "<div class='bootstrap-mmowgli'>" . "<span style='display: block;'>Parent Card: </span>" . "<a href='" . get_permalink($parent_post) . "'>" . get_the_title($parent_post) . '</a>' . '</div>';
        }

        return $content;
    }

    /**
     * Add a list of cards to a game page
     * @param string $content The content being filtered
     * @param \WP_Post $post  The post object
     * @category hook
     */
    public function add_game_cards_list($content, $post)
    {
        return $content . "<div class='bootstrap-mmowgli'>" . Helper::get_html_list(Game::$post_type . '-' . $post->ID) . '</div>';
    }

    /**
     * Add the game description to the game page
     * @param string   $content The content being filtered
     * @param \WP_Post $post  The post object
     * @category hook
     */
    public function add_game_description($content, $post)
    {
        $game_description = nl2br(Helper::get_game_description($post->ID));

        return $content . "<div class='bootstrap-mmowgli'>" . '<p>' . $game_description . '</p>' . '</div>';
    }

    /**
     * Generate card type ids for game cards
     * @param  int      $post_id The post id
     * @param  \WP_Post $post    The post object
     * @param  bool     $update  If this is an update
     * @return null
     * @category hook
     */
    public function generate_card_type_ids($post_id, $post, $update)
    {
        Game::instance()->set_post($post)->generate_card_type_ids();
    }

    /**
     * When a new card is created then create a short permalink
     * @param  int      $post_id The post id
     * @param  \WP_Post $post    The post object
     * @param  bool     $update  If this is an update
     * @return null
     * @category hook
     */
    public function save_short_permalink($post_id, $post, $update)
    {
        // Unhook this function to prevent infinite looping
        remove_action('game_card_save', array(&$this, 'save_short_permalink'));

        // Update the post slug to be a short slug based on the id
        wp_update_post(array('ID' => $post_id, 'post_name' => Helper::generate_hash_id($post_id)));

        // Rehook this function into Wordpress
        add_action('game_card_save', array(&$this, 'save_short_permalink'));
    }

    /**
     * Remove the default slug meta box from game post types
     * @return null
     * @category hook
     */
    public function remove_game_post_type_default_meta_boxes()
    {
        foreach (Game::get_post_types() as $post_type) {
            remove_meta_box('slugdiv', $post_type, 'advanced');
        }
    }

    /**
     * Register custom content filters
     * @param  string $content The content
     * @return string          The content
     */
    public function register_content_filters($content)
    {
        $post = get_post();

        if (Game::instance()->set_post($post)->is_game_post_type()) {
            $content = apply_filters('game_content', $content, $post);
        }

        if (Card::instance()->set_post($post)->is_card_post_type()) {
            $content = apply_filters('game_card_content', $content, $post);
        }

        return $content;
    }

    /**
     * Register custom publish actions
     * @param  int      $post_id The post id
     * @param  \WP_Post $post    The post object
     * @return null
     * @category hook
     */
    public function register_publish_actions($new_status, $old_status, $post)
    {
        // If we are transitioning to a publish state from a non-publish state
        if ($new_status == 'publish' && $old_status != 'publish') {
            if (Game::instance()->set_post($post)->is_game_post_type()) {
                do_action('game_publish', $post->ID, $post);
            }

            if (Card::instance()->set_post($post)->is_card_post_type()) {
                if (Card::instance()->set_post($post)->has_parent()) {
                    do_action('game_card_publish_has_parent', $post->ID, $post);
                }

                do_action('game_card_publish', $post->ID, $post);
            }
        }
    }

    /**
     * Register custom save actions
     * @param  int      $post_id The post id
     * @param  \WP_Post $post    The post object
     * @param  bool     $update  If this is an update
     * @return null
     * @category hook
     */
    public function register_save_actions($post_id, $post, $update)
    {
        if (wp_is_post_revision($post_id)) {
            return;
        }

        if (Game::instance()->set_post($post)->is_game_post_type()) {
            do_action('game_save', $post_id, $post, $update);
        }

        if (Card::instance()->set_post($post)->is_card_post_type()) {
            do_action('game_card_save', $post_id, $post, $update);
        }
    }

    /**
     * Filter to modify the redirect url args
     * @return   null
     * @category hook
     */
    public function redirect_post_location()
    {
        add_filter('redirect_post_location', array(&$this, 'redirect_post_location_args'), 10, 2);
    }

    /**
     * Modify the redirect location arguments to refresh permalinks
     * @param  string $location The filtered url
     * @param  int    $post_id  The post id
     * @return [type]           [description]
     */
    public function redirect_post_location_args($location, $post_id)
    {
        return add_query_arg(array( Config::$refresh_permalinks_key   => 'true',
                                    '_wpnonce'                        => wp_create_nonce(Config::$refresh_permalinks_nonce)), $location);
    }

    /**
     * Refresh permalinks if nonce is set and our querystring is set
     * @return    null
     * @category  hook
     */
    public function refresh_permalinks()
    {
        if (!isset($_GET[ Config::$refresh_permalinks_key ])) {
            return;
        }

        if (!isset($_GET[ '_wpnonce' ])) {
            return;
        }

        if (!wp_verify_nonce($_GET[ '_wpnonce' ], Config::$refresh_permalinks_nonce)) {
            return;
        }

        flush_rewrite_rules();
    }

    /**
     * Enqueue scripts for the front end
     * @return    null
     * @category  function
     */
    public function enqueue_scripts()
    {
        wp_enqueue_style(Config::$plugin_prefix . '-bootstrap', MMOWGLI_PLUGIN_URL . 'assets/css/bootstrap-custom.css');

        wp_enqueue_script(Config::$plugin_prefix . '-bootstrap', MMOWGLI_PLUGIN_URL . 'assets/js/bootstrap-custom.js', array( 'jquery'));

        wp_enqueue_style(Config::$plugin_prefix . '-font-awesome', MMOWGLI_PLUGIN_URL . 'assets/css/font-awesome.css');

        wp_enqueue_style(Config::$plugin_prefix . '-style', MMOWGLI_PLUGIN_URL . 'assets/css/style.css', array( Config::$plugin_prefix . '-bootstrap', Config::$plugin_prefix . '-font-awesome'));

        wp_enqueue_script(Config::$plugin_prefix . '-script', MMOWGLI_PLUGIN_URL . 'assets/js/script.js', array( 'jquery', Config::$plugin_prefix . '-bootstrap', ));
    }

    /**
     * Lozalize scripts for the front end
     * @return    null
     * @category  function
     */
    public function localize_scripts()
    {
        $localized_data = array('ajax_url'        => admin_url('admin-ajax.php'),
                                'is_card_page'    => Helper::is_card_page() ? 'true' : 'false',
                                'is_game_page'    => Helper::is_game_page() ? 'true' : 'false',
                                'is_mmowgli_page' => Helper::is_mmowgli_page() ? 'true' : 'false',
                              );

        wp_localize_script(Config::$plugin_prefix . '-script', Config::$plugin_prefix, $localized_data);
    }

    /**
     * Enqueue scripts for the front end
     * @return    null
     * @category  hook
     */
    public function wp_enqueue_scripts()
    {
        $this->enqueue_scripts();

        $this->localize_scripts();
    }

    /**
     * Enqueue scripts for the backend
     * @return    null
     * @category  hook
     */
    public function admin_enqueue_scripts()
    {
        $this->enqueue_scripts();

        $this->localize_scripts();
    }

    /**
     * Register the game post type
     * @return    null
     * @category  hook
     */
    public function register_game_post_type()
    {
        Game::register_post_type();
    }

    /**
     * Register the game post type meta boxes
     * @return    null
     * @category  hook
     */
    public function register_game_post_type_meta_boxes()
    {
        Game::register_meta_boxes();
    }

    /**
     * Register the card post tags
     * @return    null
     * @category  hook
     */
    public function register_game_card_post_tags()
    {
        Card::register_post_tags();
    }

    /**
     * Register the card post types
     * @return    null
     * @category  hook
     */
    public function register_game_card_post_types()
    {
        Card::register_post_type();
    }

    /**
     * Register the card post types meta boxes
     * @return    null
     * @category  hook
     */
    public function register_game_card_post_types_meta_boxes()
    {
        Card::register_meta_boxes();
    }

    /**
     * Custom action to print the edit card button
     * @param  string $button_text The text of the button
     * @return null   Echos data
     * @category hook
     */
    public function print_edit_card_button($button_text = 'Edit this card!')
    {
        echo "<div class='bootstrap-mmowgli'>";
        echo "<button class='btn btn-default edit-card-btn action-card-btn'
                data-remote='false' data-toggle='modal' data-target='#newCardModal'
                href='remoteContent.html' type='button'>{$button_text}</button>";
        echo "</div>";
    }

    /**
     * Custom action to print the edit card button
     * @param  string $button_text The text of the button
     * @return null   Echos data
     * @category hook
     */
    public function print_reply_card_button($button_text = 'Reply to this card!')
    {
        echo "<div class='bootstrap-mmowgli'>";
        echo "<button class='btn btn-primary reply-card-btn action-card-btn'
                data-remote='false' data-toggle='modal' data-target='#newCardModal'
                href='remoteContent.html' type='button'>{$button_text}</button>";
        echo "</div>";
    }

    /**
     * Custom action to print the new card button
     * @param  string $button_text The text of the button
     * @return null   Echos data
     * @category hook
     */
    public function print_new_card_button($button_text = 'Create a new main card!')
    {
        echo "<div class='bootstrap-mmowgli'>";
        echo "<button class='btn btn-success new-card-btn action-card-btn'
                data-remote='false' data-toggle='modal' data-target='#newCardModal'
                href='remoteContent.html' type='button'>{$button_text}</button>";
        echo "</div>";
    }

    /**
     * Custom action to print the new card modal template
     * @return null   Echos data
     * @category hook
     */
    public function print_new_card_modal()
    {
        echo '<!-- Default modal template -->
              <div class="bootstrap-mmowgli">
                <div class="modal fade" id="newCardModal" tabindex="-1" role="dialog" aria-labelledby="newCardModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="newCardModalLabel">Modal title</h4>
                      </div>
                      <div class="modal-body">
                        ...
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Create Card</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>';
    }
}
