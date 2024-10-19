@extends('layouts.dashboard')

@section('title', 'Trashed Roles')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">Roles</li>
    <li class="breadcrumb-item active">Trash</li>

@endsection
@section('content')
    <div class="bm-5">
        <a href="{{ route('dashboard.roles.index') }}" class="btn btn-sm btn-outline-primary">Back</a>
    </div>

    <x-alert />
    <br>

    <form action="{{ URL::current() }}" method="get" class="d-flex justify-content-between mb-4">
        <x-form.input name="name" placeholder="Name" class="mx-2" :value="request('name')" />
        <select name="status" class="form-control mx-2">
            <option value="">All</option>
            <option value="active" @selected(request('status') == 'active')>Active</option>
            <option value="inactive" @selected(request('status') == 'inactive')>Archived</option>
        </select>
        <button class="btn btn-dark mx-2">Filter</button>
    </form>


    <table class="table">
        <thead>
            <tr>
                <th></th>
                <th>ID</th>
                <th>Name</th>
                <th>Status</th>
                <th>Daleted At</th>
                <th colspan="2"></th>
            </tr>
        </thead>
        <tbody>

            @forelse ($roles as $role)
                <tr>
                    <td><img src="{{ asset('storage/' . $role->imge) }}" alt="" height="50"></td>
                    <td>{{ $role->id }}</td>
                    <td>{{ $role->name }}</td>
                    <td>{{ $role->status }}</td>
                    <td>{{ $role->deleted_at }}</td>

                    <td>

                        <form action="{{ route('dashboard.roles.restore', $role->id) }}" method="post">
                            @csrf
                            @method('put')
                            <button type="submit" class="btn btn-sm btn-outline-info">Restore</button>
                        </form>

                    </td>

                    <td>

                        <form action="{{ route('dashboard.roles.force-delete', $role->id) }}" method="post">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>

                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No roles defined.</td>
                </tr>
            @endforelse

        </tbody>
        </thead>
    </table>
    {{ $roles->withQueryString()->appends(['search' => 1])->links() }}

@endsection
