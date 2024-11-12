let password = document.getElementById("password")
let confirm_password = document.getElementById("confirmPassword");

function validatePassword(){
  if(password.value != confirm_password.value) {
    confirm_password.setCustomValidity("Passwords Doesn't Match");
  } else {
    confirm_password.setCustomValidity('');
  }
}

password.onchange = validatePassword;
confirm_password.onkeyup = validatePassword;


const togglePassword = document.getElementById('showPassword');
const toggleConfirmPassword = document.getElementById('showConfirmPassword');

togglePassword.addEventListener('pointerdown', (e) => {
  e.preventDefault();
    // Toggle input type between password and text
    if (password.type === 'password') {
      password.type = 'text';
      togglePassword.classList.remove('fa-eye');
      togglePassword.classList.add('fa-eye-slash');
    } else {
      password.type = 'password';
      togglePassword.classList.remove('fa-eye-slash');
      togglePassword.classList.add('fa-eye');
    }
  });
toggleConfirmPassword.addEventListener('pointerdown', (e) => {
  e.preventDefault();
    // Toggle input type between password and text
    if (confirm_password.type === 'password') {
      confirm_password.type = 'text';
      toggleConfirmPassword.classList.remove('fa-eye');
      toggleConfirmPassword.classList.add('fa-eye-slash');
    } else {
      confirm_password.type = 'password';
      toggleConfirmPassword.classList.remove('fa-eye-slash');
      toggleConfirmPassword.classList.add('fa-eye');
    }
  });

 // Fetch all the forms we want to apply custom Bootstrap validation styles to
 const forms = document.querySelectorAll('.needs-validation');
  
 // Loop over them and prevent submission
 Array.prototype.slice.call(forms).forEach((form) => {
   form.addEventListener('submit', (event) => {
     if (!form.checkValidity()) {
       event.preventDefault();
       event.stopPropagation();
     }
     form.classList.add('was-validated');
   }, false);
 });