<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Availability</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Set Weekly Availability</h2>
        <form id="weekly-availability-form">
            <div class="form-group">
                <label for="profile_id">Profile</label>
                <select class="form-control" id="profile_id" name="profile_id" required>
                    <!-- Options will be populated dynamically -->
                </select>
            </div>
            <div id="availability-container">
                <div class="form-row">
                    <div class="col">
                        <label for="day_of_week">Day of Week</label>
                        <select class="form-control" name="availabilities[0][day_of_week]" required>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                            <option value="Sunday">Sunday</option>
                        </select>
                    </div>
                    <div class="col">
                        <label for="start_time">Start Time</label>
                        <input type="time" class="form-control" name="availabilities[0][start_time]" required>
                    </div>
                    <div class="col">
                        <label for="end_time">End Time</label>
                        <input type="time" class="form-control" name="availabilities[0][end_time]" required>
                    </div>
                </div>
            </div>
            <button type="button" id="add-availability" class="btn btn-secondary mt-3">Add More</button>
            <button type="submit" class="btn btn-primary mt-3">Save Availability</button>
        </form>

        <h2 class="mt-5">Set Override Availability</h2>
        <form id="override-availability-form">
            <div class="form-group">
                <label for="profile_id_override">Profile</label>
                <select class="form-control" id="profile_id_override" name="profile_id" required>
                    <!-- Options will be populated dynamically -->
                </select>
            </div>
            <div class="form-row">
                <div class="col">
                    <label for="date">Date</label>
                    <input type="date" class="form-control" name="date" required>
                </div>
                <div class="col">
                    <label for="start_time">Start Time</label>
                    <input type="time" class="form-control" name="start_time">
                </div>
                <div class="col">
                    <label for="end_time">End Time</label>
                    <input type="time" class="form-control" name="end_time">
                </div>
                <div class="col">
                    <label for="is_available">Is Available</label>
                    <select class="form-control" name="is_available">
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Save Override</button>
        </form>

        <h2 class="mt-5">Current Weekly Availability</h2>
        <div id="current-weekly-availability"></div>

        <h2 class="mt-5">Current Date Overrides</h2>
        <div id="current-date-overrides"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function () {
            let availabilityIndex = 1;

            // Fetch profiles and populate the dropdowns
            $.getJSON('/api/profiles', function (data) {
                let profileOptions = '';
                data.forEach(function (profile) {
                    profileOptions += `<option value="${profile.id}">${profile.name}</option>`;
                });
                $('#profile_id, #profile_id_override').html(profileOptions);
            });

            // Fetch and display current weekly availability
            function fetchWeeklyAvailability(profileId) {
                $.getJSON(`/availability/weekly/${profileId}`, function (data) {
                    let html = '<ul class="list-group">';
                    data.forEach(function (availability) {
                        html += `<li class="list-group-item">${availability.day_of_week}: ${availability.start_time} - ${availability.end_time}</li>`;
                    });
                    html += '</ul>';
                    $('#current-weekly-availability').html(html);
                });
            }

            // Fetch and display current date overrides
            function fetchDateOverrides(profileId) {
                $.getJSON(`/availability/override/${profileId}`, function (data) {
                    let html = '<ul class="list-group">';
                    data.forEach(function (override) {
                        html += `<li class="list-group-item">${override.date}: ${override.start_time} - ${override.end_time} (${override.is_available ? 'Available' : 'Not Available'})</li>`;
                    });
                    html += '</ul>';
                    $('#current-date-overrides').html(html);
                });
            }

            // Add more availability fields
            $('#add-availability').click(function () {
                const availabilityFields = `
                    <div class="form-row mt-3">
                        <div class="col">
                            <select class="form-control" name="availabilities[${availabilityIndex}][day_of_week]" required>
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                                <option value="Saturday">Saturday</option>
                                <option value="Sunday">Sunday</option>
                            </select>
                        </div>
                        <div class="col">
                            <input type="time" class="form-control" name="availabilities[${availabilityIndex}][start_time]" required>
                        </div>
                        <div class="col">
                            <input type="time" class="form-control" name="availabilities[${availabilityIndex}][end_time]" required>
                        </div>
                    </div>`;
                $('#availability-container').append(availabilityFields);
                availabilityIndex++;
            });

            // Submit weekly availability form
            $('#weekly-availability-form').submit(function (event) {
                event.preventDefault();
                const profileId = $('#profile_id').val();
                $.ajax({
                    type: 'POST',
                    url: '/availability/weekly',
                    data: $(this).serialize(),
                    success: function (response) {
                        alert('Weekly availability saved successfully!');
                        $('#weekly-availability-form')[0].reset();
                        fetchWeeklyAvailability(profileId);
                    },
                    error: function (error) {
                        alert('Failed to save weekly availability.');
                    }
                });
            });

            // Submit override availability form
            $('#override-availability-form').submit(function (event) {
                event.preventDefault();
                const profileId = $('#profile_id_override').val();
                $.ajax({
                    type: 'POST',
                    url: '/availability/override',
                    data: $(this).serialize(),
                    success: function (response) {
                        alert('Override availability saved successfully!');
                        $('#override-availability-form')[0].reset();
                        fetchDateOverrides(profileId);
                    },
                    error: function (error) {
                        alert('Failed to save override availability.');
                    }
                });
            });

            // Change profile dropdown to fetch and display current availability
            $('#profile_id, #profile_id_override').change(function () {
                const profileId = $(this).val();
                fetchWeeklyAvailability(profileId);
                fetchDateOverrides(profileId);
            });
        });
    </script>
</body>
</html>
