class PaymentSystem {
    constructor() {
        this.form = document.getElementById('paymentForm');
        this.submitBtn = document.getElementById('submitBtn');
        this.onlinePaymentForm = document.getElementById('onlinePaymentForm');
        this.paymentMethods = document.querySelectorAll('input[name="payment_method"]');
        this.paymentMethodCards = document.querySelectorAll('.payment-method');
        
        this.validators = {
            cardNumber: this.validateCardNumber.bind(this),
            expiryDate: this.validateExpiryDate.bind(this),
            cvv: this.validateCVV.bind(this),
            cardholderName: this.validateCardholderName.bind(this)
        };
        
        this.init();
    }
    
    init() {
        this.setupEventListeners();
        this.setupFormFormatting();
        this.setupPaymentMethodSelection();
    }
    
    setupEventListeners() {
        // Form submission
        this.form.addEventListener('submit', this.handleFormSubmit.bind(this));
        
        // Payment method selection
        this.paymentMethods.forEach(method => {
            method.addEventListener('change', this.handlePaymentMethodChange.bind(this));
        });
        
        // Payment method card clicks
        this.paymentMethodCards.forEach(card => {
            card.addEventListener('click', this.handlePaymentMethodCardClick.bind(this));
        });
        
        // Real-time validation for online payment fields
        const fields = ['card_number', 'expiry_date', 'cvv', 'cardholder_name'];
        fields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('input', this.handleFieldInput.bind(this));
                field.addEventListener('blur', this.handleFieldBlur.bind(this));
                field.addEventListener('focus', this.handleFieldFocus.bind(this));
            }
        });
        
        // Prevent form submission on Enter in card fields
        const cardFields = document.querySelectorAll('#onlinePaymentForm input');
        cardFields.forEach(field => {
            field.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    this.focusNextField(field);
                }
            });
        });
    }
    
    setupFormFormatting() {
        // Card number formatting
        const cardNumberField = document.getElementById('card_number');
        if (cardNumberField) {
            cardNumberField.addEventListener('input', this.formatCardNumber.bind(this));
        }
        
        // Expiry date formatting
        const expiryField = document.getElementById('expiry_date');
        if (expiryField) {
            expiryField.addEventListener('input', this.formatExpiryDate.bind(this));
        }
        
        // CVV numeric only
        const cvvField = document.getElementById('cvv');
        if (cvvField) {
            cvvField.addEventListener('input', this.formatCVV.bind(this));
        }
        
        // Cardholder name formatting
        const nameField = document.getElementById('cardholder_name');
        if (nameField) {
            nameField.addEventListener('input', this.formatCardholderName.bind(this));
        }
    }
    
    setupPaymentMethodSelection() {
        // Set initial state
        this.updatePaymentMethodDisplay();
    }
    
    handleFormSubmit(e) {
        e.preventDefault();
        
        if (this.validateForm()) {
            this.showLoading(true);
            this.disableForm(true);
            
            // Simulate processing delay for better UX
            setTimeout(() => {
                this.form.submit();
            }, 1000);
        }
    }
    
    handlePaymentMethodChange(e) {
        this.updatePaymentMethodDisplay();
        this.clearValidationErrors();
    }
    
    handlePaymentMethodCardClick(e) {
        const method = e.currentTarget.dataset.method;
        const radio = document.getElementById(method);
        if (radio) {
            radio.checked = true;
            this.updatePaymentMethodDisplay();
            this.clearValidationErrors();
        }
    }
    
    handleFieldInput(e) {
        const field = e.target;
        this.clearFieldError(field);
        this.validateFieldRealTime(field);
    }
    
    handleFieldBlur(e) {
        const field = e.target;
        this.validateField(field, true);
    }
    
    handleFieldFocus(e) {
        const field = e.target;
        this.clearFieldError(field);
    }
    
    updatePaymentMethodDisplay() {
        const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
        
        // Update card visual states
        this.paymentMethodCards.forEach(card => {
            card.classList.remove('selected');
        });
        
        if (selectedMethod) {
            const selectedCard = document.querySelector(`[data-method="${selectedMethod.value}"]`);
            if (selectedCard) {
                selectedCard.classList.add('selected');
            }
            
            // Show/hide online payment form
            if (selectedMethod.value === 'online') {
                this.onlinePaymentForm.classList.add('active');
                this.focusFirstCardField();
            } else {
                this.onlinePaymentForm.classList.remove('active');
            }
            
            this.updateSubmitButton(selectedMethod.value);
        }
    }
    
    updateSubmitButton(paymentMethod) {
        const btnText = this.submitBtn.querySelector('.btn-text');
        if (paymentMethod === 'cash') {
            btnText.textContent = 'Confirm Cash Payment';
        } else {
            btnText.textContent = 'Process Payment';
        }
    }
    
    focusFirstCardField() {
        setTimeout(() => {
            const firstField = document.getElementById('cardholder_name');
            if (firstField) {
                firstField.focus();
            }
        }, 300);
    }
    
    focusNextField(currentField) {
        const fieldOrder = ['cardholder_name', 'card_number', 'expiry_date', 'cvv'];
        const currentIndex = fieldOrder.indexOf(currentField.id);
        
        if (currentIndex >= 0 && currentIndex < fieldOrder.length - 1) {
            const nextField = document.getElementById(fieldOrder[currentIndex + 1]);
            if (nextField) {
                nextField.focus();
            }
        }
    }
    
    validateForm() {
        const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
        
        if (!selectedMethod) {
            this.showAlert('Please select a payment method', 'error');
            return false;
        }
        
        if (selectedMethod.value === 'online') {
            return this.validateOnlinePaymentForm();
        }
        
        return true;
    }
    
    validateOnlinePaymentForm() {
        const fields = ['cardholder_name', 'card_number', 'expiry_date', 'cvv'];
        let isValid = true;
        
        fields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field && !this.validateField(field, true)) {
                isValid = false;
            }
        });
        
        return isValid;
    }
    
    validateField(field, showError = false) {
        const fieldName = field.id.replace('_', '');
        const validator = this.validators[fieldName];
        
        if (!validator) return true;
        
        const result = validator(field.value);
        
        if (showError) {
            if (result.isValid) {
                this.showFieldSuccess(field);
            } else {
                this.showFieldError(field, result.message);
            }
        }
        
        return result.isValid;
    }
    
    validateFieldRealTime(field) {
        // Only show success states in real-time, errors on blur
        const fieldName = field.id.replace('_', '');
        const validator = this.validators[fieldName];
        
        if (!validator) return;
        
        const result = validator(field.value);
        if (result.isValid) {
            this.showFieldSuccess(field);
        }
    }
    
    validateCardNumber(cardNumber) {
        const cleaned = cardNumber.replace(/\s/g, '');
        
        if (!cleaned) {
            return { isValid: false, message: 'Card number is required' };
        }
        
        if (cleaned.length < 13 || cleaned.length > 19) {
            return { isValid: false, message: 'Card number must be 13-19 digits' };
        }
        
        if (!/^\d+$/.test(cleaned)) {
            return { isValid: false, message: 'Card number must contain only digits' };
        }
        
        // Luhn algorithm validation
        if (!this.luhnCheck(cleaned)) {
            return { isValid: false, message: 'Invalid card number' };
        }
        
        return { isValid: true, message: 'Valid card number' };
    }
    
    validateExpiryDate(expiryDate) {
        if (!expiryDate) {
            return { isValid: false, message: 'Expiry date is required' };
        }
        
        const regex = /^(0[1-9]|1[0-2])\/([0-9]{2})$/;
        if (!regex.test(expiryDate)) {
            return { isValid: false, message: 'Use MM/YY format' };
        }
        
        const [month, year] = expiryDate.split('/');
        const expiry = new Date(2000 + parseInt(year), parseInt(month) - 1);
        const now = new Date();
        const currentMonth = new Date(now.getFullYear(), now.getMonth());
        
        if (expiry < currentMonth) {
            return { isValid: false, message: 'Card has expired' };
        }
        
        return { isValid: true, message: 'Valid expiry date' };
    }
    
    validateCVV(cvv) {
        if (!cvv) {
            return { isValid: false, message: 'CVV is required' };
        }
        
        if (!/^[0-9]{3,4}$/.test(cvv)) {
            return { isValid: false, message: 'CVV must be 3-4 digits' };
        }
        
        return { isValid: true, message: 'Valid CVV' };
    }
    
    validateCardholderName(name) {
        if (!name.trim()) {
            return { isValid: false, message: 'Cardholder name is required' };
        }
        
        if (name.trim().length < 2) {
            return { isValid: false, message: 'Name must be at least 2 characters' };
        }
        
        if (!/^[a-zA-Z\s]+$/.test(name.trim())) {
            return { isValid: false, message: 'Name must contain only letters and spaces' };
        }
        
        return { isValid: true, message: 'Valid name' };
    }
    
    luhnCheck(cardNumber) {
        let sum = 0;
        let alternate = false;
        
        for (let i = cardNumber.length - 1; i >= 0; i--) {
            let digit = parseInt(cardNumber.charAt(i));
            
            if (alternate) {
                digit *= 2;
                if (digit > 9) {
                    digit = (digit % 10) + 1;
                }
            }
            
            sum += digit;
            alternate = !alternate;
        }
        
        return (sum % 10) === 0;
    }
    
    formatCardNumber(e) {
        let value = e.target.value.replace(/\s/g, '').replace(/[^0-9]/gi, '');
        let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
        
        if (formattedValue !== e.target.value) {
            e.target.value = formattedValue;
        }
    }
    
    formatExpiryDate(e) {
        let value = e.target.value.replace(/\D/g, '');
        
        if (value.length >= 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }
        
        e.target.value = value;
    }
    
    formatCVV(e) {
        e.target.value = e.target.value.replace(/\D/g, '');
    }
    
    formatCardholderName(e) {
        // Remove numbers and special characters, allow only letters and spaces
        e.target.value = e.target.value.replace(/[^a-zA-Z\s]/g, '');
    }
    
    showFieldError(field, message) {
        const formGroup = field.closest('.form-group');
        const errorElement = formGroup.querySelector('.error-message');
        
        formGroup.classList.remove('success');
        formGroup.classList.add('error');
        
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.classList.add('show');
        }
    }
    
    showFieldSuccess(field) {
        const formGroup = field.closest('.form-group');
        
        formGroup.classList.remove('error');
        formGroup.classList.add('success');
        
        this.clearFieldError(field);
    }
    
    clearFieldError(field) {
        const formGroup = field.closest('.form-group');
        const errorElement = formGroup.querySelector('.error-message');
        
        formGroup.classList.remove('error');
        
        if (errorElement) {
            errorElement.classList.remove('show');
        }
    }
    
    clearValidationErrors() {
        const errorElements = document.querySelectorAll('.error-message.show');
        const formGroups = document.querySelectorAll('.form-group.error, .form-group.success');
        
        errorElements.forEach(el => el.classList.remove('show'));
        formGroups.forEach(group => group.classList.remove('error', 'success'));
    }
    
    showLoading(show) {
        const spinner = this.submitBtn.querySelector('.loading-spinner');
        const btnText = this.submitBtn.querySelector('.btn-text');
        
        if (show) {
            spinner.style.display = 'inline-block';
            btnText.textContent = 'Processing...';
            this.submitBtn.disabled = true;
        } else {
            spinner.style.display = 'none';
            this.submitBtn.disabled = false;
            this.updateSubmitButton(document.querySelector('input[name="payment_method"]:checked')?.value || 'cash');
        }
    }
    
    disableForm(disable) {
        const inputs = this.form.querySelectorAll('input, button');
        inputs.forEach(input => {
            input.disabled = disable;
        });
    }
    
    showAlert(message, type = 'info') {
        // Remove existing alerts
        const existingAlerts = document.querySelectorAll('.alert');
        existingAlerts.forEach(alert => alert.remove());
        
        // Create new alert
        const alert = document.createElement('div');
        alert.className = `alert alert-${type}`;
        alert.textContent = message;
        
        // Insert at the top of the container
        const container = document.querySelector('.container');
        container.insertBefore(alert, container.firstChild);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 5000);
        
        // Scroll to top to show alert
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}

// Utility functions
const PaymentUtils = {
    detectCardType(cardNumber) {
        const patterns = {
            visa: /^4[0-9]{12}(?:[0-9]{3})?$/,
            mastercard: /^5[1-5][0-9]{14}$/,
            amex: /^3[47][0-9]{13}$/,
            discover: /^6(?:011|5[0-9]{2})[0-9]{12}$/
        };
        
        const cleaned = cardNumber.replace(/\s/g, '');
        
        for (const [type, pattern] of Object.entries(patterns)) {
            if (pattern.test(cleaned)) {
                return type;
            }
        }
        
        return 'unknown';
    },
    
    formatCurrency(amount) {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD'
        }).format(amount);
    },
    
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
};

// Initialize payment system when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new PaymentSystem();
});

// Handle page visibility changes (pause/resume validation)
document.addEventListener('visibilitychange', () => {
    if (document.hidden) {
        // Page is hidden, pause any ongoing processes
        console.log('Payment page hidden');
    } else {
        // Page is visible again
        console.log('Payment page visible');
    }
});

// Handle beforeunload to warn about unsaved changes
window.addEventListener('beforeunload', (e) => {
    const form = document.getElementById('paymentForm');
    const hasChanges = form && form.querySelector('input:checked');
    
    if (hasChanges) {
        e.preventDefault();
        e.returnValue = 'You have unsaved payment information. Are you sure you want to leave?';
        return e.returnValue;
    }
});

// Export for testing purposes
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { PaymentSystem, PaymentUtils };
}

