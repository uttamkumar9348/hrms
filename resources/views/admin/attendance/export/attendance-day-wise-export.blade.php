<table>
    <thead>
    <tr>
        <th colspan="5" style="text-align: center"><strong>
                @if(\App\Helpers\AppHelper::ifDateInBsEnabled())
                    {{\App\Helpers\AppHelper::getFormattedNepaliDate($dayDetail['attendance_date'])}}
                @else
                {{ date('M d Y',strtotime($dayDetail['attendance_date']))}}
                @endif
                Attendance Report  </strong></th>
    </tr>
    <tr>
         <th><b>Employee Name</b></th>
        <!--<th style="text-align: center;"><b>Check In At</b></th>
        <th style="text-align: center;"><b>Check Out At</b></th>
        <th style="text-align: center;"><b>Total Worked Hours</b></th>
        <th style="text-align: center;"><b>Attendance Status</b></th> -->
        @foreach($dates as $date){
            <th style="text-align: center;"><b>{{$date}}</b></th>
        }
        @endforeach
        <th style="text-align: center;"><b>Total Worked Hours</b></th>
    </tr>
    </thead>
    <tbody>
    {{dd($holidayDates);}}
    @forelse($attendanceDayWiseRecord as $key => $value)
        <tr>
            <td>{{ ucfirst($value->user_name) }}</td>
            
            @foreach($dates as $all_date)
                @php
                    $isHoliday = in_array($all_date, $holidayDates);
                    $isWeekend = in_array($all_date, $weekends);
                @endphp
                @if ($isHoliday)
                    <td align="center">Holiday</td>
                @elseif ($isWeekend)
                    <td align="center">Week Off</td>
                @elseif ($value['check_in_at'] != null)
                    <td align="center">Present</td>
                @else
                    <td align="center">Absent</td>
                @endif
            @endforeach
            <td align="center">
                @if($value['check_out_at'])
                    {{ \App\Helpers\AttendanceHelper::getWorkedHourInHourAndMinute($value['check_in_at'], $value['check_out_at']) }}
                @endif
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="{{ count($dates) + 2 }}">
                <p class="text-center"><b>No records found!</b></p>
            </td>
        </tr>
    @endforelse
    </tbody>
</table>

