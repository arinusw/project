from playwright.sync_api import sync_playwright
import time
import os

def run_tests():
    # Ensure directories exist
    os.makedirs('verification/screenshots', exist_ok=True)
    os.makedirs('verification/videos', exist_ok=True)

    with sync_playwright() as p:
        # Launch headless browser
        browser = p.chromium.launch(headless=True)

        # ==================== DESKTOP UI VERIFICATION ====================
        print("Starting Desktop UI verification...")
        context_desktop = browser.new_context(
            viewport={'width': 1280, 'height': 800},
            record_video_dir='verification/videos/'
        )
        page_desktop = context_desktop.new_page()
        page_desktop.goto('http://127.0.0.1:8000/')

        # Incremental scroll to trigger ScrollReveal animations on Desktop
        print("Scrolling page incrementally on Desktop...")
        for i in range(1, 10):
            page_desktop.evaluate(f"window.scrollTo(0, {i * 450})")
            time.sleep(0.2)

        page_desktop.evaluate("window.scrollTo(0, 0)")
        time.sleep(0.5)

        # Capture desktop homepage screenshot
        page_desktop.screenshot(path='verification/screenshots/desktop_home.png', full_page=True)
        print("Desktop home screenshot saved.")

        # Test Dark/Light Theme Switcher on Desktop
        theme_btn = page_desktop.locator('#theme-button')
        body = page_desktop.locator('body')

        is_dark_init = "dark-theme" in (body.get_attribute("class") or "")
        print(f"Initial theme state: {'Dark' if is_dark_init else 'Light'}")

        # Toggle theme to dark mode
        theme_btn.click()
        time.sleep(0.5)
        is_dark_after = "dark-theme" in (body.get_attribute("class") or "")
        print(f"Theme state after toggling: {'Dark' if is_dark_after else 'Light'}")
        page_desktop.screenshot(path='verification/screenshots/desktop_dark_mode.png')

        # Toggle theme back to light mode for form test
        theme_btn.click()
        time.sleep(0.5)

        # Test Contact form simulation mode
        print("Submitting Contact Form in simulation mode...")
        # Set hidden simulation mode field to 1
        page_desktop.evaluate("document.getElementById('simulation_mode').value = '1'")

        page_desktop.fill('#user_name', 'Budi Pratama')
        page_desktop.fill('#user_email', 'budi.pratama@arcreative.id')
        page_desktop.fill('#user_project', 'Ingin berkolaborasi membuat website edutech dan video kreatif.')

        # Submit the contact form
        page_desktop.click('button[type="submit"]')

        # Wait for response message
        msg_locator = page_desktop.locator('#contact-message')
        msg_locator.wait_for(state="visible", timeout=6000)
        time.sleep(1) # Wait for text to settle

        status_text = msg_locator.inner_text()
        print(f"Form response status: {status_text}")
        page_desktop.screenshot(path='verification/screenshots/desktop_form_success.png')

        context_desktop.close()

        # ==================== MOBILE UI VERIFICATION ====================
        print("\nStarting Mobile UI verification...")
        context_mobile = browser.new_context(
            viewport={'width': 375, 'height': 667},
            is_mobile=True,
            record_video_dir='verification/videos/'
        )
        page_mobile = context_mobile.new_page()
        page_mobile.goto('http://127.0.0.1:8000/')
        time.sleep(1)

        # Check that logo is hidden on mobile layout (max-width 767px)
        logo = page_mobile.locator('.nav__logo')
        logo_visible = logo.is_visible()
        print(f"Mobile Nav Logo hidden check: {'PASS' if not logo_visible else 'FAIL'}")

        # Open Mobile Navigation Menu Drawer
        toggle_btn = page_mobile.locator('#nav-toggle')
        menu = page_mobile.locator('#nav-menu')

        print("Toggling mobile menu drawer...")
        toggle_btn.click(force=True)
        time.sleep(0.5)

        menu_class = menu.get_attribute("class") or ""
        is_menu_shown = "show-menu" in menu_class
        print(f"Mobile menu opened check: {'PASS' if is_menu_shown else 'FAIL'}")
        page_mobile.screenshot(path='verification/screenshots/mobile_menu_open.png')

        # Close Mobile Navigation Menu Drawer
        close_btn = page_mobile.locator('#nav-close')
        close_btn.click(force=True)
        time.sleep(0.5)

        menu_class_closed = menu.get_attribute("class") or ""
        is_menu_closed = "show-menu" not in menu_class_closed
        print(f"Mobile menu closed check: {'PASS' if is_menu_closed else 'FAIL'}")

        # Incremental scroll to trigger ScrollReveal animations on Mobile
        print("Scrolling page incrementally on Mobile...")
        for i in range(1, 15):
            page_mobile.evaluate(f"window.scrollTo(0, {i * 320})")
            time.sleep(0.1)

        page_mobile.evaluate("window.scrollTo(0, 0)")
        time.sleep(0.5)

        # Capture mobile homepage screenshot
        page_mobile.screenshot(path='verification/screenshots/mobile_home.png', full_page=True)
        print("Mobile home screenshot saved.")

        context_mobile.close()
        browser.close()
        print("\nUI verification complete!")

if __name__ == "__main__":
    run_tests()
