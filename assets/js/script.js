jQuery(document).ready(function($) {

    MMOWGLI = {

        attributeOne: '',

        initialize: function() {

            $(".new-game-card").click(this.newButtonClick);

        },
        newButtonClick: function() {

            alert("button");

        }

    }

    MMOWGLI.initialize();

});
