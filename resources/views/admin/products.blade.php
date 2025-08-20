{{-- @extends('admin.layout.layout')
@section('content')

<div class="container mt-4">
    <h2>Product List</h2>
    
    <!-- Add Product Button -->
    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addProductModal">
        Add Product
    </button>

    <!-- Product Table -->
    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Image</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->description }}</td>
                <td>${{ $product->price }}</td>
                <td>
                    @if ($product->image)
                        <img src="{{ asset($product->image) }}" width="60" height="60">
                    @else
                        N/A
                    @endif
                  </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Name:</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Description:</label>
                    <textarea name="description" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <label>Price:</label>
                    <input type="number" step="0.01" name="price" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label>Product Image:</label>
                <input type="file" name="image" class="form-control">
            </div>
            
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Save Product</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </form>
  </div>
</div>

@endsection --}}



@extends('admin.layout.layout')

@section('content')

<div class="container mt-4">
    <h2>Product List</h2>
    
    <!-- Add Product Button -->
    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addProductModal">
        Add Product
    </button>

    <!-- Product Table -->
    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Image</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->description }}</td>
                <td>${{ $product->price }}</td>
                <td>
                    @if ($product->image)
                        <img src="{{ asset($product->image) }}" width="60" height="60">
                    @else
                        N/A
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>Name:</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Description:</label>
                    <textarea name="description" class="form-control"></textarea>
                </div>

                <div class="form-group">
                    <label>Price:</label>
                    <input type="number" step="0.01" name="price" class="form-control">
                </div>

                <div class="form-group">
                    <label>Stock Quantity:</label>
                    <input type="number" name="stock" class="form-control" placeholder="e.g. 20" required>
                </div>
                
                <div class="form-group">
                    <label>Available Colors (comma separated):</label>
                    <input type="text" name="color" class="form-control" placeholder="e.g. Red,Blue,Black">
                </div>
                <div class="form-group">
                    <label>Category:</label>
                    <select name="category_id" class="form-control" required>
                        <option value="">Select Category</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Subcategory:</label>
                    <select name="subcategory_id" class="form-control" required>
                        <option value="">Select Subcategory</option>
                        @foreach ($subcategories as $sub)
                            <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Main Image:</label>
                    <input type="file" name="image" class="form-control">
                </div>
                
                <div class="form-group">
                    <label>Thumbnail 1:</label>
                    <input type="file" name="image_1" class="form-control">
                </div>
                
                <div class="form-group">
                    <label>Thumbnail 2:</label>
                    <input type="file" name="image_2" class="form-control">
                </div>
                
                <div class="form-group">
                    <label>Thumbnail 3:</label>
                    <input type="file" name="image_3" class="form-control">
                </div>
                <div class="form-group">
                    <label>YouTube Video Link:</label>
                    <input type="text" name="youtube_link" class="form-control" placeholder="https://youtube.com/watch?v=xyz">
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Save Product</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </form>
  </div>
</div>

@endsection
