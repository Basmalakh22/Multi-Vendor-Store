@if ($errors->any())
    <div class="alert alert-danger">
        <h3>Error Occured!</h3>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="form-group">
    <label>Category Name</label>
    <input type="text" name="name" @class([
        'form-control',
        'is-invalid' => $errors->has('name'),
    ])
    value="{{ old('name' ,$category->name) }}">
    @error('name')
    <div class="invalid-feedback">
        {{ $message }}
    </div>

    @enderror
</div>
<div class="form-group">
    <label>Category Parent</label>
    <select name="parent_id" class="form-control form-select">
        <option value="">Primary Category</option>
        @foreach ($parents as $parent)
            <option value="{{ $parent->id }}" @selected( old('parent_id' ,$category->parent_id) == $parent->id)>{{ $parent->name }}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label>Description</label>
    <textarea name="description" class="form-control">{{ old('description' ,$category->description) }}</textarea>
</div>
<div class="form-group">
    <label>Image</label>
    <input type='file' name="imge" class="form-control">
    @if ($category->imge)
        <img src="{{ asset('storage/' . $category->imge) }}" alt="" height="50">
    @endif
</div>
<div class="form-group">
    <label>Status</label>
    <div>

        <div class="form-check">
            <input class="form-check-input" type="radio" name="status" value="active" @checked( old('status'  ,$category->status) == 'active')>
            <label class="form-check-label">
                Active
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="status" value="inactive" @checked( old('status' ,$category->status) == 'inactive')>
            <label class="form-check-label">
                Archived
            </label>
        </div>
    </div>
</div>
<div class="form-group">
    <button type="submit" class="btn btn-primary">{{ $button_label ?? 'Save' }}</button>
</div>
