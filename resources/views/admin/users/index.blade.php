@extends('layouts.dashboard')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-3xl border border-stone-200/80 shadow-soft">
        <div>
            <h1 class="text-xl font-bold text-stone-900">Daftar Pengguna Platform</h1>
            <p class="text-xs text-stone-500 mt-0.5">Pantau dan kelola seluruh akun Customer, Member, Reseller, dan Administrator.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-stone-200/80 shadow-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-stone-600">
                <thead class="bg-stone-50 text-stone-700 uppercase font-bold text-[10px] tracking-wider border-b border-stone-100">
                    <tr>
                        <th class="py-3.5 px-5">ID</th>
                        <th class="py-3.5 px-5">Pengguna</th>
                        <th class="py-3.5 px-5">Kontak</th>
                        <th class="py-3.5 px-5">Role</th>
                        <th class="py-3.5 px-5">Status</th>
                        <th class="py-3.5 px-5">Bergabung</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($users as $u)
                        <tr class="hover:bg-stone-50/50">
                            <td class="py-3.5 px-5 font-mono text-stone-400">#{{ $u->id }}</td>
                            <td class="py-3.5 px-5">
                                <p class="font-bold text-stone-900 text-sm">{{ $u->name }}</p>
                                <p class="text-[11px] text-stone-400">{{ $u->email }}</p>
                            </td>
                            <td class="py-3.5 px-5 text-stone-700 font-mono">{{ $u->phone ?? '-' }}</td>
                            <td class="py-3.5 px-5">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $u->role->badgeColor() }}">
                                    {{ $u->role->label() }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $u->status->value === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $u->status->label() }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5 text-stone-400">{{ $u->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-stone-400">Belum ada pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="p-4 border-t border-stone-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
