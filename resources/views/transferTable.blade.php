<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Nama</th>
    </tr>
    </thead>
    <tbody>
        @foreach ($transfer as $transfers)
            <tr>
            <td>{{$transfers->id}}</td>
            <td>{{$transfers->name}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
