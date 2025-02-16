<table id="dataTableMembers" class="table align-items-center mb-0 table-striped table-bordered table-hover">
    <thead class="thead-dark">
        <tr>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nama</th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Email</th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Jenis Kelamin</th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Alamat</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($members as $member)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td class="text-left">{{ $member->user->name }}</td>
                <td class="text-left">{{ $member->user->email }}</td>
                <td class="text-left">{{ $member->gender }}</td>
                <td class="text-left">{{ Str::limit($member->alamat, 15, '...') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<div class="d-flex justify-content-center mt-3">
    {{ $members->links() }}
</div>
