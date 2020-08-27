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
	  <a href="{{ route('pdfs.create') }}" class="btn btn-primary my-2">add new document</a>
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
                <!-- Trigger the modal with a button -->
				<img src="{{ asset($pdf->image) }}" data-toggle="modal" data-target="#myModal-{{$pdf->id}}" class="img-thumbnail" alt="{{ $pdf->filename }}">
				<div class="card-body">
				  <p class="card-text">{{ $pdf->description }}</p>
				  <div class="d-flex justify-content-between align-items-center">
					<div class="btn-group">
                        <form action="{{ route('pdfs.destroy', $pdf->id) }}" method="post">
                            <input class="btn btn-sm btn-outline-secondary" type="submit" value="Del" />
                            @method('delete')

                            @csrf

                        </form>
					 </div>
					<small class="text-muted">{{ $pdf->size }} Mb</small>
				  </div>
				</div>
			  </div>
			</div>
            <!-- Modal -->
            <div id="myModal-{{$pdf->id}}" class="modal fade" role="dialog">
                <div class="modal-dialog modal-lg">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">{{$pdf->description}}</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <embed src="{{ asset($pdf->hash) }}" frameborder="0" width="100%" height="400px">
                        </div>
                    </div>
                </div>
            </div>
 			@endforeach

		</div>
        {{ $pdfs->links() }}
	</div>

</div>

@endif
