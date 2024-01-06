@extends('app.layout.app')

@section('title', 'Login | Register')

@section('style')
    @vite('resources/css/auth.css')
@endsection

@section('content')

<div id="container" class="container">
    <!-- FORM SECTION -->
    <div class="row">
        <!-- SIGN UP -->
        <div class="col align-items-center flex-col sign-up">
            <div class="form-wrapper align-items-center">
                <div class="form sign-up">
                    <div class="input-group">
                        <i class='bx bxs-user'></i>
                        <input type="text" id="username" placeholder="Username">
                    </div>
                    <div class="input-group">
                        <i class='bx bx-mail-send'></i>
                        <input type="email" id="email" placeholder="Email">
                    </div>
                    <div class="input-group">
                        <i class='bx bxs-lock-alt'></i>
                        <input type="password" id="password" placeholder="Password">
                    </div>
                    <div class="input-group">
                        <i class='bx bxs-lock-alt'></i>
                        <input type="password" id="confirm_password" placeholder="Confirm password">
                    </div>
                    <button onclick="signup()">
                        Sign up
                    </button>
                    <p>
                        <span>
                            Already have an account?
                        </span>
                        <b onclick="toggle()" class="pointer">
                            Sign in here
                        </b>
                    </p>
                </div>
            </div>

        </div>
        <!-- END SIGN UP -->
        <!-- SIGN IN -->
        <div class="col align-items-center flex-col sign-in">
            <div class="form-wrapper align-items-center">
                <div class="form sign-in">
                    <div class="input-group">
                        <i class='bx bxs-user'></i>
                        <input type="text" id="login_username" placeholder="Username">
                    </div>
                    <div class="input-group">
                        <i class='bx bxs-lock-alt'></i>
                        <input type="password" id="login_password" placeholder="Password">
                    </div>
                    <button onclick="signin()">
                        Sign in
                    </button>
                    <br>
                    <p>
                        <span>
                            Don't have an account?
                        </span>
                        <b onclick="toggle()" class="pointer">
                            Sign up here
                        </b>
                    </p>
                </div>
            </div>
            <div class="form-wrapper">

            </div>
        </div>
        <!-- END SIGN IN -->
    </div>
    <!-- END FORM SECTION -->
    <!-- CONTENT SECTION -->
    <div class="row content-row">
        <!-- SIGN IN CONTENT -->
        <div class="col align-items-center flex-col">
            <div class="text sign-in">
                <h2>
                    Welcome
                </h2>

            </div>
            <div class="img sign-in">

            </div>
        </div>
        <!-- END SIGN IN CONTENT -->
        <!-- SIGN UP CONTENT -->
        <div class="col align-items-center flex-col">
            <div class="img sign-up">

            </div>
            <div class="text sign-up">
                <h2>
                    Join with us
                </h2>

            </div>
        </div>
        <!-- END SIGN UP CONTENT -->
    </div>
    <!-- END CONTENT SECTION -->
</div>
@endsection

@section('script')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('container');

            setTimeout(() => container.classList.add('sign-in'), 200);

            function toggle() {
                container.classList.toggle('sign-in');
                container.classList.toggle('sign-up');
            }

            function showMessage(title, text, icon, timer) {
                Swal.fire({
                    title: title,
                    text: text,
                    icon: icon,
                    showConfirmButton: false,
                    timer: timer
                });
            }

            function handleSignup() {
                const username = $('#username').val().trim();
                const email = $('#email').val().trim();
                const password = $('#password').val().trim();
                const confirmPassword = $('#confirm_password').val().trim();

                if (!username || username.length < 4) {
                    showMessage('Warning', 'Username must have a minimum of 4 characters', 'warning', 2000);
                    return;
                }

                if (!password || password.length < 8 || !confirmPassword || confirmPassword.length < 8) {
                    showMessage('Warning', 'Password must have a minimum of 8 characters', 'warning', 2000);
                    return;
                }

                if (password !== confirmPassword) {
                    showMessage('Error', 'Password confirmation not matched', 'warning', 1500);
                    $('#password').val('');
                    $('#confirm_password').val('');
                    return;
                }

                // AJAX request for signup
                $.ajax({
                    url: '/register', // Replace with your route for registration
                    method: 'POST',
                    data: { username, email, password },
                    success: function(response) {

                        if(response.msg == "Success") {
                            Swal.fire({
                                title: 'Registration Completed',
                                text: 'Account registered successfully',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $('#login_username').val('');
                            $('#login_password').val('');
                            toggle();
                        } else if (response.msg == "Account already exists with same username") {
                            Swal.fire({
                                title: 'Account Exists',
                                text: 'Account already exists with username ' + loginUsername,
                                icon: 'warning',
                                showConfirmButton: false,
                                timer: 2000
                            });
                        }

                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }

            function handleSignin() {
                const loginUsername = $('#login_username').val().trim();
                const loginPassword = $('#login_password').val().trim();

                if (!loginUsername || loginUsername.length < 4) {
                    showMessage('Warning', 'Username must have a minimum of 4 characters', 'warning', 2000);
                    return;
                }

                if (!loginPassword || loginPassword.length < 8) {
                    showMessage('Warning', 'Password must have a minimum of 8 characters', 'warning', 2000);
                    return;
                }

                // AJAX request for signin
                $.ajax({
                    url: '/login', // Replace with your route for login
                    method: 'POST',
                    data: { username: loginUsername, password: loginPassword },
                    success: function(response) {
                        if(response.msg == "Invalid Username") {
                            Swal.fire({
                                title: 'Invalid Username',
                                text: 'No accounts registered with ' + loginUsername,
                                icon: 'error',
                                showConfirmButton: false,
                                timer: 2000
                            });
                        } else if (response.msg == "Invalid Password") {
                            Swal.fire({
                                title: 'Invalid Password',
                                text: 'Entered password is invalid for ' + loginUsername,
                                icon: 'error',
                                showConfirmButton: false,
                                timer: 2000
                            });
                        } else if (response.msg == "Success") {
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }

            window.toggle = toggle;
            window.signup = handleSignup;
            window.signin = handleSignin;
        });

    </script>
@endsection
