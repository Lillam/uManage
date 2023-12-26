@foreach ($paybacks as $payback)
    <p>[{{ $payback->paid_when->format('Y-m-d') }}] £{{ $payback->amount }}</p>
@endforeach