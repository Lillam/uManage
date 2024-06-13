@foreach ($paybacks as $payback)
    <p>#{{$payback->id}} [{{ $payback->paid_when->format('Y-m-d') }}] £{{ $payback->amount }}</p>
@endforeach
