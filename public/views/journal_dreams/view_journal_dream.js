$(() => {
    let $body = $('body');

    $body.on('click', '.content, .meaning', function (event) {
        let $this = $(this),
            original_class = $this[0].classList[0],
            original_content = $this.html().trim();

        // save the content that is originally stored inside this box wrapper, so that we can reference it when the user
        // decides to walk away from the content, and we can return the content back to what it was prior to the user
        // pressing cancel, or save.
        cache().set(original_class, original_content);

        // if the box contains a placeholder, then we are going to want to get rid of the placeholder so that the user
        // can begin typing like normal without the need of a placeholder being there.
        if ($this.find('.placeholder').length >= 1) {
            $this.html('');
        }

        // show the buttons for being able to click save or cancel.
        $this.next().removeClass('uk-hidden');

        // turn this object into a summernote with the globalised summernote options which is defined inside of
        // application.js
        $this.summernote(window.summernote_options).next().find('.note-editable').placeCursorAtEnd();
    });

    // when the user has clicked on the save buttons for the above summernote objects that might have been prepared...
    // then we are going to collect the class name from the class list, and ensure we're picking the first one...
    // this will be the target that we are not only editing, but sending up to the database as the field name.
    // after the element has been fired up and saved, we are going to shut down summernote object, and return it back
    // to a default divider element; for slick feeling functionality.
    $body.on('click', '.save_content, .save_meaning', function (event) {
        let $this = $(this),
            class_list = $this[0].classList,
            target_class = class_list[0].replace('save_', '');

        $(`.${target_class}`).summernote('destroy');
        $this.parent().addClass('uk-hidden');

        // we have to define this after we have finished editing, so that we can grab exactly what is currently
        // sitting iwthin this containing element.
        let content = $this.parent().parent().find(`.${target_class}`).html();

        // edit the journal, with the target class and the content of which we have decided to edit. with the content
        // needed. (this method will be making an ajax call).
        edit_journal_dream(target_class, content);

        // when the user has opted to save, whether or not they have done anything specific, the value inside the cache,
        // is going to want to be deleted. we no longer need to reference what it might have been and can continue
        // as normal.
        cache().remove(target_class);
    });

    // when the user clicks on cancel of the above element, content, then we should assume that the user is no longer
    // wishing to make any amends, in which, we are going to destroy the summernote object returning the element back to
    // it's divider self, and remove any new content that would have been added to it.
    $body.on('click', '.cancel_content, .cancel_meaning', function (event) {
        let $this = $(this),
            class_list = $this[0].classList,
            target_class = class_list[0].replace('cancel_', ''),
            $target = $(`.${target_class}`);

        $target.summernote('destroy');
        $this.parent().addClass('uk-hidden');

        $target.html(cache().get(target_class));
        cache().remove(target_class);
    });

    // this functionality is specifically for deleting an journal entry when on the page, pressing this will kick the
    // user back to the list page... whilst deleting the current day's entry. this functionality is built for those that
    // click on a day that they can't really enter on, and decide to leave it there, rather than a blank entry sit in
    // the database, they can opt to remove it from their list for a cleaner view.
    $body.on('click', '.delete_journal_dream', function (event) {
        let $this = $(this),
            $journal_dream = $('#journal_dream'),
            delete_journal_dream_url = $journal_dream.data('delete_journal_dream_url'),
            journal_dream_id = $journal_dream.data('journal_dream_id');

        $.ajax({
            method: 'post',
            url: delete_journal_dream_url,
            data: {
                journal_dream_id: journal_dream_id
            },
            success: function (data) {
                window.location.href = data.response;
            }
        });
    });

    // on the journal dream page, the user has the option of rating their dream, by clicking on 1, 2, 3, 4 or 5 stars
    // will instantly fire up to the database the rating that the user has selected, the day needs a rating, so there
    // will be no means to take the rating away from the day once selected, but can be altered when has been selected
    // as the day can possibly change for the user, and the means to alter the rating exists here.
    $body.on('click', '.journal_dream_rating', function (event) {
        let $this = $(this),
            rating = $this.data('rating');

        $('.journal_rating').removeClass('active')
            .removeClass('fa fa-star')
            .addClass('far fa-star');

        for (let i = 0; i < rating + 1; i++) {
            $('.journal_dream_rating[data-rating="' + i + '"]')
                .removeClass('far fa-star')
                .addClass('fa fa-star');
        }

        edit_journal_dream('rating', rating);
    });

    $body.on('click', '.journal_dream_information', function (event) {
        let $journal_dream = $('#journal_dream');

        event.stopPropagation();

        if (window.innerWidth <= 991) {
            $journal_dream.removeClass('journal_dream_sidebar_closed');
            $journal_dream.toggleClass('journal_dream_sidebar_open');
        }

        if (window.innerWidth > 991) {
            $journal_dream.toggleClass('journal_dream_sidebar_closed');
            $journal_dream.removeClass('journal_dream_sidebar_open');
        }
    });

    $body.on('click', '.journal_dream_sidebar', function (event) {
        event.stopPropagation();
    });

    $body.on('click', function (event) {
        $('.journal_dream_sidebar_open').removeClass('journal_dream_sidebar_open');
    });
});

/**
 * This method is specifically for crafting the request and response to and from the server in regards to editing
 * journal dreams. the field and value are completely variable that we are able to pass through in the code in order to
 * manipulate the journal dream
 *
 * @param field    (string)§
 * @param value    (wildcard|variable)
 * @param callback (null|function)
 */
var edit_journal_dream = function (field, value, callback = null) {
    let $journal_dream = $('#journal_dream'),
        journal_dream_id = $journal_dream.data('journal_dream_id'),
        edit_journal_dream_url = $journal_dream.data('edit_journal_dream_url');

    $.ajax({
        method: 'post',
        url: edit_journal_dream_url,
        data: {
            field: field,
            value: value,
            journal_dream_id: journal_dream_id,
        },
        success: function (data) {
            ajax_message_helper($('.ajax_message_helper'), data);
            if (callback !== null)
                callback();
        }
    });
};
