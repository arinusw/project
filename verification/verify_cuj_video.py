import time
import os
from playwright.sync_api import sync_playwright

def record_journey():
    os.makedirs('verification/videos', exist_ok=True)

    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        context = browser.new_context(
            viewport={'width': 1280, 'height': 800},
            record_video_dir='verification/videos/'
        )
        page = context.new_page()
        page.goto('http://127.0.0.1:8000')
        page.wait_for_load_state('networkidle')

        # Hero interaction
        time.sleep(1)

        # Smooth scroll to About
        page.click('#nav-menu a[href="#about"]')
        time.sleep(1.5)

        # Smooth scroll to Services
        page.click('#nav-menu a[href="#services"]')
        time.sleep(1.5)

        # Smooth scroll to Education
        page.click('#nav-menu a[href="#education"]')
        time.sleep(1.5)

        # Toggle Dark Theme
        page.click('#theme-button')
        time.sleep(1.5)

        # Smooth scroll to Contact
        page.click('#nav-menu a[href="#contact"]')
        time.sleep(1.5)

        # Form submission
        page.fill('#name', 'Siti Aminah')
        page.fill('#email', 'siti@example.com')
        page.fill('#message', 'Pertanyaan mengenai kelas edukasi teknologi Ar Creative.')
        page.evaluate("""
            const form = document.getElementById('contact-form');
            let input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'simulation_mode';
            input.value = '1';
            form.appendChild(input);
        """)
        page.click('.contact__submit-btn')
        time.sleep(2)

        context.close()
        browser.close()
        print("Customer User Journey Video recorded successfully.")

if __name__ == '__main__':
    record_journey()
