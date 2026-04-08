// Hlavný JavaScript súbor pre LinuxDistro webstránku

// Spustí sa keď sa celá stránka načíta
document.addEventListener('DOMContentLoaded', function() {
    // Inicializácia všetkých komponentov stránky
    initNavigation();     // Spustí navigáciu
    initCarousel();       // Spustí prezentáciu obrázkov
    initAccordion();      // Spustí rozbaľovacie sekcie
    initContactForm();    // Spustí kontaktný formulár
    initAnimations();     // Spustí animácie
    initAlerts();         // Spustí upozornenia

});

// Funkcia pre navigačné menu - hamburger menu pre mobilné zariadenia
function initNavigation() {
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('.nav-menu');
    
    if (hamburger && navMenu) {
        // Po kliknutí na hamburger ikonu
        hamburger.addEventListener('click', function() {
            hamburger.classList.toggle('active');
            navMenu.classList.toggle('active');
            
            // Zablokuje scrollovanie keď je menu otvorené
            if (navMenu.classList.contains('active')) {
                document.body.classList.add('no-scroll');
            } else {
                document.body.classList.remove('no-scroll');
            }
        });
        
        // Zatvára menu po kliknutí na odkaz
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                hamburger.classList.remove('active');
                navMenu.classList.remove('active');
                document.body.classList.remove('no-scroll');
            });
        });
        
        // Zatvára menu po kliknutí mimo neho
         document.addEventListener('click', function(event) {
            if (!event.target.closest('.nav-container')) {
                hamburger.classList.remove('active');
                navMenu.classList.remove('active');
                document.body.classList.remove('no-scroll');
            }
        });
    }
    
    // Efekt navigácie pri scrollovaní - mení priehľadnosť
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 100) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
}

// Funkcia pre prezentáciu obrázkov (karusel) - automatické posúvanie
function initCarousel() {
    let currentSlide = 0;
    const slides = document.querySelectorAll('.carousel-slide');
    const totalSlides = slides.length;
    
    if (slides.length === 0) return;
    
    // Zobrazí konkrétny slajd
    function showSlide(index) {
        // Odstráni 'active' triedu zo všetkých slajdov
        slides.forEach(slide => {
            slide.classList.remove('active');
        });
        
        // Vypočíta aktuálny slajd s ochranou prekročenia rozsahu
        currentSlide = (index + totalSlides) % totalSlides;
        // Pridá 'active' triedu aktuálnemu slajdu
        slides[currentSlide].classList.add('active');
    }
    
    // Automatické posúvanie každých 5 sekúnd
    let slideInterval = setInterval(() => {
        showSlide(currentSlide + 1);
    }, 5000);
    
    // Pozastaví automatické posúvanie keď myš je nad karuselom
    const carousel = document.querySelector('.carousel');
    if (carousel) {
        carousel.addEventListener('mouseenter', () => {
            clearInterval(slideInterval);
        });
        
        carousel.addEventListener('mouseleave', () => {
            slideInterval = setInterval(() => {
                showSlide(currentSlide + 1);
            }, 5000);
        });
    }
    
    // Vytvorí globálne funkcie pre tlačidlá (používané v HTML)
    window.nextSlide = function() {
        clearInterval(slideInterval);
        showSlide(currentSlide + 1);
        slideInterval = setInterval(() => {
            showSlide(currentSlide + 1);
        }, 5000);
    };
    
    window.prevSlide = function() {
        clearInterval(slideInterval);
        showSlide(currentSlide - 1);
        slideInterval = setInterval(() => {
            showSlide(currentSlide + 1);
        }, 5000);
    };
    
    // Podpora dotykových gest pre mobilné zariadenia
    let touchStartX = 0;
    let touchEndX = 0;
    
    if (carousel) {
        // Zachytí začiatok dotyku
        carousel.addEventListener('touchstart', e => {
            touchStartX = e.changedTouches[0].screenX;
        });
        
        // Zachytí koniec dotyku
        carousel.addEventListener('touchend', e => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });
    }
    
    // Spracuje swipe gesto - určuje smer potiahnutia
    function handleSwipe() {
        const swipeThreshold = 50;
        const diff = touchStartX - touchEndX;
        
        if (Math.abs(diff) > swipeThreshold) {
            if (diff > 0) {
                // Swipe doľava - nasledujúci slajd
                window.nextSlide();
            } else {
                // Swipe doprava - predchádzajúci slajd
                window.prevSlide();
            }
        }
    }
}

// Funkcia pre akordeón (rozbaľovacie sekcie)
function initAccordion() {
    const accordionHeaders = document.querySelectorAll('.accordion-header');
    
    accordionHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const isActive = this.classList.contains('active');
            const content = this.nextElementSibling;
            
            // Prepne aktuálnu položku
            if (!isActive) {
                this.classList.add('active');
                if (content) {
                    content.classList.add('active');
                }
            } else {
                this.classList.remove('active');
                if (content) {
                    content.classList.remove('active');
                }
            }
            
            // Zatvorí všetky ostatné položky akordeónu
            accordionHeaders.forEach(otherHeader => {
                if (otherHeader !== this) {
                    otherHeader.classList.remove('active');
                    const otherContent = otherHeader.nextElementSibling;
                    if (otherContent) {
                        otherContent.classList.remove('active');
                    }
                }
            });
        });
    });
    
    // Automaticky otvorí prvú položku akordeónu pri načítaní stránky
    if (accordionHeaders.length > 0 && !accordionHeaders[0].classList.contains('active')) {
        accordionHeaders[0].classList.add('active');
        const firstContent = accordionHeaders[0].nextElementSibling;
        if (firstContent) {
            firstContent.classList.add('active');
        }
    }
}

// Funkcia pre kontaktný formulár - validácia a odoslanie
function initContactForm() {
    const contactForm = document.getElementById('contactForm');
    
    if (contactForm) {
        // Zachytí odoslanie formulára
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Zabráni štandardnému odoslaniu
            
            if (validateForm(this)) {
                // Zobrazí stav načítavania
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.textContent;
                submitBtn.textContent = 'Odosielam...';
                submitBtn.classList.add('loading');
                
                // Simuluje odoslanie formulára
                setTimeout(() => {
                    window.location.href = 'thank-you.html'; // Presmerovanie
                }, 2000);
            }
        });
        
        // Pridá real-time validáciu polí formulára
        const inputs = contactForm.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            // Validácia keď používateľ odíde z poľa
            input.addEventListener('blur', function() {
                validateField(this);
            });
            
            // Vymazanie chybových správ pri písaní
            input.addEventListener('input', function() {
                clearFieldError(this);
            });
        });
    }
}

// Hlavná funkcia pre validáciu celého formulára
function validateForm(form) {
    let isValid = true;
    const requiredFields = form.querySelectorAll('[required]');
    
    // Skontroluje všetky povinné polia
    requiredFields.forEach(field => {
        if (!validateField(field)) {
            isValid = false;
        }
    });
    
    // Špeciálna validácia pre emailové pole
    const emailField = form.querySelector('input[type="email"]');
    if (emailField && emailField.value) {
        if (!validateEmail(emailField.value)) {
            showFieldError(emailField, 'Prosím, zadajte platnú emailovú adresu.');
            isValid = false;
        }
    }
    
    return isValid;
}

// Validácia jednotlivého poľa formulára
function validateField(field) {
    const value = field.value.trim();
    
    // Kontrola povinných polí
    if (field.hasAttribute('required') && !value) {
        showFieldError(field, 'Toto pole je povinné.');
        return false;
    }
    
    // Kontrola formátu emailu
    if (field.type === 'email' && value && !validateEmail(value)) {
        showFieldError(field, 'Prosím, zadajte platnú emailovú adresu.');
        return false;
    }
    
    clearFieldError(field);
    return true;
}

// Validácia formátu emailovej adresy pomocou regulárneho výrazu
function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Zobrazí chybovú správu pre pole
function showFieldError(field, message) {
    clearFieldError(field);
    
    field.classList.add('error');
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error';
    errorDiv.textContent = message;
    
    field.parentNode.appendChild(errorDiv); // Pridá chybu pod pole
}

// Vymaže chybovú správu z poľa
function clearFieldError(field) {
    field.classList.remove('error');
    
    const existingError = field.parentNode.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }
}

// Funkcia pre animácie pri scrollovaní, bonus poeny
function initAnimations() {
    const observerOptions = {
        threshold: 0.1, // Spustí sa keď je 10% prvku viditeľné
        rootMargin: '0px 0px -50px 0px'
    };
    
    // Vytvorí observer ktorý sleduje viditeľnosť elementov
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in'); // Pridá CSS animáciu
                observer.unobserve(entry.target); // Prestane sledovať
            }
        });
    }, observerOptions);
    
    // Sleduje tieto elementy pre animácie
    const animateElements = document.querySelectorAll('.distro-card, .info-card, .feature-list, .table-container');
    animateElements.forEach(el => {
        observer.observe(el);
    });
}

// Funkcia pre upozornenia (alerts) - pridá zatváracie tlačidlo
function initAlerts() {
    const alerts = document.querySelectorAll('.alert');
    
    alerts.forEach(alert => {
        // Vytvorí zatváracie tlačidlo (×)
        const closeBtn = document.createElement('button');
        closeBtn.innerHTML = '×';
        closeBtn.className = 'alert-close';
        closeBtn.setAttribute('aria-label', 'Close alert');
        
        // Pridá funkciu na zatvorenie alertu
        closeBtn.addEventListener('click', function() {
            alert.classList.add('fade-out');
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.remove(); // Odstráni alert z DOM
                }
            }, 300);
        });
        
        // Pridá tlačidlo do alertu
        alert.appendChild(closeBtn);
        
        // Automaticky zatvorí alert po 10 sekundách
        setTimeout(() => {
            if (alert.parentNode) {
                alert.classList.add('fade-out');
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.remove();
                    }
                }, 300);
            }
        }, 10000);
    });
}

