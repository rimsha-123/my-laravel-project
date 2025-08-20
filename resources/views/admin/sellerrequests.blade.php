@extends('admin.layout.layout') 

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Pending Seller Requests</h1>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-200 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if($requests->isEmpty())
        <p>No pending seller requests.</p>
    @else
        <table class="min-w-full border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2">User Name</th>
                    <th class="border px-4 py-2">Shop Name</th>
                    <th class="border px-4 py-2">Category</th>
                    <th class="border px-4 py-2">Subcategory</th>
                    <th class="border px-4 py-2">Phone</th>
                    <th class="border px-4 py-2">Address</th>
                    <th class="border px-4 py-2">Description</th>
                    <th class="border px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $req)
                    <tr>
                        <td class="border px-4 py-2">{{ $req->user->name ?? 'N/A' }}</td>
                        <td class="border px-4 py-2">{{ $req->shop_name }}</td>
                        <td class="border px-4 py-2">{{ $req->category->name ?? 'N/A' }}</td>
                        <td class="border px-4 py-2">{{ $req->subcategory->name ?? 'N/A' }}</td>
                        <td class="border px-4 py-2">{{ $req->phone }}</td>
                        <td class="border px-4 py-2">{{ $req->address }}</td>
                        <td class="border px-4 py-2">{{ $req->description }}</td>
                        <td class="border px-4 py-2 space-x-2">
                            <form method="POST" action="{{ route('sellerrequests.approve', $req->id) }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded">Approve</button>
                            </form>
                            <form method="POST" action="{{ route('sellerrequests.reject', $req->id) }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded">Reject</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
