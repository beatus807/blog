<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Create Your Account</h4>
            </div>
            <div class="card-body">
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?php echo $this->session->flashdata('success'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo $this->session->flashdata('error'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php echo form_open('auth/register', ['id' => 'registerForm']); ?>
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name *</label>
                        <input type="text" class="form-control <?php echo form_error('name') ? 'is-invalid' : ''; ?>" 
                               id="name" name="name" value="<?php echo set_value('name'); ?>" 
                               placeholder="Enter your full name" required>
                        <div class="invalid-feedback">
                            <?php echo form_error('name'); ?>
                        </div>
                        <div class="form-text help-text" id="nameHelp" style="display: none;">
                            Enter your full name (minimum 3 characters)
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address *</label>
                        <input type="email" class="form-control <?php echo form_error('email') ? 'is-invalid' : ''; ?>" 
                               id="email" name="email" value="<?php echo set_value('email'); ?>" 
                               placeholder="Enter your email address" required>
                        <div class="invalid-feedback">
                            <?php echo form_error('email'); ?>
                        </div>
                        <div class="form-text help-text" id="emailHelp" style="display: none;">
                            Enter a valid email address (e.g., name@example.com)
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password *</label>
                        <input type="password" class="form-control <?php echo form_error('password') ? 'is-invalid' : ''; ?>" 
                               id="password" name="password" placeholder="Create a strong password" required>
                        <div class="invalid-feedback">
                            <?php echo form_error('password'); ?>
                        </div>
                        <div class="form-text help-text" id="passwordHelp" style="display: none;">
                            <small>Password requirements:</small>
                            <ul class="small mb-0 mt-1">
                                <li id="length" class="text-muted">At least 6 characters long</li>
                                <li id="strength" class="text-muted">Contains letters and numbers</li>
                            </ul>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password *</label>
                        <input type="password" class="form-control <?php echo form_error('confirm_password') ? 'is-invalid' : ''; ?>" 
                               id="confirm_password" name="confirm_password" placeholder="Re-enter your password" required>
                        <div class="invalid-feedback">
                            <?php echo form_error('confirm_password'); ?>
                        </div>
                        <div class="form-text help-text" id="confirmHelp" style="display: none;">
                            Re-enter your password to confirm
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-3">
                        <i class="fas fa-user-plus me-2"></i>Create Account
                    </button>
                    
                    <div class="text-center">
                        <small class="text-muted">
                            Already have an account? 
                            <a href="<?php echo site_url('auth/login'); ?>" class="text-decoration-none">Sign In</a>
                        </small>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Show help text on focus, hide on blur
    $('#name').focus(function() {
        $('#nameHelp').show();
    }).blur(function() {
        if ($(this).val() === '') {
            $('#nameHelp').hide();
        }
    });

    $('#email').focus(function() {
        $('#emailHelp').show();
    }).blur(function() {
        if ($(this).val() === '') {
            $('#emailHelp').hide();
        }
    });

    $('#password').focus(function() {
        $('#passwordHelp').show();
    }).blur(function() {
        if ($(this).val() === '') {
            $('#passwordHelp').hide();
        }
    });

    $('#confirm_password').focus(function() {
        $('#confirmHelp').show();
    }).blur(function() {
        if ($(this).val() === '') {
            $('#confirmHelp').hide();
        }
    });

    // Password strength indicator
    $('#password').on('input', function() {
        var password = $(this).val();
        
        // Check length
        if (password.length >= 6) {
            $('#length').removeClass('text-muted').addClass('text-success');
            $('#length').html('<i class="fas fa-check me-1"></i>At least 6 characters long');
        } else {
            $('#length').removeClass('text-success').addClass('text-muted');
            $('#length').html('At least 6 characters long');
        }
        
        // Check strength (contains both letters and numbers)
        var hasLetters = /[a-zA-Z]/.test(password);
        var hasNumbers = /[0-9]/.test(password);
        
        if (hasLetters && hasNumbers) {
            $('#strength').removeClass('text-muted').addClass('text-success');
            $('#strength').html('<i class="fas fa-check me-1"></i>Contains letters and numbers');
        } else {
            $('#strength').removeClass('text-success').addClass('text-muted');
            $('#strength').html('Contains letters and numbers');
        }
    });

    // Confirm password validation
    $('#confirm_password').on('input', function() {
        var password = $('#password').val();
        var confirmPassword = $(this).val();
        
        if (confirmPassword !== '' && password !== confirmPassword) {
            $(this).addClass('is-invalid');
            $(this).next('.invalid-feedback').text('Passwords do not match');
        } else {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').text('');
        }
    });

    // Form validation
    $('#registerForm').validate({
        rules: {
            name: {
                required: true,
                minlength: 3,
                maxlength: 100
            },
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 6,
                strongPassword: true
            },
            confirm_password: {
                required: true,
                equalTo: "#password"
            }
        },
        messages: {
            name: {
                required: "Please enter your full name",
                minlength: "Name must be at least 3 characters long"
            },
            email: {
                required: "Please enter your email address",
                email: "Please enter a valid email address"
            },
            password: {
                required: "Please create a password",
                minlength: "Password must be at least 6 characters long"
            },
            confirm_password: {
                required: "Please confirm your password",
                equalTo: "Passwords do not match"
            }
        },
        errorElement: 'div',
        errorClass: 'invalid-feedback',
        highlight: function(element) {
            $(element).addClass('is-invalid');
            // Show help text when there's an error
            var helpId = element.id + 'Help';
            if ($('#' + helpId).length) {
                $('#' + helpId).show();
            }
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid');
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            // Show loading state
            var submitBtn = $(form).find('button[type="submit"]');
            var originalText = submitBtn.html();
            submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Creating Account...');
            submitBtn.prop('disabled', true);

            // Submit the form
            form.submit();
        }
    });

    // Custom validation rule for strong password
    $.validator.addMethod("strongPassword", function(value, element) {
        return this.optional(element) || 
               /^(?=.*[a-zA-Z])(?=.*[0-9])/.test(value);
    }, "Password must contain both letters and numbers");

    // Show help text if there are validation errors on page load
    <?php if (validation_errors()): ?>
        $('.form-control').each(function() {
            if ($(this).hasClass('is-invalid')) {
                var helpId = this.id + 'Help';
                if ($('#' + helpId).length) {
                    $('#' + helpId).show();
                }
            }
        });
    <?php endif; ?>
});
</script>

<style>
.help-text {
    background-color: #f8f9fa;
    border-left: 4px solid #007bff;
    padding: 8px 12px;
    border-radius: 0 4px 4px 0;
    margin-top: 5px;
}
</style>