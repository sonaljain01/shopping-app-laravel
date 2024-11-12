{{-- resources/views/orders/track-details.blade.php --}}
@extends('front.layouts.app')

@section('content')
    <style>
        .hh-grayBox {
            background-color: #F8F8F8;
            margin-bottom: 20px;
            padding: 35px;
            margin-top: 20px;
        }

        .pt45 {
            padding-top: 45px;
        }

        .order-tracking {
            text-align: center;
            width: 33.33%;
            position: relative;
            display: block;
        }

        .order-tracking .is-complete {
            display: block;
            position: relative;
            border-radius: 50%;
            height: 30px;
            width: 30px;
            margin: 0 auto;
            transition: background 0.25s linear;
            z-index: 2;
        }

        .order-tracking p {
            color: #A4A4A4;
            font-size: 16px;
            margin-top: 8px;
            margin-bottom: 0;
            line-height: 20px;
        }

        .order-tracking p span {
            font-size: 14px;
        }

        /* In Progress (Yellow) */
        .order-tracking.in-progress .is-complete {
            background-color: #f7be16;
            /* Yellow */
        }

        .order-tracking.in-progress p {
            color: #A4A4A4;
            /* Grey text */
        }

        /* Completed (Green) */
        .order-tracking.completed .is-complete {
            background-color: #27aa80;
            /* Green */
        }

        .order-tracking.completed p {
            color: #000;
            /* Black text */
        }

        /* Connector Line between steps */
        .order-tracking::before {
            content: '';
            display: block;
            height: 3px;
            width: calc(100% - 40px);
            top: 13px;
            position: absolute;
            left: calc(-50% + 20px);
            z-index: 0;
            background-color: #d3d3d3;
            /* Default Gray */
        }

        /* In-Progress Connector Line */
        .hh-grayBox.in-progress .order-tracking.in-progress::before {
            background-color: #f7be16;
            /* Yellow */
        }

        /* Completed Connector Line */
        .hh-grayBox.completed .order-tracking.completed::before,
        .order-tracking.completed::before {
            background-color: #27aa80;
            /* Green */
        }

        .order-tracking:first-child::before {
            display: none;
        }
    </style>

    <div class="container">
        <h2>Order Tracking Details</h2>
        <p><strong>Order ID:</strong> {{ $order->id }}</p>
        <p><strong>Products:</strong>
            @foreach ($order->orderItems as $item)
                <tr>
                    <td>{{ implode(', ', $order->orderItems->pluck('product.title')->toArray()) }}</td>
                </tr>
            @endforeach
        </p>
        <p><strong>Amount:</strong> {{ $order->total_amount }}</p>
        <p><strong>Status:</strong> {{ $order->status }}</p>
        <p><strong>Order Date:</strong> {{ $order->created_at->format('Y-m-d') }}</p>

        <div class="row">
            <div
                class="col-12 col-md-10 hh-grayBox pt45 pb20 {{ $order->status === 'completed' ? 'completed' : ($order->status === 'in-progress' ? 'in-progress' : '') }}">
                <div class="row justify-content-between">
                    @foreach ($order->orderHistories as $history)
                        <div class="order-tracking {{ $history->status === 'In Progress' ? 'in-progress' : 'completed' }}">
                            <span class="is-complete"></span>
                            <p>{{ ucfirst($history->status) }}<br>
                                <span>{{ \Carbon\Carbon::parse($history->changed_at)->format('D, M d') }}</span>
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
