@extends('layouts.customerapp')
@section('content')
<!-- Nav tabs -->
<div class="row">
    <div class="col-md-3">
        <ul class="nav  md-pills pills-primary flex-column" role="tablist">
            <?php 
            $i = 1;
            ?>
            @foreach($menus as $type => $menu)
            <li class="nav-item">
                <a class="nav-link @if($i == 1) active @endif" data-toggle="tab" href="#menu{{$i}}" role="tab">{{$type}}
                <i class="fa fa-file-text ml-2"></i>
                </a>
            </li>
            <?php
            $i++;
            ?>
            @endforeach
        </ul>
    </div>
    <div class="col-md-9">
        <!-- Tab panels -->
        <div class="tab-content vertical">
            <?php 
            $i = 1;
            ?>
            @foreach($menus as $type => $allmenu)
                <!--Panel 1-->
                <div class="tab-pane fade @if($i == 1) in show active @endif" id="menu{{$i}}" role="tabpanel">

                    <h5 class="my-2 h5">Panel {{$type}}</h5>
                    <div class="row">
                        @foreach($allmenu as $menu)
                            <div class="col-md-4">
                                <!-- Card Narrower -->
                                <div class="card card-cascade narrower">

                                  <!-- Card image -->
                                  <div class="view overlay">
                                    <img class="card-img-top" src="https://mdbootstrap.com/img/Photos/Lightbox/Thumbnail/img%20(147).jpg" alt="Card image cap">
                                    <a>
                                      <div class="mask rgba-white-slight"></div>
                                    </a>
                                  </div>

                                  <!-- Card content -->
                                  <div class="card-body">

                                    <!-- Label -->
                                    <h5 class="pink-text pb-2 pt-1">
                                        <i class="fas fa-utensils"></i> {{$menu->time_taken}} m 
                                    </h5>
                                    <!-- Title -->
                                    <h4 class="card-title">{{$menu->name}}</h4>
                                    <!-- Text -->
                                    <p class="card-text">{{$menu->description}}</p>
                                    <!-- Button -->
                                    <a class="btn btn-unique addtocard" 
                                            data-menu="{{$menu->id}}"
                                            data-name="{{$menu->name}}"
                                            data-price="{{$menu->price}}"
                                            data-avgtime="{{$menu->time_taken}}">RM {{$menu->price}}</a>
                                    

                                  </div>

                                </div>
                                <!-- Card Narrower -->
                            </div>
                        @endforeach    
                    </div>
                    

                </div>
                <!--/.Panel 1-->
            <?php
            $i++;
            ?>
            @endforeach
        </div>
    </div>
</div>
<div style="bottom: 45px; right: 24px; position: fixed;z-index: 998;padding-top: 15px;margin-bottom: 0;" >
    <a class="btn-floating blue" id="mycart" data-toggle="modal" data-target="#cartmodal"><i class="fa fa-shopping-cart"></i></a>
</div>
<!-- Nav tabs -->
@endsection
@push('modals')
    <!-- Modal -->
    <div class="modal fade" id="cartmodal" tabindex="-1" role="dialog" aria-labelledby="cartmodallabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cartmodallabel">
                        <i class="fa fa-shopping-cart"></i> Cart
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-wrapper-2">
                        <!--Table-->
                        <table class="table table-responsive-md" id="carttable">
                            <thead class="mdb-color lighten-4">
                                <tr>
                                    <th>#</th>
                                    <th class="th-lg">Item</th>
                                    <th class="th-lg">Price</th>
                                    <th class="th-lg">Avg. Time(s)</th>
                                    <th class="th-lg">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <!--Table-->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="btnsubmitorder"  data-dismiss="modal">Submit Order <i class="fa fa-send" aria-hidden="true"></i></button>
                </div>
            </div>
        </div>
    </div>
@endpush
@push('scripts')
    <script>
        "use strict";
        window.itemsincart = [];
        $(".addtocard").on('click',function() {
            // if (!_.find(itemsincart, ['menu', $(this).data('menu')])) {
                // var idx = _.findLastIndex(itemsincart);
                itemsincart.push({
               "menu":$(this).data('menu'),
               "name":$(this).data('name'),
               "price":$(this).data('price'),
               "avgtime":$(this).data('avgtime'),
               "qty": 1
               });
                 toastr.success('Added '+$(this).data('name')+' to cart');
            // }
            console.log(itemsincart);
        });
        $("#mycart").on('click',function () {
            refreshingcartcontent();
        });
        
        $("#carttable").on('keyup', '.itemqtyincart',function () {
            var idx = _.findIndex(itemsincart, ['menu', $(this).data('menu')]);
            itemsincart[idx].qty = parseInt($(this).val());
            refreshingcartcontent();
            console.log(itemsincart);
        });
        function refreshingcartcontent() {
            $("#carttable>tbody").empty();
            for (var i = 0 ; i < itemsincart.length; i++) {
                
                var newrowincart = '<tr>';
                    newrowincart+= '<th scope="row">'+(i+1)+'</th>';
                    newrowincart+= '<td>'+itemsincart[i].name+'</td>';
                    //newrowincart+= '<td><input style="margin: 0;padding: 0;" class="form-control itemqtyincart" id="itemqtyincart" type="number" min="0" value="'+itemsincart[i].qty+'" data-menu="'+itemsincart[i].menu+'"></td>';
                    newrowincart+= '<td>'+itemsincart[i].price+'</td>';
                    newrowincart+= '<td>'+itemsincart[i].avgtime+'</td>';
                    newrowincart+= '<td>'+itemsincart[i].price+'</td>';
                    newrowincart+= '</tr>';
                $("#carttable>tbody").append(newrowincart);
            }
        }

        $("#btnsubmitorder").on('click',function () {
            // jom submit
            console.log(itemsincart);
            $.ajax({
                type: 'POST',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: '{{route('order.store',['tableno'=>$tableno])}}',
                data: JSON.stringify(itemsincart),
                contentType: "application/json",
                success: function (data) { 
                    if (data.status == 'success') {
                        toastr.success(data.msg);    
                        window.location =  '/order/'+data.orderno+'/'+{{$tableno}};
                    }else if(data.status == 'error'){
                        toastr.error(data.msg);
                    }
                }
            }); 

        });
    </script>
@endpush
