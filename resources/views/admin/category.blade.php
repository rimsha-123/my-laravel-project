<h2>Add Category</h2>
@if(session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif

<form method="POST" action="{{ route('category.store') }}">
    @csrf
    <input type="text" name="name" placeholder="Category Name" required>
    <button type="submit">Add</button>
</form>
