<x-mail::message>
Xin chào,

Bạn có một yêu cầu duyệt đơn đặt hàng từ {{$order->creator->name}}.


<x-mail::button :url="$url" color="green">
Duyệt
</x-mail::button>

Trân trọng,<br>
{{ config('app.name') }}
</x-mail::message>
