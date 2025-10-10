@extends('admin.layouts.app')
@section('title','Aduan Pengguna')

@section('content')
<div class="container py-3">
    <h3 class="mb-3">📩 Aduan Pengguna</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Subjek</th>
                        <th>Pesan</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contacts as $c)
                    <tr class="{{ $c->read_at ? '' : 'table-warning' }}">
                        <td>{{ $loop->iteration + ($contacts->currentPage()-1)*$contacts->perPage() }}</td>
                        <td>{{ $c->name }}</td>
                        <td>{{ $c->email }}</td>
                        <td>{{ $c->subject }}</td>
                        <td style="max-width:280px; white-space:nowrap; text-overflow:ellipsis; overflow:hidden;">{{ $c->message }}</td>
                        <td>{{ $c->created_at->format('d M Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.contacts.show', $c->id) }}" class="btn btn-sm btn-primary">Lihat</a>
                            <form action="{{ route('admin.contacts.destroy', $c->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus aduan ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4">Belum ada aduan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $contacts->links() }}
        </div>
    </div>
</div>
@endsection
