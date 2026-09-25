import time
import os
from playwright.sync_api import sync_playwright

def trigger_scroll_animations(page):
    # Scroll incrementally to trigger ScrollReveal animations
    for i in range(1, 6):
        page.mouse.wheel(0, 800)
        time.sleep(0.2)
    # Scroll back to top
    page.evaluate("window.scrollTo(0, 0)")
    time.sleep(0.5)

def run_verification():
    os.makedirs('verification/screenshots', exist_ok=True)
    os.makedirs('verification/videos', exist_ok=True)

    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)

        # 1. Desktop Verification
        desktop_context = browser.new_context(viewport={'width': 1280, 'height': 800})
        desktop_page = desktop_context.new_page()
        desktop_page.goto('http://127.0.0.1:8000')
        desktop_page.wait_for_load_state('networkidle')
        trigger_scroll_animations(desktop_page)
        desktop_page.evaluate("window.scrollTo(0, 0)")
        time.sleep(0.5)

        # Light theme screenshot
        desktop_page.screenshot(path='verification/screenshots/desktop_light.png')
        print("Captured desktop_light.png")

        # Theme toggle to Dark
        theme_btn = desktop_page.locator('#theme-button')
        theme_btn.click()
        time.sleep(0.5)
        desktop_page.screenshot(path='verification/screenshots/desktop_dark.png')
        print("Captured desktop_dark.png")

        # Form submission simulation
        desktop_page.evaluate("window.scrollTo(0, document.body.scrollHeight)")
        time.sleep(0.5)
        desktop_page.fill('#name', 'Budi Santoso')
        desktop_page.fill('#email', 'budi@example.com')
        desktop_page.fill('#message', 'Halo Ar Creative, saya tertarik dengan jasa pembuatan website dan edukasi teknologi.')

        # Add hidden field simulation_mode = 1 to contact form for reliable test response
        desktop_page.evaluate("""
            const form = document.getElementById('contact-form');
            let input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'simulation_mode';
            input.value = '1';
            form.appendChild(input);
        """)

        desktop_page.click('.contact__submit-btn')
        desktop_page.locator('#contact-message').wait_for(state="visible")
        time.sleep(1)
        desktop_page.screenshot(path='verification/screenshots/desktop_contact_success.png')
        print("Captured desktop_contact_success.png")

        desktop_context.close()

        # 2. Mobile Verification
        mobile_context = browser.new_context(viewport={'width': 375, 'height': 667})
        mobile_page = mobile_context.new_page()
        mobile_page.goto('http://127.0.0.1:8000')
        mobile_page.wait_for_load_state('networkidle')
        trigger_scroll_animations(mobile_page)
        mobile_page.evaluate("window.scrollTo(0, 0)")
        time.sleep(0.5)

        mobile_page.screenshot(path='verification/screenshots/mobile_home.png')
        print("Captured mobile_home.png")

        # Mobile drawer navigation toggle
        mobile_page.click('#nav-toggle', force=True)
        time.sleep(0.5)
        mobile_page.screenshot(path='verification/screenshots/mobile_menu_open.png')
        print("Captured mobile_menu_open.png")

        mobile_context.close()
        browser.close()

if __name__ == '__main__':
    run_verification()
