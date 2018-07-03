jQuery(document).ready(function($) {
    var $espresso_admin = $('.espresso-admin'),
        $add_new_event_button = $('.page-title-action'),
        displayAddNewEventModal = function(event) {
            event.preventDefault();
            $add_new_event_button.css({'border': '1px solid red'});
            alert('create new event?');
            $espresso_admin.css({'border': '1px solid red'});
            $add_new_event_button.off('click', preventRedirect);
            $add_new_event_button.trigger('click');
            alert('still here?');

            // $add_new_event_button.trigger('click');
        },
        preventRedirect = function(event) {
            event.preventDefault();
        };
    $add_new_event_button.on('click', preventRedirect).on('click', displayAddNewEventModal);
});


