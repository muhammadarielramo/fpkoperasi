import { Component, OnInit, ViewEncapsulation } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  standalone: false,
  selector: 'app-register',
  templateUrl: './register.page.html',
  styleUrls: ['./register.page.scss'],
  encapsulation: ViewEncapsulation.None
})
export class RegisterPage implements OnInit {
  showVerification = false;
  showDatePicker = false;
  selectedDate = ''; 
  showPassword = false; 
<<<<<<< Updated upstream
=======
  selectedGender: string = '';
>>>>>>> Stashed changes

  constructor(private router: Router) { }

  ngOnInit() {
  }

  showVerificationAlert() {
    this.showVerification = true;
    
    // Redirect after 5 seconds
    setTimeout(() => {
      this.router.navigate(['/home']);
    }, 5000);
  }

    openDatePicker() {
    this.showDatePicker = true;
  }
  
  setDateValue(event: any) {
    if (event && event.detail && event.detail.value) {
      // Format date as DD/MM/YYYY
      const dateValue = event.detail.value;
      
      // Check if the dateValue is a valid date string
      if (typeof dateValue === 'string' && dateValue.trim() !== '') {
        const date = new Date(dateValue);
        
        // Check if date is valid
        if (!isNaN(date.getTime())) {
          const day = String(date.getDate()).padStart(2, '0');
          const month = String(date.getMonth() + 1).padStart(2, '0');
          const year = date.getFullYear();
          
          this.selectedDate = `${day}/${month}/${year}`;
          
          // Set the value to the input field
          const inputElement = document.getElementById('tanggalLahirInput') as HTMLIonInputElement;
          if (inputElement) {
            inputElement.value = this.selectedDate;
          }
        }
      }
    }
    
    this.showDatePicker = false;
  }

  togglePasswordVisibility() {
    this.showPassword = !this.showPassword;
  }  
<<<<<<< Updated upstream
=======

>>>>>>> Stashed changes
}

    

