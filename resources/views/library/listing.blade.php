<table class="table">
    <thead>
    <tr>
        <th scope="col">Book</th>
        <th scope="col">Authors</th>
        <th scope="col">Publishers</th>
    </tr>
    </thead>
    <tbody>
    @if(!empty($books))
        @foreach($books as $key => $book)
            <tr>
                <td>{{ $book['title'] }}</td>
                <td>{{ $book['authors'] }}</td>
                <td>{{ $book['publishers'] }}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="3" class="text-center">No Books Found!</td>
        </tr>
    @endif
    </tbody>
</table>
<nav aria-label="Page navigation example">
    <ul class="pagination justify-content-end">
        @for($i = 1; $i <= $totalPages; $i++)
            <li class="page-item">
                <button class="page-link" data-id="{{ $i }}">{{ $i }}</button>
            </li>
        @endfor
    </ul>
</nav>
