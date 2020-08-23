<section class="jumbotron text-center">
  <div class="container">
	  @if ($message = Session::get('success'))
	  <div class="alert alert-success alert-block">
	  	<button type="button" class="close" data-dismiss="alert">×</button>
	          <strong>{{ $message }}</strong>
	  </div>
	  @endif
	  @if ($message = Session::get('error'))
	  <div class="alert alert-danger alert-block">
	  	<button type="button" class="close" data-dismiss="alert">×</button>
	          <strong>{{ $message }}</strong>
	  </div>
	  @endif
	<h1>{{ $text }}</h1>
	<p>
	  <a href="{{ route('create') }}" class="btn btn-primary my-2">add new document</a>
	</p>
  </div>
</section>
@if ($pdfs != FALSE )
<div class="album py-5 bg-light">
	<div class="container">
		<div class="row">
			@foreach ($pdfs as $pdf)
			<div class="col-md-3">
			  <div class="card mb-3 shadow-sm">
				<img src="{{ asset($pdf->image) }}" class="img-thumbnail" alt="{{ $pdf->filename }}">
				<div class="card-body">
				  <p class="card-text">{{ $pdf->description }}</p>
				  <div class="d-flex justify-content-between align-items-center">
					<div class="btn-group">
					  <a href="{{ route('show', $pdf->id) }}" class="btn btn-sm btn-outline-secondary" role="button">View</a>
					 </div>
					<small class="text-muted">{{ $pdf->size }} Mb</small>
				  </div>
				</div>
			  </div>
			</div>
			@endforeach
		</div>
	</div>
</div>

@endif
