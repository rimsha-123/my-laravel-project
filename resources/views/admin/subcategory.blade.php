<h2>Add Subcategory</h2>
@if(session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif

<form method="POST" action="{{ route('subcategory.store') }}">
    @csrf
    <input type="text" name="name" placeholder="Subcategory Name" required>

    <select name="category_id" required>
        <option value="">Select Category</option>
        @foreach ($categories as $cat)
            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
        @endforeach
    </select>

    <button type="submit">Add</button>
</form>
