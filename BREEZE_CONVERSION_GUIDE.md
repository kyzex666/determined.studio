# Laravel Breeze Conversion Guide

## Status Konversi ke Breeze Tailwind

### ✅ Completed (3 files)
- `resources/views/layouts/app.blade.php` - Main layout dengan Tailwind & @vite
- `resources/views/layouts/navigation.blade.php` - Role-based navigation component
- `resources/views/admin/dashboard.blade.php` - Admin dashboard
- `resources/views/gudang/dashboard.blade.php` - Gudang dashboard  
- `resources/views/admin/vendors/index.blade.php` - Vendors list page

### 🔄 Pattern untuk Konversi Cepat

#### 1. **Index/List Pages** (15+ files)
Pola Bootstrap → Tailwind:

**Bootstrap:**
```blade
@extends('layouts.admin')
@section('content')
<div class="card">
    <table class="table table-hover">
        <tr>
            <td>
                <a href="" class="btn btn-info"><i class="bi bi-eye"></i></a>
```

**Tailwind (Pattern):**
```blade
<x-app-layout>
    <x-slot name="header">...</x-slot>
    <div class="py-12">
        <div class="bg-white rounded-lg shadow">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <tr class="hover:bg-gray-50">
                        <td><a href="" class="text-blue-600">👁️</a></td>
```

#### 2. **Create/Edit Form Pages** (15+ files)
Gunakan Breeze components:

**Mapping komponen:**
- `form-group` → `<x-input-label>` + `<x-text-input>`
- `form-control` → `<x-text-input>`
- `btn btn-primary` → `<x-primary-button>`
- `form-select` → `<select class="border rounded">`

Contoh:
```blade
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Tambah Vendor</h2>
    </x-slot>
    <div class="py-12">
        <form method="POST" action="{{ route(...) }}">
            @csrf
            <x-input-label for="name">Nama</x-input-label>
            <x-text-input id="name" name="name" value="{{ old('name') }}"/>
            <x-input-error for="name"/>
```

#### 3. **Show/Detail Pages** (10+ files)
Konversi ke card grid layout:

```blade
<x-app-layout>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-4 rounded shadow">
            <label class="text-gray-600 text-sm">Kode</label>
            <div class="font-bold text-lg">{{ $item->code }}</div>
        </div>
```

### 📋 File Priority untuk Konversi

**Tier 1 (Critical - gunakan dulu):**
- ✅ Admin Vendors: index, create, edit, show (1 done, 3 pending)
- Material, Products: index, create, edit, show (8 files)

**Tier 2 (Important):**
- Gudang Material In/Out/Product In (9 files)
- Produksi: Production Orders, Results (8 files)

**Tier 3 (Optional):**
- Admin Reports, Logs
- User management pages

### 🔧 Quick Conversion Checklist per File

- [ ] Change @extends() → use <x-app-layout>
- [ ] Remove @section('content') / @endsection
- [ ] Replace card div.card → div.bg-white rounded-lg shadow
- [ ] Replace table classes:
  - table table-hover → w-full
  - th → px-6 py-3 text-left font-medium text-gray-700
  - td → px-6 py-4 text-gray-900
- [ ] Replace buttons:
  - btn btn-primary → bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded
  - btn btn-danger → bg-red-600 text-white
- [ ] Replace form inputs with <x-text-input>, <x-select>
- [ ] Replace icons (bi bi-*) dengan emoji atau SVG
- [ ] Wrap footer scripts in @push('scripts')

### 💡 Reusable Breeze Components

Tersedia di `resources/views/components/`:
- x-app-layout - Main wrapper
- x-primary-button - Blue CTA button
- x-danger-button - Red destructive button
- x-input-label - Form label
- x-text-input - Text field
- x-select - Dropdown select
- x-input-error - Error message display
- x-nav-link - Navigation link
- x-dropdown - Dropdown menu

### 🚀 Accelerated Conversion Script (Optional)

Untuk mempercepat, buat PHP artisan command:
```bash
php artisan breeze:convert admin/vendors/create
php artisan breeze:convert admin/materials/index
```

Atau gunakan find/replace batch di VS Code:
1. Find: `@extends\('layouts\.(admin|gudang|produksi)'\)`
2. Replace: `<x-app-layout>`
3. Find: `@section\('content'\)(.*?)@endsection`
4. Replace: `<div class="py-12"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8">$1</div></div>`

### 📊 Current Progress

```
Layouts & Navigation: ✅✅✅ (3/3)
Dashboards: ✅✅ (2/3) 
CRUD Index: ✅ (1/12)
CRUD Create/Edit: ⏳ (0/15)
CRUD Show: ⏳ (0/10)
Forms & Modals: ⏳ (0/8)

Total: 6/54 files (11%) - Estimated 3-4 hours to complete all
```

### 🎯 Next Action Items

1. **Immediate (Next 30 min):**
   - Complete admin vendors (create, edit, show)
   - Convert admin materials index

2. **Short-term (Next 2 hours):**
   - Finish all admin CRUD pages
   - Convert gudang material-in pages

3. **Remaining (Next 2-3 hours):**
   - All gudang & produksi pages
   - Final testing & bug fixes
