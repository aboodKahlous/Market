@extends('layouts.admin')

@section('content')
<div class="container-fluid">
  
</div>
@endsection


@push('js')
<script>
$(document).ready(function(){
  $('#dataTable').dataTable({
  processing:true,
  serverSide:true,
  ajax:"{{route('flashSale.all')}}",
    columns:[
      {data:'select',orderable:false,searchable:false},
      {data:'product_title',orderable:false},
      {data:'product_price'},
      {data:'flash_price'},
      {data:'created_at'},
      {data:'action'},
    ]
  });

	$('.selectall').click(function(){
		$('.selectbox').prop('checked', $(this).prop('checked'));
	})

	$('.selectbox').change(function(){
		var total = $('.selectbox').length;
		var number = $('.selectbox:checked').length;
		if(total == number){
			$('.selectall').prop('checked', true);
		} else{
			$('.selectall').prop('checked', false);
		}
	})

  $('#showSelected').click(function(){
    if($('.selectbox:checked').length < 1){
      alert('Please select atleast one of the row!');
      return;
    }else{
      $('#selectorForm').submit();
    }
  })

})

</script>
@endpush