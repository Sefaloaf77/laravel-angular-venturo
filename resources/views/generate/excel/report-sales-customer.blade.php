<table>
    <thead>
        <tr>
            <td style="width: 170px; vertical-align: middle;" rowspan="2" id="customer">Customer</td>
            <td colspan="{{count($dates)}}" class="text-center" id="periode">Periode</td>
            <td style="width: 70px; vertical-align: middle;" rowspan="2" id="total">Total</td>
        </tr>
        <tr>
            @foreach($dates as $date)
            <td>
                {{date('d', strtotime($date))}}
            </td>
            @endforeach
        </tr>
    </thead>
    <tbody>
        <tr class="customer">
            <td></td>
        </tr>
        @foreach($data as $customer)
        <tr>
            <td>
                {{$customer['customer_name']}}
            </td>
            @foreach($customer['transactions'] as $sale)
            <td class="nominal">
                Rp {{number_format($sale['total_sales'])}}
            </td>
            @endforeach
            <td class="nominal">
                Rp {{number_format($customer['transactions_total'])}}
            </td>
        </tr>
        @endforeach
        <tr class="total">
            <td>Grand Total</td>
            @foreach($total_per_date as $total)
            <td class="nominal">
                Rp {{number_format($total)}}
            </td>
            @endforeach
            <td class="nominal">
                Rp {{number_format($grand_total)}}
            </td>
        </tr>
    </tbody>
</table>