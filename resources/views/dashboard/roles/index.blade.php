@extends('layouts.dashboard')

@section('title', 'Roles')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Roles</li>
@endsection

@section('content')

    <div class="mb-5">
        <a href="{{ route('dashboard.roles.create') }}" class="btn btn-sm btn-outline-primary mr-2">Create</a>

    </div>

    <x-alert type="success" />
    <x-alert type="info" />


    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Created At</th>
                <th colspan="2"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($roles as $role)
                <tr>
                    <t>{{ $role->id }}</t d>
                    <td><a href="{{ route('dashboard.roles.show', $role->id) }}">{{ $role->name }}</a></td>
                    <td>{{ $role->created_at }}</td>
                    <td>
                        <a href="{{ route('dashboard.roles.edit', $role->id) }}"
                            class="btn btn-sm btn-outline-success">Edit</a>
                    </td>
                    <td>
                        <form action="{{ route('dashboard.roles.destroy', $role->id) }}" method="post">
                            @csrf
                            <!-- Form Method Spoofing -->
                            <input type="hidden" name="_method" value="delete">
                            @method('delete')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No roles defined.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $roles->withQueryString()->links() }}

@endsection
