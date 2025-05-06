@extends('layouts.home')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-white px-4">
    <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-md">
        <a href="/" class="flex justify-start">
            <button class="text-cos-yellow font-bold pb-5 rounded">
                < Home
            </button>
        </a>
        <h2 class="text-3xl font-bold text-center mb-6 text-gray-800">Login to MINE</h2>
        
        <form id="loginForm" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-gray-600 mb-2">Email</label>
                <input type="email" name="email" required placeholder="youremail@gmail.com"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#fcbf49]" id="email">
            </div>

            <div>
                <label class="block text-gray-600 mb-2">Password</label>
                <input type="password" name="password" required placeholder="your password"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#fcbf49]" id="password">
            </div>

            <div id="errorMessage" class="bg-red-100 text-red-700 p-2 rounded mb-4 hidden"></div>

            <button type="submit"
                class="w-full bg-[#fcbf49] text-white py-2 rounded-lg font-semibold hover:bg-yellow-500 transition">
                Login
            </button>
        </form>

        <p class="text-center text-gray-600 mt-4">
            Belum punya akun? 
            <a href="/signup" class="text-[#fcbf49] font-semibold hover:underline">Sign Up</a>
        </p>
    </div>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#loginForm').on('submit', function(e) {
            e.preventDefault();

            $('#errorMessage').addClass('hidden').empty();

            const email = $('#email').val();
            const password = $('#password').val();
            const token = $('input[name="_token"]').val(); 

            $.ajax({
                url: '/api/login',
                method: 'POST',
                data: {
                    _token: token,
                    email: email,
                    password: password
                },
                success: function(response) {
                    window.location.href = '/index';
                },
                error: function(xhr) {
                    const errorMessages = xhr.responseJSON.errors;
                    let errorsHtml = '';
                    for (let key in errorMessages) {
                        if (errorMessages.hasOwnProperty(key)) {
                            errorsHtml += `<p>${errorMessages[key]}</p>`;
                        }
                    }
                    $('#errorMessage').removeClass('hidden').html(errorsHtml);
                }
            });
        });
    });
</script>
