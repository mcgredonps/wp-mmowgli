WP MMOWGLI
========================================

MMOWGLI stands for Massive Multiplayer Online War Game Leveraging the Internet. It is a message-based game used to encourage innovative thinking by many people, connected via the internet. This project is based on the original MMOWGLI project (https://portal.mmowgli.nps.edu/) initiated by the Office of Naval Research (ONR) for the United States Navy. This project aims to port MMOWGLI capabilities on the open-source WordPress framework.

Installation / Usage
--------------------

1. This plugin requires composer to install plugin dependencies. Clone this repository and run: `composer install`
2. When the composer install is complete, install this repository as you would any WordPress plugin.

Additional instructions and screenshots will be available in a future release. Game setup and configurations can be done from the administrator area under "Games".

Requirements
------------

PHP 5.3.2 or above (at least 5.3.4 recommended to avoid potential bugs).

Todo / In Progress
-------
1. Functionality
    * Wire up the new card modal on the front end
    * Wire up the reply to card modal on the front end
    * Wire up the edit card modal on the front end
    * Wire up the assigning of points to the user creating the card
2. Shortcodes / Widgets
    * Logged in user and user points
    * Sortable leaderboard 

License
-------

WP MMOWGLI is licensed under the GPL License - see the LICENSE file for details.

Acknowledgments
---------------

- This project started out as a WordPress port of [MMOWGLI](https://portal.mmowgli.nps.edu/) - developed by the Office of Naval Research (ONR).
- This project uses open source libraries and plugins to reduce development time. Thanks!
    -   [Bootstrap](https://getbootstrap.com/)
    -   [CMB2](https://github.com/WebDevStudios/CMB2)
    -   [Font Awesome](https://fortawesome.github.io/Font-Awesome/)
    -   [Hash Ids](https://github.com/ivanakimov/hashids.php)
    -   [Redux Framework](https://github.com/reduxframework/redux-framework)
    -   [UtilPHP](https://github.com/brandonwamboldt/utilphp)
