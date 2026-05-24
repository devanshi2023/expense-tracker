<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Category</th>
                <th>Spent / Limit</th>
                <th>Usage</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>
                    <td>{{ $row['category']->name }}</td>
                    <td>{{ number_format($row['spent'], 2) }} / {{ number_format($row['limit'], 2) }}</td>
                    <td>
                        <div class="progress">
                            <span style="width: {{ min($row['percent'], 100) }}%"></span>
                        </div>
                        {{ $row['percent'] }}%
                    </td>
                    <td>
                        @if ($row['is_over_limit'])
                            <span class="badge rejected">Over limit</span>
                        @else
                            <span class="badge approved">Within limit</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="empty-state">No categories found for this month.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
