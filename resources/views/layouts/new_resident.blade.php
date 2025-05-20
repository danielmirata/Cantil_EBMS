<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Management System</title>

    <!-- Bootstrap CSS and Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/secretary-dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('css/res_info.css') }}" rel="stylesheet">
    
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @stack('styles')
</head>
<body>

    <div class="d-flex">
        @include('partials.secretary-sidebar')
        <main class="container flex-grow-1">
            @yield('content')
        </main>
    </div>

    <footer class="bg-light text-center py-3 mt-4">
        <small>&copy; {{ date('Y') }} Barangay Management System. All rights reserved.</small>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('scripts')

    <script>
        // Common SweetAlert configurations for resident operations
        window.showSuccessAlert = function(message) {
            return Swal.fire({
                title: 'Success!',
                text: message,
                icon: 'success',
                confirmButtonText: 'OK'
            });
        };

        window.showErrorAlert = function(message) {
            return Swal.fire({
                title: 'Error!',
                text: message,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        };

        window.showConfirmAlert = function(title, text, confirmButtonText = 'Yes') {
            return Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: confirmButtonText,
                cancelButtonText: 'Cancel'
            });
        };

        // Handle form submissions with SweetAlert
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form[data-swal]');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    const action = this.getAttribute('action');
                    const method = this.getAttribute('method');

                    fetch(action, {
                        method: method,
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showSuccessAlert(data.message);
                            if (data.redirect) {
                                setTimeout(() => {
                                    window.location.href = data.redirect;
                                }, 1500);
                            }
                        } else {
                            showErrorAlert(data.message);
                        }
                    })
                    .catch(error => {
                        showErrorAlert('An error occurred. Please try again.');
                    });
                });
            });
        });
    </script>
</body>
</html>
