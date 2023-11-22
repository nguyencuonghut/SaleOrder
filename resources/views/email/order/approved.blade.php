<x-mail::message>
Xin chào,

Đơn đặt hàng của bạn đã được duyệt.<br>
Kỳ: {{$order->schedule->title}}.<br>
Số sản phẩm: {{$order_products_cnt}}.<br>
Tổng trọng lượng: {{number_format($order->total_weight, 0, '.', ',')}} KG.<br>
@if ('level_1' == $level)
    @if('Đồng ý' == $order->level1_manager_approved_result)
    - Kết quả: <b style="color:green;">{{$order->level1_manager_approved_result}}</b>.<br>
    - Trạng thái: <b style="color:blue;">{{$order->status}}</b>.
    @else
    - Kết quả: <b style="color:red;">{{$order->level1_manager_approved_result}}</b>.
    @endif
@elseif('level_2' == $level)
    @if('Đồng ý' == $order->level2_manager_approved_result)
    - Kết quả: <b style="color:green;">{{$order->level2_manager_approved_result}}</b>.<br>
    - Trạng thái: <b style="color:orange;">{{$order->status}}</b>.
    @else
    - Kết quả: <b style="color:red;">{{$order->level2_manager_approved_result}}</b>.
    @endif
@endif


<x-mail::button :url="$url" color="green">
Xem đơn
</x-mail::button>

Trân trọng,<br>
{{ config('app.name') }}
</x-mail::message>
