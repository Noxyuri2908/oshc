<table class="table table-striped table-bordered table-hover dataTables-example">
	<thead>
		<tr>
			<th class="no-sort check-all-table text-center"><input type="checkbox" id="master"></th>
			<th class="text-center">Dịch vụ</th>
			<th class="text-center">Loại dịch vụ</th>
			<th class="text-center">Số tháng</th>
			<th class="text-center">Giá</th>
			<th class="text-center">Trạng Thái</th>
			<th class="text-center">Action</th>
		</tr>
	</thead>
	<tbody>
		@foreach($data as $obj)
		<tr>
			<td class="text-center"><input type="checkbox" class="sub_chk" data-id="{{$obj->id}}"></td>
			<td class="text-center">{{$obj->Service->name}}</td>
			<td class="text-center">
				@if ($obj->type == 1)
				<span class="label label-success">Single</span></a>
				@elseif ($obj->type == 2)
				<span class="label label-danger">Couple</span></a>
				@elseif ($obj->type == 3)
				<span class="label label-danger">Family</span></a>
				@endif
			</td>
			<td class="text-center">{{ $obj->num_month }}</td>
			<td class="text-center">{{ $obj->price }}</td>
			<td class="text-center">
				@if ($obj->status == 1)
				<span class="label label-success">Đang sử dụng</span></a>
				@else
				<span class="label label-danger">Ngừng sử dụng</span></a>
				@endif
			</td>
			<td class="text-center">
				<a href="{{route('price.edit', ['id'=>$obj->id])}}" class="btn btn-warning btn-circle"><i class="fa fa-edit"></i></a>
				<a 	class="btn btn-danger btn-circle btn-sm delete-button" 
					data-action ="{{ route('price.destroy',$obj->id) }}" type="button">
					<i class="fa fa-trash"></i>
				</a>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>