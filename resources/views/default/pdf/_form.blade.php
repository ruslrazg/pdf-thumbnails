<div class="col-md-6 offset-md-3 mt-5">
   	<a href="{{ route('index')}}" class="btn btn-primary my-1">Back to main page</a>
   	<h1>HTML Form with File Upload</h1>
	@if ($errors->any())
	    <div class="alert alert-danger">
	        <ul>
	            @foreach ($errors->all() as $error)
	                <li>{{ $error }}</li>
	            @endforeach
	        </ul>
	    </div>
	@endif
   	<form accept-charset="UTF-8" action="{{ route('store')}}" method="POST" enctype="multipart/form-data">
	   @csrf
	 <div class="form-group">
	   <label for="exampleInputName">Short file name</label>
	   <input type="text" name="description" class="form-control" id="exampleInputName" placeholder="Enter short description">
	 </div>
	 <hr>
	 <div class="form-group mt-3">
	   <label class="mr-2">Upload your file:</label>
	   <input type="file" name="file">
	 </div>
	 <hr>
	 <button type="submit" class="btn btn-primary">Submit</button>
   	</form>
</div>
