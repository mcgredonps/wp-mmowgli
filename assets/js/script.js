jQuery(document).ready(function($) {

    MMOWGLI = {

        attributeOne: '',

        initialize: function() {

            // When the modal show function is initiated
            $("#newCardModal").on("show.bs.modal", this.showModal);

        },
        showModal: function(e) {

          // var link = $(e.relatedTarget);

          // $(this).find(".modal-body").load(link.attr("href"));

          // Reset modal title
          $(this).find(".modal-title").html('');

          // Reset modal body
          $(this).find(".modal-body").html('');



        }

    }

    MMOWGLI.initialize();

});
