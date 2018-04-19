# Registration (Create Acount)


[] nativates to /register.php
[] enters email and password
[] submits to /register-results.php
[] user record is created
[] confirmation email is sent
[] user clicks link in confirmation email
[] account confirmed -> redirect to success page
[] sessions starts

# Create Appointment

_that user_
[] navigates to /appointments.php -> sees "You have no appointments"
[] click "Create Appointment" -> redirected to form
[] enters appointment time and date

# Logout

_that user_
[] clicks "Logout"
[] session ends

# Login

_that user_
[] user nativates to /login.php
[] user enters email and password and submits
[] if login succeeds, start session and redirect to appointments.php
[] else re-render to /login.php and store error message in session 


# View Appointments

_that user_
[] returns to /appointments.php -> sees their recent appointment
[] notify the users that they must contact an admin to delete or update appointments

# Admin
_the admin_
[] Creates a normal user account
[] Pre-existing admin gives privilidges to _the admin_
[] can create appointments
[] can update appointments
[] can delete appointments

