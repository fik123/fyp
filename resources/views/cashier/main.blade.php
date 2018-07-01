@extends('layouts.app')

@section('content')
<main>
	<div class="container-fluid">
        <div class="row">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('kitchen.cashier')}}">All table</a></li>
                <li class="breadcrumb-item active">Order no: {{$orders->first()->orderno}}</li>
            </ol>
        </div>
        @include('searchorder')
        <div class="row">
           <table class="table">
                <!--Table head-->
                <thead class="blue-grey lighten-4">
                    <tr>
                        <th>#</th>
                        <th>Menu Name</th>
                        <th>Status</th>
                        <th>Price</th>
                        <th>Pay</th>
                        <th>Select All</th>
                    </tr>
                </thead>
                <!--Table head-->

                <!--Table body-->
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <th scope="row">{{$order->id}}</th>
                        <td>{{$order->menu->name}}</td>
                        <td>{{$order->status}}</td>
                        <td>{{$order->menu->price}}</td>
                        @switch($order->status)
                            @case('completed')
                                <td><button type="button" class="btn btn-default disabled">Paid</button></td>
                                @break

                            @case('cancelled')
                                <td><button type="button" class="btn btn-warning disabled">Cancelled</button></td>
                                @break
    
                            @default
                                @if($order->paid)
                                <td><button type="button" class="btn btn-default disabled">Paid</button></td>
                                @else
                                <td><button type="button" class="btn btn-success" data-toggle="modal" data-target="#paymodal" onclick="launchpaymodal({{$order->id}},'singlepay')">Pay</button></td>
                                <td><div class="form-check checkbox-rounded checkbox-success-filled">
                                    <input type="checkbox" id="cashouttablecheckbox{{$order->id}}" class="filled-in form-check-input cashouttablecheckbox" checked data-orderid="{{$order->id}}">
                                    <label class="form-check-label" for="cashouttablecheckbox{{$order->id}}">Success</label>
                                </div></td>
                                @endif
                        @endswitch

                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="3">Order no: {{$orders->first()->orderno}}</td>
                        <td>{{$orders->where('status','!=','cancelled')->where('status','!=','completed')->where('paid','!=',1)->sum('menuprice')}}</td>
                        <td><button type="button" class="btn btn-success" data-toggle="modal" data-target="#paymodal" onclick="launchpaymodal({{$orders->first()->orderno}},'bulkpayall')">Pay Remaining</button></td>
                        <td><button type="button" class="btn btn-success" data-toggle="modal" data-target="#paymodal" onclick="launchpaymodal({{$orders->first()->orderno}},'bulkpay')">Pay Selected</button></td>
                    </tr>
                </tbody>
                <!--Table body-->

            </table>
            <!--Table-->

        </div>
    </div>
</main>
@endsection

@push('modalstacks')
<!-- Modal -->
<!-- Full Height Modal Right -->
<div class="modal fade bottom" id="paymodal" tabindex="-1" role="dialog" aria-labelledby="paymodalmenuname" aria-hidden="true">
    <div class="modal-dialog modal-frame modal-bottom" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymodalmenuname"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="paymodalbody">
                <div class="container">
                    <div class="row">
                        <div class="col-md-1">Price</div>
                        <div class="col-md-3"><input type="number" id="paymodalinputprice" class="form-control disabled" disabled></div>
                        <div class="col-md-1">Pay</div>
                        <div class="col-md-3"><input type="number" id="paymodalinputpayment" class="form-control"></div>
                        <div class="col-md-1">Balance</div>
                        <div class="col-md-3"><input type="number" id="paymodalinputbalance" class="form-control disabled" disabled></div>
                    </div>
                    <input type="hidden" id="paymodalinputorderid">
                    <input type="hidden" id="paytype">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Abort</button>
                <button type="button" class="btn btn-primary" id="paymodalbuttoncomplete" >Completed</button>
            </div>
        </div>
    </div>
</div>
@endpush

@push('kikiscripts')
<script>
    function launchpaymodal(orderid,paytype) {
        $("#paytype").val(paytype);
        $('#paymodalinputorderid').val(orderid);
        switch(paytype) {
            case 'singlepay':
                $.ajax({
                    type: 'GET',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: '/cashier/'+orderid,
                    contentType: "application/json",
                    success: function(data) {
                        console.log(data.order,data.ordermenu);
                        // $("#paymodalbody").empty();
                        $('#paymodalmenuname').text(data.ordermenu.name);
                        $('#paymodalinputprice').val(data.ordermenu.price);
                        $('#paymodalinputorderid').val(data.order.id);
                    } 
                }); 
                break;
            case 'bulkpay':
                var orderidsforbulkpaid = [];
                $('.cashouttablecheckbox[type=checkbox]').each(function () {
                    if (this.checked) {
                        orderidsforbulkpaid.push($(this).data("orderid"));
                    }
                });
                var ids = orderidsforbulkpaid.toString();
                $.ajax({
                    type: 'GET',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: '/cashierselected/'+orderid+'?ids='+ids,
                    contentType: "application/json",
                    success: function(data) {
                        console.log("bulkpay",data);
                        // $('#paymodalmenuname').text(data.ordermenu.name);
                        $('#paymodalinputprice').val(data.totalprice);
                    } 
                }); 
                break;
            case 'bulkpayall':
                $('#paymodalmenuname').text('Order no : '+orderid);
                $.ajax({
                    type: 'GET',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: '/cashierall/'+orderid,
                    contentType: "application/json",
                    success: function(data) {
                        console.log("bulkpayall",data);
                        // // $("#paymodalbody").empty();
                        
                        $('#paymodalinputprice').val(data.totalprice);
                    } 
                }); 
                break;
            default:
        }
        
    }
    $("#paymodalbody").on('keyup', '#paymodalinputpayment',function () {
        var bal = parseInt($(this).val()) - $('#paymodalinputprice').val();
        $('#paymodalinputbalance').val(bal);
    });

    $("#paymodalbuttoncomplete").on('click',function () {
        var paytype = $("#paytype").val();
        var orderid = $("#paymodalinputorderid").val();
        switch(paytype) {
            case 'singlepay':
                singlepay(orderid);
                break;
            case 'bulkpay':
                bulkpaid(orderid);
                break;
            case 'bulkpayall':
                bulkpaidall(orderid);
                break;
            default:
        }
    });

    function singlepay(orderid) {
        var orderid = $("#paymodalinputorderid").val();
        $.ajax({
            type: 'PUT',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: '/order/'+orderid,
            data: JSON.stringify({"status": "paid"}),
            contentType: "application/json",
            success: function (data) { 
                if (data.status == 'success') {
                    toastr.success(data.msg); 
                    location.reload();   
                    // cookedlist.appendChild($('.domid-'+orderid)[0]);
                    // var pgbar = '<div class="progress" style="height: 20px"><div class="progress-bar" role="progressbar" style="width: 70%; height: 20px" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100">25%</div></div>';
                    // $('.ordercount-'+data.order.id).html(pgbar);
                }else if(data.status == 'error'){
                    toastr.error(data.msg);
                }
            }
        }); 
    }
    function bulkpaid(orderno) {
        console.log("dah paid : ",orderno);
        // update order status to served
        var orderidsforbulkpaid = [];
        $('.cashouttablecheckbox[type=checkbox]').each(function () {
            if (this.checked) {
                orderidsforbulkpaid.push($(this).data("orderid"));
            }
        });
        $.ajax({
            type: 'PUT',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: '/bulkorder/'+orderno,
            data: JSON.stringify({"status": "paid","orderids": orderidsforbulkpaid}),
            contentType: "application/json",
            success: function (data) { 
                if (data.status == 'success') {
                    toastr.success(data.msg);   
                    location.reload();
                }else if(data.status == 'error'){
                    toastr.error(data.msg);
                }
            }
        }); 
    }
    function bulkpaidall(orderno) {
        console.log("dah paid : ",orderno);
        // update order status to served
        $.ajax({
            type: 'PUT',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: '/bulkorder/'+orderno,
            data: JSON.stringify({"status": "paid"}),
            contentType: "application/json",
            success: function (data) { 
                if (data.status == 'success') {
                    toastr.success(data.msg);   
                    location.reload();
                }else if(data.status == 'error'){
                    toastr.error(data.msg);
                }
            }
        }); 
    }
</script>
@endpush