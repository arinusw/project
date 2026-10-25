import os
import time
from playwright.sync_api import sync_playwright

def run_verification():
    os.makedirs('verification/screenshots', exist_ok=True)
    os.makedirs('verification/videos', exist_ok=True)

    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)

        # 1. Desktop Verification
        desktop_context = browser.new_context(
            viewport={'width': 1280, 'height': 800},
            record_video_dir='verification/videos/'
        )
        desktop_page = desktop_context.new_page()
        desktop_page.goto('http://127.0.0.1:8000')
        desktop_page.wait_for_selector('.home__title')
        desktop_page.evaluate("window.scrollTo({top: 0, behavior: 'instant'})")
        desktop_page.screenshot(path='verification/screenshots/desktop_light.png')

        # Toggle dark theme on desktop
        theme_btn = desktop_page.locator('#theme-button')
        theme_btn.click()
        time.sleep(0.5)
        desktop_page.screenshot(path='verification/screenshots/desktop_dark.png')

        # Scroll down to reveal sections and test scroll-up
        desktop_page.evaluate("window.scrollTo(0, 1000)")
        time.sleep(0.5)
        desktop_page.screenshot(path='verification/screenshots/desktop_scrolled.png')

        # Submit contact form simulation
        desktop_page.evaluate("document.querySelector('#contact').scrollIntoView()")
        time.sleep(0.5)
        desktop_page.fill('#contact-name', 'Penguji Sistem')
        desktop_page.fill('#contact-email', 'tester@example.com')
        desktop_page.fill('#contact-subject', 'Uji Coba Layanan')
        desktop_page.fill('#contact-message', 'Halo Ar Creative, ini adalah tes pengiriman formulir otomatis.')
        desktop_page.click('#contact-form button[type="submit"]')

        desktop_page.wait_for_selector('.contact__message.success')
        time.sleep(0.5)
        desktop_page.screenshot(path='verification/screenshots/contact_submitted.png')

        desktop_context.close()

        # 2. Mobile Verification
        mobile_context = browser.new_context(
            viewport={'width': 375, 'height': 667},
            user_agent='Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15'
        )
        mobile_page = mobile_context.new_page()
        mobile_page.goto('http://127.0.0.1:8000')
        mobile_page.wait_for_selector('.home__title')
        mobile_page.screenshot(path='verification/screenshots/mobile_light.png')

        # Open mobile navigation menu
        nav_toggle = mobile_page.locator('#nav-toggle')
        nav_toggle.click(force=True)
        time.sleep(0.5)
        mobile_page.screenshot(path='verification/screenshots/mobile_menu_open.png')

        mobile_context.close()
        browser.close()

    print("UI Verification completed successfully!")

if __name__ == '__main__':
    run_verification()
