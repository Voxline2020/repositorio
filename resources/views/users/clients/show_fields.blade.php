@if (!empty($user->company_id) && isset($user->company_id))
	<div class="row p-0 m-0">
		<div class="col-md-12">
			<h2 class="font-weight-bolder">{{ $user->company->name }}</h2>
		</div>
	</div>
@else
<div class="col-md-6">
	<p><b>Compañia:</b>
		<a href="{{route('users.companies.new',[$user->id]) }}" class='btn btn-secondary btn-xs'>
			<i class="fas fa-plus"></i> Compañia</a>
	</p>
</div>
@endif
