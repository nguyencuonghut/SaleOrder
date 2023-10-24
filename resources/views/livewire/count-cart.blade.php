<li class="nav-item dropdown">
    <a class="nav-link" data-toggle="dropdown" href="#">
      <i class="fas fa-shopping-cart"></i>
      <span class="badge badge-success navbar-badge">
        {{ number_format(Cart::getContent()->count(), 0, '.', ',') }}
      </span>
    </a>

    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-item dropdown-header">{{ number_format(Cart::getTotalQuantity(), 0, '.', ',') }} KG cho {{Cart::getContent()->count()}}  mã hàng</span>
        @foreach (Cart::getContent() as $item)
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
                {{$item->name}}
                <span class="float-right text-muted text-sm">{{ number_format($item->quantity, 0, '.', ',') }} KG</span>
            </a>
        @endforeach
        <a href="{{route('cart.detail')}}" wire:navigate class="dropdown-item dropdown-footer">Xem giỏ hàng</a>
    </div>
</li>
