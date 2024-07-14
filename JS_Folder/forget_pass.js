let otp_val; // Declare otp_val in the global scope

function showOTPForm() {
    document.getElementById('forgot-password-form').style.display = 'none';
    document.getElementById('otp-form').style.display = 'block';
}

function showUpdatePasswordForm() {
    document.getElementById('otp-form').style.display = 'none';
    document.getElementById('update-password-form').style.display = 'block';
}

function resendOTP() {
    document.getElementById('forgot-password-form').style.display = 'block';
    document.getElementById('otp-form').style.display = 'none';
}

function sendOtp() {
    const email = document.getElementById('email-otp').value;
    const otpVerify = document.getElementsByClassName('otpVerify')[0];
    otp_val = Math.floor(Math.random() * 10000); // Assign value to global otp_val
    let emailbody = `<h2>Your OTP is ${otp_val}</h2>`;

    console.log(`Sending OTP to ${email}`);

    fetch('https://api.smtp2go.com/v3/email/send', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            api_key: 'api-56450ECF4955490C88A78B715D26F7CA',
            to: [email],
            sender: 'easymathstep@outlook.com',
            subject: 'Email OTP',
            html_body: emailbody
        })
    }).then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("OTP sent to your email " + email);
            otpVerify.style.display = 'flex';
            
            // Call update_password.php when OTP is successfully sent
            fetch('./Forget_pass-update_password.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    email: email,
                    otp: otp_val
                })
            }).then(updateResponse => updateResponse.json())
            .then(updateData => {
                if (updateData.success) {
                    console.log('Password update process initiated successfully.');
                } else {
                    console.log('Failed to initiate password update process: ' + (updateData.error || 'Unknown error'));
                }
            }).catch(updateError => {
                console.error('Error calling update_password.php:', updateError);
            });
            
        } else {
            console.log('Failed to send OTP: ' + (data.error || 'Unknown error'));
            alert("OTP sent to your email " + email);
        }
    }).catch(error => {
        console.error('Error sending OTP:', error);
        alert('Error sending OTP. Please try again later.');
    });
}

function verifyOtp() {
    const otp_inp = document.getElementById('otp_inp').value;
    if (otp_inp == otp_val) {
        alert('Email address verified');
        showUpdatePasswordForm(); // Show the update password form upon successful OTP verification
    } else {
        alert('Invalid OTP');
    }
}
function updatePassword() {
    const email = document.getElementById('email-otp').value;
    const newPassword = document.getElementById('new-password').value;

    fetch('update_password.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            email: email,
            new_password: newPassword
        })
    }).then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Password updated successfully!');
        } else {
            alert('Error: ' + data.error);
        }
    }).catch(error => {
        console.error('Error updating password:', error);
        alert('Error updating password. Please try again later.');
    });
}