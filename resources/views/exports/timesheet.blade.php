<table>
    <tbody>
        <tr>
            <th></th>
            @foreach ($timesheet['dates'] as $date)
            <th>{{date('m/d/Y', strtotime($date))}}</th>
            @endforeach
        </tr>
        <tr>
            <th></th>
            @foreach ($timesheet['dates'] as $date)
            <th>{{date('D', strtotime($date))}}</th>
            @endforeach
            <th>Total Hours</th>
            <th>Hourly Wage</th>
            <th>Total Wage</th>
        </tr>
        @foreach ($timesheet['ts'] as $empId => $entry)
            <tr>
                <td>{{$entry['employeeName']}}</td>

                @foreach ($entry['dates'] as $date => $duration)
                    <td>{{($duration / 3600)}}</td>
                @endforeach

                <td>{{$entry['duration'] / 3600}}</td>
                <td>{{$entry['wage']}}</td>
                <td>{{$entry['wage'] * ($entry['duration'] / 3600)}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
