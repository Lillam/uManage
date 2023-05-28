$(() => {
    // assigning body to a $body variable, so that we can apply on click and other events onto the body, this will be
    // a safe haven happening for all click events and other events to be latched on where ajax is concerned... all
    // events will be propagated to the body.
    let $body = $('body');

    // instantiating the content summernote, so that the user in question is going to be able to enter a variety of
    // updates and showing the other users of the system the types of things that are changing on the system.
    $body.on('click', '.content', function (event) {
        let $this = $(this);
        handleSummernoteOpen('content', $this.html().trim());
        $this.parent().find('.content_options').removeClass('uk-hidden');
        $this.summernote(window.summernote_options).next().find('.note-editable').placeCursorAtEnd();
    });

    // on the click of save content or cancel content, then we are going to be deciding what we're doing with the above
    // if the button has the class of save, then we are going to be proceeding with throwing this data into the database
    // otherwise, we're just going to cancel the box, and return it to factory settings in the way that it was prior to
    // being clicked on.
    $body.on('click', '.save_content, .cancel_content', function (event) {
        event.preventDefault();
        let $this = $(this),
            $content = $('.content'),
            handle = $this.hasClass('cancel') ? 'cancel' : 'save',
            field = 'content',
            update_on_save = $this.hasClass('cancel') ? true : false;

        $content.summernote('destroy');

        $this.parent().addClass('uk-hidden');

        handleSummernoteLeave($content, handle, field, update_on_save);
    });
});