@foreach ($paybacks as $payback)
    <div class="journal_loan_payback_item">
        <div class="entry">{{ $payback->id }}</div>
        <div class="amount">£{{ $payback->amount }}</div>
        <div class="when">{{ $payback->paid_when->format('Y-m-d') }}</div>
    </div>
@endforeach
