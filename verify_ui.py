import os
import time
import subprocess
from playwright.sync_api import sync_playwright

def run_verification():
    os.makedirs('verification/screenshots', exist_ok=True)
    os.makedirs('verification/videos', exist_ok=True)

    # Start PHP local server
    php_process = subprocess.Popen(
        ['php', '-S', '127.0.0.1:8000', '-t', 'ar-creative'],
        stdout=subprocess.PIPE,
        stderr=subprocess.PIPE
    )
    time.sleep(1.5)

    try:
        with sync_playwright() as p:
            browser = p.chromium.launch(headless=True)

            # 1. Desktop Verification
            print("Running Desktop Verification...")
            desktop_context = browser.new_context(
                viewport={'width': 1280, 'height': 800},
                record_video_dir='verification/videos/'
            )
            desktop_page = desktop_context.new_page()
            desktop_page.goto('http://127.0.0.1:8000')
            desktop_page.wait_for_selector('#home')

            # Desktop Light Theme
            desktop_page.screenshot(path='verification/screenshots/desktop_light.png')

            # Toggle Theme (Dark)
            desktop_page.click('#theme-button')
            desktop_page.wait_for_timeout(300)
            desktop_page.screenshot(path='verification/screenshots/desktop_dark.png')

            # Toggle Back to Light
            desktop_page.click('#theme-button')
            desktop_page.wait_for_timeout(300)

            # Scroll through sections to trigger ScrollReveal animations
            for section in ['#about', '#services', '#education', '#social', '#contact']:
                desktop_page.evaluate(f"document.querySelector('{section}').scrollIntoView({{behavior: 'smooth'}})")
                desktop_page.wait_for_timeout(500)

            # Test Contact Form with simulation_mode
            desktop_page.fill('#name', 'Budi Santoso')
            desktop_page.fill('#email', 'budi@example.com')
            desktop_page.fill('#subject', 'Konsultasi Project Website')
            desktop_page.fill('#message', 'Halo Ar Creative, kami ingin berdiskusi mengenai layanan pembuatan website dan modul edukasi.')

            # Add simulation mode hidden input before submit
            desktop_page.evaluate("""
                const form = document.getElementById('contact-form');
                const simInput = document.createElement('input');
                simInput.type = 'hidden';
                simInput.name = 'simulation_mode';
                simInput.value = '1';
                form.appendChild(simInput);
            """)

            desktop_page.click('#contact-button')
            desktop_page.wait_for_selector('#contact-message:has-text("Pesan Anda telah berhasil terkirim")', timeout=5000)
            desktop_page.screenshot(path='verification/screenshots/contact_success.png')

            desktop_context.close()

            # 2. Mobile Verification
            print("Running Mobile Verification...")
            mobile_context = browser.new_context(
                viewport={'width': 375, 'height': 667},
                is_mobile=True,
                has_touch=True,
                user_agent='Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1'
            )
            mobile_page = mobile_context.new_page()
            mobile_page.goto('http://127.0.0.1:8000')
            mobile_page.wait_for_selector('#home')

            # Mobile Home View
            mobile_page.screenshot(path='verification/screenshots/mobile_light.png')

            # Open Mobile Menu
            mobile_page.click('#nav-toggle', force=True)
            mobile_page.wait_for_timeout(300)
            mobile_page.screenshot(path='verification/screenshots/mobile_menu.png')

            # Close Mobile Menu
            mobile_page.click('#nav-close', force=True)
            mobile_page.wait_for_timeout(300)

            mobile_context.close()
            browser.close()

            print("UI Verification completed successfully!")

    finally:
        php_process.terminate()
        php_process.wait()

if __name__ == '__main__':
    run_verification()
