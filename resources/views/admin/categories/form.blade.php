{{-- File: resources/views/admin/categories/_form.blade.php --}}

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- 1. Tên danh mục --}}
    <div class="input-group">
        <label class="font-semibold block mb-1">
            Tên danh mục <span style="color: red;">*</span>
        </label>
        <input type="text" name="name" id="categoryName" placeholder="Laptop Dell XPS Like new, MacBook Pro..."
            value="{{ old('name', $category->name ?? '') }}"
            class="@error('name') border-red-500 @enderror form-control" /> <small
            class="text-gray-500 text-sm mt-1 block">Tên hiển thị cho khách hàng</small>
        @error('name')
            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
        @enderror
    </div>

    {{-- 2. Danh mục cha (BỔ SUNG QUAN TRỌNG) --}}
    <div class="input-group">
        <label class="font-semibold block mb-1">Danh mục cha</label>
        <select name="parent_id" class="form-select">
            <option value="">-- Là danh mục gốc (Không có cha) --</option>
            @if (isset($parents))
                @foreach ($parents as $parent)
                    <option value="{{ $parent->id }}" {{-- Logic chọn: Nếu đang sửa thì check DB, nếu lỗi form thì check old --}}
                        {{ old('parent_id', $category->parent_id ?? '') == $parent->id ? 'selected' : '' }}>
                        {{ $parent->name }}
                    </option>
                @endforeach
            @endif
        </select>
        <small class="text-gray-500 text-sm mt-1 block">Để trống nếu đây là danh mục cấp cao nhất</small>
    </div>

    {{-- 3. Slug --}}
    <div class="input-group">
        <label class="font-semibold">Slug URL <span class="text-muted">(Tùy chọn)</span></label>
        <input type="text" name="slug" id="slug" value="{{ old('slug', $category->slug ?? '') }}"
            class="@error('slug') border-red-500 @enderror" placeholder="laptop-dell-xps-like-new" />
        <small class="text-gray-500 mt-1">Để trống để tự động tạo từ tên danh mục</small>
    </div>

    {{-- 4. Icon --}}
    <div class="input-group">
        <label class="font-semibold">Icon (Font Awesome)</label>
        <div style="display: flex; gap: 10px; align-items: center;">
            <input type="text" name="icon" id="iconInput" value="{{ old('icon', $category->icon ?? '') }}"
                placeholder="fas fa-laptop" style="flex: 1;" />
            <div id="iconPreview" class="icon-preview">
                <i class="{{ $category->icon ?? 'fas fa-laptop' }}"></i>
            </div>
        </div>
        <div class="icon-help">
            <small>Ví dụ: <code>fas fa-laptop</code>. Tìm tại: <a href="https://fontawesome.com/icons"
                    target="_blank">fontawesome.com</a></small>
        </div>
    </div>

    {{-- 5. Thứ tự --}}
    <div class="input-group">
        <label class="font-semibold">Thứ tự hiển thị</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}"
            min="0" placeholder="0" />
    </div>

    {{-- 6. Nổi bật --}}
    <div class="input-group">
        <label class="font-semibold">Danh mục nổi bật</label>
        <select name="is_featured">
            <option value="0" {{ old('is_featured', $category->is_featured ?? 0) == 0 ? 'selected' : '' }}>Không
            </option>
            <option value="1" {{ old('is_featured', $category->is_featured ?? 0) == 1 ? 'selected' : '' }}>Có
                (Hiện trang chủ)</option>
        </select>
    </div>

    {{-- 7. Trạng thái --}}
    <div class="input-group">
        <label class="font-semibold">Trạng thái</label>
        <select name="status">
            <option value="1" {{ old('status', $category->status ?? 1) == 1 ? 'selected' : '' }}>Kích hoạt
            </option>
            <option value="0" {{ old('status', $category->status ?? 1) == 0 ? 'selected' : '' }}>Ẩn</option>
        </select>
    </div>

    {{-- 8. Mô tả --}}
    <div class="input-group col-span-1 md:col-span-2">
        <label class="font-semibold">Mô tả SEO</label>
        <textarea name="description" rows="4">{{ old('description', $category->description ?? '') }}</textarea>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Preview icon
            const iconInput = document.getElementById('iconInput');
            const iconPreview = document.getElementById('iconPreview');

            if (iconInput && iconPreview) {
                iconInput.addEventListener('input', function() {
                    const val = this.value.trim();
                    iconPreview.innerHTML = val ? `<i class="${val} text-4xl"></i>` :
                        '<i class="fas fa-laptop text-4xl text-gray-400"></i>';
                });
            }
        });
    </script>
@endpush
