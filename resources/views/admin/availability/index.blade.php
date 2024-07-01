@extends('layouts.app')
@section('title', 'Set Profile Availability')

@section('content')
    <div class="container mt-5">
        {!! html()->form('POST', route('availabilites.store'))->class('form-horizontal')->open() !!}
        <fieldset class="border p-4">
            <legend class="w-auto">Set Seller Weekly Availabilites</legend>

            <div class="my-3">
                {!! html()->label('Profile')->class('form-label required')->for('profile_id') !!}
                {!! html()->select('profile_id')->options($profiles)->class('form-control')->placeholder('Select Profile') !!}
            </div>

            <table class="table table-striped table-sm availability-table">
                <tr>
                    <th>Day Of Week</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Action</th>
                </tr>
                <tr class="availability-row">
                    <td>
                        {!! html()->select('day_of_week[]')->options([
                                'Monday' => 'Monday',
                                'Tuesday' => 'Tuesday',
                                'Wednesday' => 'Wednesday',
                                'Thursday' => 'Thursday',
                                'Friday' => 'Friday',
                                'Saturday' => 'Saturday',
                                'Sunday' => 'Sunday',
                            ])->class('form-control day-of-week')->placeholder('Select Day') !!}
                    </td>
                    <td>
                        {!! html()->time('start_time[]')->class('form-control timepicker') !!}
                    </td>
                    <td>
                        {!! html()->time('end_time[]')->class('form-control timepicker') !!}
                    </td>
                    <td>
                        <label class="btn btn-primary btn-sm add-more"><i class="bi bi-plus-circle"></i></label>
                    </td>
                </tr>
            </table>
            <button class="float-end btn btn-primary">Save</button>
        </fieldset>
        {!! html()->form()->close() !!}
    </div>
@endsection

@section('footer-script')
    <script>
        /********************************
        ADD MORE WEEKLY AVAILABILITIES
        *******************************/
        $(document).on('click', '.add-more', function() {
            let row = $('.availability-row').eq(0).clone();
            let rowIndex = $('.availability-row').length;
            if (rowIndex > 6) {
                alert("Maximum 7 row allowed!");
                return false;
            }
            row.find('.add-more')
                .removeClass('add-more btn-primary')
                .addClass('btn-danger remove')
                .html('<i class="bi bi-dash-circle"></i>');

            row.find('input,select').each(function(i, input) {
                $(input).val('');
            });
            $('.availability-table').append(row);
        });

        $(document).on('click', '.remove', function() {
            $(this).parent().parent().remove();
        });

        /****************************************
        UINIQ DAY OF WEEK SCRIPTING START HERE
        *****************************************/
        $(document).on("change", ".day-of-week", function() {
            let parentHtml = $(this).parent().parent().parent().parent().parent();
            let weekDay = $(this).val();
            let totalCount = 0;
            parentHtml.find('.day-of-week').each(function(i, input) {
                if (input.value == weekDay) {
                    totalCount++;
                }
            });

            if (totalCount > 1) {
                alert('Please select uniq day')
                $(this).val('');
            }
        });
    </script>
@endsection
