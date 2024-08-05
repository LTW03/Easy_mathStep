let otp_val; // Ensure otp_val is defined globally

document.getElementById('forgot-password-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the form from submitting normally
    sendOtp();
});

function sendOtp() {
    const sendOtpBtn = document.querySelector('.sendOtpbtn');
    sendOtpBtn.disabled = true; // Disable the button

    const email = document.getElementById('email-otp').value.trim();

    fetch('forget_pass_emalVerify.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            'user_email': email
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            showOTPForm();
            sendOtpEmail(email);
        } else {
            alert(data.message);
            sendOtpBtn.disabled = false; // Re-enable the button if there is an error
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error verifying email. Please try again later.');
        sendOtpBtn.disabled = false; // Re-enable the button if there is an error
    });
}

function sendOtpEmail(email) {
    otp_val = Math.floor(100000 + Math.random() * 900000).toString(); // Generate a 6-digit OTP

    const emailbody = `<h2>Your OTP is ${otp_val}</h2>`;
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
        subject: 'Your OTP verification',
        html_body: emailbody 
    })
})
.then(response => response.json())
.then(data => {
    console.log('SMTP2GO Response:', data);

    if (data.data && data.data.succeeded > 0) {
        alert(`OTP sent successfully to your email`);
        document.getElementsByClassName('otpVerify')[0].style.display = 'flex';
    } else {
        console.error('Error from API:', data);
        alert(`Failed to send OTP: ${data.error || 'Unknown error'} (${data.error_code || 'No error code'})`);
    }
})
.catch(error => {
    console.error('Error:', error);
    alert('Error sending OTP. Please try again later.');
});  

}

function showOTPForm() {
    document.getElementById('forgot-password-form').style.display = 'none';
    document.getElementById('otp-form').style.display = 'block';
}

function verifyOtp() {
    const otp_inp = document.getElementById('otp_inp').value.trim(); // Trim any extra spaces
    console.log(`Entered OTP: ${otp_inp}, Actual OTP: ${otp_val}`); // Debugging line

    if (otp_inp === otp_val) {
        alert('Email address verified');
        showUpdatePasswordForm(); // Show the update password form upon successful OTP verification
    } else {
        alert('Invalid OTP');
    }
}

function showUpdatePasswordForm() {
    document.getElementById('otp-form').style.display = 'none';
    document.getElementById('update-password-form').style.display = 'block';
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
            window.location.href = "Login_page.php";
        } else {
            alert('Error: ' + data.error);
        }
    }).catch(error => {
        console.error('Error updating password:', error);
        alert('Error updating password. Please try again later.');
    });
}

function resendOTP() {
    document.getElementById('forgot-password-form').style.display = 'block';
    document.getElementById('otp-form').style.display = 'none';
}
