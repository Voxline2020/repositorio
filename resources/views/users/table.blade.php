<div class="table-responsive">
	<table class="table" id="users-table">
		<thead class="thead-dark">
			<tr>
				<th>Nombre</th>
				<th>Email</th>
				<th>Estado</th>
				<th>Rol</th>
				<th>Empresa</th>
				<th colspan="3">Accion</th>
			</tr>
		</thead>
		<tbody>
			@foreach($users as $user)
			<tr>
				<td>{!! $user->fullName !!}</td>
				<td>{!! $user->email !!}</td>
				<td>{!! $user->stateName !!}</td>
				<td>
					@if ($user->getRoleNames()->count()!=0)
						@if ($user->role == "Administrador")
						<a href="#" class='btn btn-danger'>{{ $user->role }}</a>
						@else
						{!! Form::open(['route' => ['users.roles.unassign',$user->id],'method'=>'delete'] ) !!}
						{!! Form::button($user->role.' <i class="fas fa-times-circle"></i>', ['type' => 'submit', 'name'=>'role_id',
						'value'=>$user->role, 'class' => 'btn btn-danger', 'onclick' => "return confirm('Esta seguro de que desea
						eliminar?')"]) !!}
						{!! Form::close() !!}
						@endif

					@else
					<a href="{{route('users.roles.new',[$user->id]) }}" class='btn btn-secondary btn-xs'><i
							class="fas fa-plus"></i> Rol</a>
					@endif
				</td>
				<td>
					@if ($user->company_id != null)
					{!! Form::open(['route' => ['users.companies.unassign',$user->id],'method'=>'delete'] ) !!}
						@foreach ($companies as $company)
							@if($company->id == $user->company_id)
							{!! Form::button($company->name.' <i class="fas fa-times-circle"></i>', ['type' => 'submit', 'name'=>'id',
							'value'=>$user->company_id, 'class' => 'btn btn-danger', 'onclick' => "return confirm('Esta seguro de que desea
							eliminar?')"]) !!}
							{!! Form::close() !!}
							@endif
						@endforeach
					@else
						@if ($user->role == "Administrador")
						<a href="#" class='btn btn-secondary disabled'><i class="fas fa-times"></i></a>
						@else
						<a href="{{route('users.companies.new',[$user->id]) }}" class='btn btn-secondary'>
						<i class="fas fa-plus"></i></a>
						@endif
					@endif
				</td>
				<td>
					{!! Form::open(['route' => ['users.destroy', $user->id], 'method' => 'delete']) !!}
					<div class='btn-group'>
						<a href="{{route('users.show',[$user->id]) }}" class='btn btn-primary btn-xs'><i class="fas fa-eye"></i></a>
						<a href="{{route('users.edit',[$user->id]) }}" class='btn btn-warning btn-xs'><i
								class="fas fa-edit"></i></a>
						{!! Form::button('<i class="fas fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'onclick'
						=> "return confirm('Esta seguro de que desea eliminar?')"]) !!}

					</div>
					{!! Form::close() !!}

				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	{{$users->links()}}
</div>
