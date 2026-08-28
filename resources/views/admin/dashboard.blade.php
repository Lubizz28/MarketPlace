@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Admin Header -->
    <div class="bg-gradient-to-r from-purple-950 via-purple-900 to-indigo-900 rounded-3xl p-6 sm:p-8 text-white shadow-card relative overflow-hidden">
        <div class="relative z-10 space-y-2">
            <span class="inline-block text-xs font-bold px-3 py-1 bg-purple-700/80 rounded-full text-purple-100 backdrop-blur">
                Admin Control Center
            </span>
            <h1 class="text-2xl sm:text-3xl font-serif font-bold tracking-tight">Pusat Kendali Administrator</h1>
            <p class="text-purple-100/90 text-sm max-w-xl">Ringkasan aktivitas platform marketplace, verifikasi kemitraan reseller, dan manajemen akun pengguna.</p>
        </div>
        <div class="absolute -right-8 -bottom-8 w-48 h-48 rounded-full bg-purple-600/20 blur-3xl pointer-events-none"></div>
    </div>

    <!-- Admin Overview Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-3xl border border-stone-200/80 shadow-soft">
            <span class="text-xs font-bold uppercase text-stone-500 tracking-wider">Total Pengguna</span>
            <p class="text-2xl font-bold text-stone-900 mt-2">{{ $metrics['total_users'] }}</p>
            <span class="text-xs text-stone-400">Terdaftar di sistem</span>
        </div>
        <div class="bg-white p-5 rounded-3xl border border-stone-200/80 shadow-soft">
            <span class="text-xs font-bold uppercase text-stone-500 tracking-wider">Member Belanja</span>
            <p class="text-2xl font-bold text-blue-700 mt-2">{{ $metrics['total_members'] }}</p>
            <span class="text-xs text-stone-400">Pelanggan aktif</span>
        </div>
        <div class="bg-white p-5 rounded-3xl border border-stone-200/80 shadow-soft">
            <span class="text-xs font-bold uppercase text-stone-500 tracking-wider">Mitra Reseller</span>
            <p class="text-2xl font-bold text-emerald-700 mt-2">{{ $metrics['total_resellers'] }}</p>
            <span class="text-xs text-stone-400">Mitra afiliasi</span>
        </div>
        <div class="bg-white p-5 rounded-3xl border border-stone-200/80 shadow-soft">
            <span class="text-xs font-bold uppercase text-stone-500 tracking-wider">Staff Admin</span>
            <p class="text-2xl font-bold text-purple-700 mt-2">{{ $metrics['total_admins'] }}</p>
            <span class="text-xs text-stone-400">Pengelola platform</span>
        </div>
    </div>

    <!-- Latest Registered Users -->
    <div class="bg-white p-6 rounded-3xl border border-stone-200/80 shadow-soft space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-stone-100">
            <h2 class="font-bold text-stone-900 text-base">Pengguna Terbaru</h2>
            <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-purple-800 hover:underline">Lihat Semua Pengguna &rarr;</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-stone-600">
                <thead class="bg-stone-50 text-stone-700 uppercase font-bold text-[10px] tracking-wider border-b border-stone-100">
                    <tr>
                        <th class="py-3 px-4">Nama</th>
                        <th class="py-3 px-4">Email</th>
                        <th class="py-3 px-4">Role</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Terdaftar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @foreach($latestUsers as $u)
                        <tr class="hover:bg-stone-50/50">
                            <td class="py-3 px-4 font-semibold text-stone-900">{{ $u->name }}</td>
                            <td class="py-3 px-4">{{ $u->email }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $u->role->badgeColor() }}">
                                    {{ $u->role->label() }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $u->status->value === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $u->status->label() }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-stone-400">{{ $u->created_at->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
