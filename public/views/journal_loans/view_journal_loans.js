$(async () => {
    const $body = $('body');

    $body.on('click', '.make_journal_loan', async (e) => {
        const $add_journal_loan_modal = $('#add_journal_loan_modal'),
              make_journal_loan_url = $add_journal_loan_modal.data('make_journal_loan_url');

        await request().post(make_journal_loan_url, {
            amount: $('#amount').val(),
            interest: $('#interest').val(),
            reference: $('#reference').val(),
            when_loaned: $('#when_loaned').val(),
            when_pay_back: $('#when_pay_back').val()
        });

        await viewJournalLoans();

        // close the modal after we're done.
        $('.uk-modal-close-default').click();
    });

    $body.on('click', '.make_journal_loan_payback', async (e) => {
        const $add_journal_loan_payback_modal = $('#add_journal_loan_payback_modal'),
              journal_loan_id = $add_journal_loan_payback_modal.attr('data-loan_id'),
              make_journal_loan_payabck_url = $add_journal_loan_payback_modal.data('make_journal_loan_payback_url');

        await request().post(make_journal_loan_payabck_url, {
            journal_loan_id,
            amount: $('#repayment_amount').val(),
            paid_when: $('#repayment_when').val()
        });

        await viewJournalLoanPaybacks();

        // $('.uk-modal-close-default').click();
    });

    $body.on('click', '.loans .loan', function () {
        const $this = $(this),
              id = $this.data('loan-id'),
              $add_journal_loan_payback_modal = $('#add_journal_loan_payback_modal');

        $add_journal_loan_payback_modal.attr('data-loan_id', id);
    });

    $body.on('click', '.make_journal_loan_payback', async () => {
         const $add_journal_loan_payback_modal = $('#add_journal_loan_payback_modal'),
               journal_loan_id = $add_journal_loan_payback_modal.attr('data-loan_id'),
               make_journal_loan_payback_url = $add_journal_loan_payback_modal.data('make_journal_loan_payback_url');

         await request().post(make_journal_loan_payback_url, {
             journal_loan_id,
             amount: $('#repayment_amount').val(),
             when: $('#repayment_when').val()
         });
    });

    await viewJournalLoans();
});

const viewJournalLoans = async () => {
    const $loans = $('.loans'),
          viewJournalLoansUrl = $loans.data('view_journal_loans_url');

    const response = await request().get(viewJournalLoansUrl);

    $loans.html(response.html);
};

const viewJournalLoanPaybacks = async () => {
    const $add_journal_loan_payback_modal = $('#add_journal_loan_payback_modal'),
          journal_loan_id = $add_journal_loan_payback_modal.attr('data-loan_id'),
          view_journal_loan_paybacks_url = $add_journal_loan_payback_modal.data('view_journal_loan_paybacks_url'),
          $journal_loan = $(`.loan[data-loan-id="${journal_loan_id}"]`);

    const response = await request().get(view_journal_loan_paybacks_url, {
        journal_loan_id
    });

    $journal_loan.find('#paybacks').html(response.html);
};