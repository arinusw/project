import os
import time
from playwright.sync_api import sync_playwright

def trigger_scroll_animations(page):
    # Scroll through the page to trigger ScrollReveal animations
    for i in range(0, 3000, 400):
        page.mouse.wheel(0, 400)
        page.wait_for_timeout(200)
    page.evaluate("window.scrollTo(0, 0)")
    page.wait_for_timeout(300)

def run_tests():
    os.makedirs('verification/screenshots', exist_ok=True)
    os.makedirs('verification/videos', exist_ok=True)

    with sync_playwright() as p:
        # Launch browser
        browser = p.chromium.launch(headless=True)

        # 1. Desktop Visual Verification
        context_desktop = browser.new_context(
            viewport={'width': 1280, 'height': 800},
            record_video_dir='verification/videos/'
        )
        page_desktop = context_desktop.new_page()
        page_desktop.goto('http://127.0.0.1:8000/index.html')
        page_desktop.wait_for_load_state('networkidle')

        trigger_scroll_animations(page_desktop)

        # Light theme screenshot
        page_desktop.screenshot(path='verification/screenshots/desktop_light.png', full_page=False)

        # Toggle Dark Theme
        theme_btn = page_desktop.locator('#theme-button')
        theme_btn.click()
        page_desktop.wait_for_timeout(300)
        page_desktop.screenshot(path='verification/screenshots/desktop_dark.png', full_page=False)

        context_desktop.close()

        # 2. Mobile Visual Verification & Interaction
        context_mobile = browser.new_context(
            viewport={'width': 375, 'height': 667},
            record_video_dir='verification/videos/'
        )
        page_mobile = context_mobile.new_page()
        page_mobile.goto('http://127.0.0.1:8000/index.html')
        page_mobile.wait_for_load_state('networkidle')

        trigger_scroll_animations(page_mobile)

        # Open Mobile Navigation Menu
        nav_toggle = page_mobile.locator('#nav-toggle')
        nav_toggle.click(force=True)
        page_mobile.wait_for_timeout(300)
        page_mobile.screenshot(path='verification/screenshots/mobile_menu.png', full_page=False)

        # Close Mobile Navigation Menu
        nav_close = page_mobile.locator('#nav-close')
        nav_close.click(force=True)
        page_mobile.wait_for_timeout(300)

        # Navigate to Contact Section directly via hash or scrolling
        page_mobile.evaluate("document.getElementById('contact').scrollIntoView()")
        page_mobile.wait_for_timeout(300)

        # Fill Contact Form & Simulate Submission
        page_mobile.fill('#name', 'Ahmad Test')
        page_mobile.fill('#email', 'ahmad@example.com')
        page_mobile.fill('#subject', 'Konsultasi Project')
        page_mobile.fill('#message', 'Halo Ar Creative, saya berminat dengan jasa pembuatan website.')

        # Inject hidden input simulation_mode = 1 to test AJAX response
        page_mobile.evaluate("""() => {
            const form = document.getElementById('contact-form');
            const simInput = document.createElement('input');
            simInput.type = 'hidden';
            simInput.name = 'simulation_mode';
            simInput.value = '1';
            form.appendChild(simInput);
        }""")

        page_mobile.click('#contact-form button[type="submit"]')
        page_mobile.wait_for_selector('#contact-message.success', state='visible')

        page_mobile.screenshot(path='verification/screenshots/mobile_contact_success.png', full_page=False)

        context_mobile.close()

        browser.close()
        print("UI Verification Completed Successfully!")

if __name__ == '__main__':
    run_tests()
