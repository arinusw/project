import os
import time
from playwright.sync_api import sync_playwright

def trigger_scroll_animations(page):
    # Scroll through the page incrementally to trigger ScrollReveal entrance animations
    for i in range(10):
        page.mouse.wheel(0, 500)
        time.sleep(0.2)
    # Scroll back to top
    page.evaluate("window.scrollTo({top: 0, behavior: 'instant'})")
    time.sleep(0.5)

def run_tests():
    os.makedirs('verification/screenshots', exist_ok=True)
    os.makedirs('verification/videos', exist_ok=True)

    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)

        # 1. Desktop Visual Verification & Interactions
        print("Testing Desktop Layout...")
        desktop_context = browser.new_context(
            viewport={'width': 1280, 'height': 800},
            record_video_dir='verification/videos/'
        )
        desktop_page = desktop_context.new_page()
        desktop_page.goto('http://127.0.0.1:8000')
        desktop_page.wait_for_load_state('networkidle')

        # Trigger scroll animations
        trigger_scroll_animations(desktop_page)

        # Capture Desktop Light Theme Screenshot
        desktop_page.screenshot(path='verification/screenshots/desktop_light.png', full_page=False)

        # Toggle Dark Theme
        theme_button = desktop_page.locator('#theme-button')
        theme_button.click()
        time.sleep(0.5)

        # Capture Desktop Dark Theme Screenshot
        desktop_page.screenshot(path='verification/screenshots/desktop_dark.png', full_page=False)

        # Test Contact Form Submission in Simulation Mode
        print("Testing Contact Form Submission...")
        desktop_page.locator('#contact').scroll_into_view_if_needed()
        time.sleep(0.5)

        desktop_page.fill('#name', 'Pengunjung Uji')
        desktop_page.fill('#email', 'pengunjung@example.com')
        desktop_page.fill('#message', 'Halo Ar Creative, ini adalah pesan pengujian otomatis.')

        # Append simulation mode field dynamically or submit form
        desktop_page.evaluate("""() => {
            const form = document.getElementById('contact-form');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'simulation_mode';
            input.value = '1';
            form.appendChild(input);
        }""")

        desktop_page.click('.contact__submit-btn')
        desktop_page.locator('#contact-message').wait_for(state="visible")
        time.sleep(1)

        # Capture Contact Form Success Screenshot
        desktop_page.screenshot(path='verification/screenshots/contact_success.png', full_page=False)

        desktop_context.close()

        # 2. Mobile Visual Verification & Interactions
        print("Testing Mobile Layout...")
        mobile_context = browser.new_context(
            viewport={'width': 375, 'height': 667},
            record_video_dir='verification/videos/'
        )
        mobile_page = mobile_context.new_page()
        mobile_page.goto('http://127.0.0.1:8000')
        mobile_page.wait_for_load_state('networkidle')

        # Trigger scroll animations
        trigger_scroll_animations(mobile_page)

        # Capture Mobile Home Screenshot
        mobile_page.screenshot(path='verification/screenshots/mobile_light.png', full_page=False)

        # Open Mobile Navigation Menu
        mobile_toggle = mobile_page.locator('#nav-toggle')
        mobile_toggle.click(force=True)
        time.sleep(0.5)

        # Capture Mobile Menu Screenshot
        mobile_page.screenshot(path='verification/screenshots/mobile_menu.png', full_page=False)

        # Close Mobile Menu
        mobile_close = mobile_page.locator('#nav-close')
        mobile_close.click(force=True)
        time.sleep(0.5)

        mobile_context.close()
        browser.close()

    print("UI Verification Completed Successfully!")

if __name__ == '__main__':
    run_tests()
