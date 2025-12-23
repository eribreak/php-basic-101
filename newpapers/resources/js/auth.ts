function setFieldError(form: HTMLElement, fieldName: string, message: string) {
    const el = form.querySelector<HTMLElement>(
        `[data-field-error="${fieldName}"]`,
    );
    if (!el) return;

    el.textContent = message;
    el.classList.remove('hidden');
}

function clearFieldError(form: HTMLElement, fieldName: string) {
    const el = form.querySelector<HTMLElement>(
        `[data-field-error="${fieldName}"]`,
    );
    if (!el) return;

    el.textContent = '';
    el.classList.add('hidden');
}

function isValidEmail(value: string) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
}

function bootLoginValidation(form: HTMLFormElement) {
    const emailInput = form.querySelector<HTMLInputElement>(
        'input[name="email"]',
    );
    const passwordInput = form.querySelector<HTMLInputElement>(
        'input[name="password"]',
    );

    if (!emailInput || !passwordInput) return;

    const msgEmailRequired =
        form.dataset.msgEmailRequired ?? 'Email là bắt buộc.';
    const msgEmailInvalid =
        form.dataset.msgEmailInvalid ?? 'Email không đúng định dạng.';
    const msgPasswordRequired =
        form.dataset.msgPasswordRequired ?? 'Mật khẩu là bắt buộc.';

    const validateEmail = () => {
        const value = emailInput.value.trim();
        if (!value) {
            setFieldError(form, 'email', msgEmailRequired);
            return false;
        }
        if (!isValidEmail(value)) {
            setFieldError(form, 'email', msgEmailInvalid);
            return false;
        }
        clearFieldError(form, 'email');
        return true;
    };

    const validatePassword = () => {
        const value = passwordInput.value;
        if (!value) {
            setFieldError(form, 'password', msgPasswordRequired);
            return false;
        }
        clearFieldError(form, 'password');
        return true;
    };

    emailInput.addEventListener('input', () => {
        if (emailInput.value.trim()) clearFieldError(form, 'email');
    });

    passwordInput.addEventListener('input', () => {
        if (passwordInput.value) clearFieldError(form, 'password');
    });

    emailInput.addEventListener('blur', validateEmail);
    passwordInput.addEventListener('blur', validatePassword);

    form.addEventListener('submit', (e) => {
        clearFieldError(form, 'email');
        clearFieldError(form, 'password');

        const okEmail = validateEmail();
        const okPassword = validatePassword();

        if (!okEmail || !okPassword) {
            e.preventDefault();
            (okEmail ? passwordInput : emailInput).focus();
        }
    });
}

function bootRegisterValidation(form: HTMLFormElement) {
    const nameInput =
        form.querySelector<HTMLInputElement>('input[name="name"]');
    const emailInput = form.querySelector<HTMLInputElement>(
        'input[name="email"]',
    );
    const passwordInput = form.querySelector<HTMLInputElement>(
        'input[name="password"]',
    );
    const passwordConfirmationInput = form.querySelector<HTMLInputElement>(
        'input[name="password_confirmation"]',
    );

    if (
        !nameInput ||
        !emailInput ||
        !passwordInput ||
        !passwordConfirmationInput
    )
        return;

    const msgNameRequired = form.dataset.msgNameRequired ?? 'Tên là bắt buộc.';
    const msgEmailRequired =
        form.dataset.msgEmailRequired ?? 'Email là bắt buộc.';
    const msgEmailInvalid =
        form.dataset.msgEmailInvalid ?? 'Email không đúng định dạng.';
    const msgPasswordRequired =
        form.dataset.msgPasswordRequired ?? 'Mật khẩu là bắt buộc.';
    const msgPasswordConfirmed =
        form.dataset.msgPasswordConfirmed ?? 'Mật khẩu xác nhận không khớp.';

    const validateName = () => {
        const value = nameInput.value.trim();
        if (!value) {
            setFieldError(form, 'name', msgNameRequired);
            return false;
        }
        clearFieldError(form, 'name');
        return true;
    };

    const validateEmail = () => {
        const value = emailInput.value.trim();
        if (!value) {
            setFieldError(form, 'email', msgEmailRequired);
            return false;
        }
        if (!isValidEmail(value)) {
            setFieldError(form, 'email', msgEmailInvalid);
            return false;
        }
        clearFieldError(form, 'email');
        return true;
    };

    const validatePassword = () => {
        const value = passwordInput.value;
        if (!value) {
            setFieldError(form, 'password', msgPasswordRequired);
            return false;
        }
        clearFieldError(form, 'password');
        return true;
    };

    const validatePasswordConfirmation = () => {
        const value = passwordConfirmationInput.value;
        if (!value) {
            setFieldError(form, 'password_confirmation', msgPasswordRequired);
            return false;
        }
        if (passwordInput.value && value !== passwordInput.value) {
            setFieldError(form, 'password_confirmation', msgPasswordConfirmed);
            return false;
        }
        clearFieldError(form, 'password_confirmation');
        return true;
    };

    nameInput.addEventListener('input', () => {
        if (nameInput.value.trim()) clearFieldError(form, 'name');
    });

    emailInput.addEventListener('input', () => {
        if (emailInput.value.trim()) clearFieldError(form, 'email');
    });

    passwordInput.addEventListener('input', () => {
        if (passwordInput.value) clearFieldError(form, 'password');
        if (passwordConfirmationInput.value) validatePasswordConfirmation();
    });

    passwordConfirmationInput.addEventListener('input', () => {
        if (passwordConfirmationInput.value)
            clearFieldError(form, 'password_confirmation');
    });

    nameInput.addEventListener('blur', validateName);
    emailInput.addEventListener('blur', validateEmail);
    passwordInput.addEventListener('blur', validatePassword);
    passwordConfirmationInput.addEventListener(
        'blur',
        validatePasswordConfirmation,
    );

    form.addEventListener('submit', (e) => {
        clearFieldError(form, 'name');
        clearFieldError(form, 'email');
        clearFieldError(form, 'password');
        clearFieldError(form, 'password_confirmation');

        const okName = validateName();
        const okEmail = validateEmail();
        const okPassword = validatePassword();
        const okPasswordConfirmation = validatePasswordConfirmation();

        if (!okName || !okEmail || !okPassword || !okPasswordConfirmation) {
            e.preventDefault();
            (okName
                ? okEmail
                    ? okPassword
                        ? passwordConfirmationInput
                        : passwordInput
                    : emailInput
                : nameInput
            ).focus();
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.querySelector<HTMLFormElement>(
        'form[data-js-validate="login"]',
    );

    if (loginForm) {
        bootLoginValidation(loginForm);
    }

    const registerForm = document.querySelector<HTMLFormElement>(
        'form[data-js-validate="register"]',
    );

    if (registerForm) {
        bootRegisterValidation(registerForm);
    }
});
