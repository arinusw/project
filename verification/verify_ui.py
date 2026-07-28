import os
import time
from playwright.sync_api import sync_playwright

def incremental_scroll(page):
    """
    Perform incremental scrolling with pauses to trigger entrance animations
    for all sections when ScrollReveal is active.
    """
    for i in range(10):
        page.mouse.wheel(0, 500)
        time.sleep(0.3)
    # Scroll back to top
    page.evaluate("window.scrollTo(0, 0)")
    time.sleep(0.5)

def run_tests():
    print("Starting Playwright verification...")

    # Create output directories if they don't exist
    os.makedirs("/home/jules/verification/screenshots", exist_ok=True)
    os.makedirs("/home/jules/verification/videos", exist_ok=True)

    with sync_playwright() as p:
        # 1. Desktop Verification (Viewport: 1280x800)
        print("Launching Desktop browser...")
        desktop_browser = p.chromium.launch(headless=True)
        desktop_context = desktop_browser.new_context(
            viewport={"width": 1280, "height": 800},
            record_video_dir="/home/jules/verification/videos"
        )
        desktop_page = desktop_context.new_page()

        # Navigate to the site
        desktop_page.goto("http://127.0.0.1:8000")
        time.sleep(1)

        # Incremental scroll to trigger animations
        incremental_scroll(desktop_page)

        # Capture Desktop Landing page
        desktop_page.screenshot(path="/home/jules/verification/screenshots/desktop_landing.png", full_page=True)
        print("Desktop landing screenshot saved.")

        # Test Dark Theme Toggle on Desktop
        theme_btn = desktop_page.locator("#theme-button")
        theme_btn.wait_for(state="visible")
        theme_btn.click()
        time.sleep(0.5)

        # Capture Dark Theme layout
        desktop_page.screenshot(path="/home/jules/verification/screenshots/desktop_dark_theme.png", full_page=True)
        print("Desktop dark theme screenshot saved.")

        # Switch back to light theme for consistency
        theme_btn.click()
        time.sleep(0.5)

        # Test Contact Form Submission (Simulation Mode = 1)
        print("Filling out contact form on Desktop...")
        desktop_page.evaluate("document.getElementById('simulation_mode').value = '1'")
        desktop_page.fill("#user_name", "Aria Pratama")
        desktop_page.fill("#user_email", "aria.pratama@example.com")
        desktop_page.fill("#user_project", "Saya ingin memesan website portofolio studio kreatif.")

        # Submit
        submit_btn = desktop_page.locator("#contact-form button[type='submit']")
        submit_btn.wait_for(state="visible")
        submit_btn.click()

        # Wait for success message
        contact_msg = desktop_page.locator("#contact-message")
        contact_msg.wait_for(state="visible")

        # Capture submission success
        desktop_page.screenshot(path="/home/jules/verification/screenshots/desktop_contact_success.png")
        print("Desktop contact submission success screenshot saved.")

        desktop_context.close()
        desktop_browser.close()

        # 2. Mobile Verification (Viewport: 375x667)
        print("Launching Mobile browser...")
        mobile_browser = p.chromium.launch(headless=True)
        mobile_context = mobile_browser.new_context(
            viewport={"width": 375, "height": 667},
            is_mobile=True,
            has_touch=True,
            record_video_dir="/home/jules/verification/videos"
        )
        mobile_page = mobile_context.new_page()

        # Navigate to the site
        mobile_page.goto("http://127.0.0.1:8000")
        time.sleep(1)

        # Incremental scroll on mobile
        incremental_scroll(mobile_page)

        # Capture Mobile landing page
        mobile_page.screenshot(path="/home/jules/verification/screenshots/mobile_landing.png", full_page=True)
        print("Mobile landing screenshot saved.")

        # Open mobile navigation menu using toggle trigger
        mobile_toggle = mobile_page.locator("#nav-toggle")
        mobile_toggle.wait_for(state="visible")
        # Passing force=True helps bypass ScrollReveal overlays
        mobile_toggle.click(force=True)
        time.sleep(0.5)

        # Take screenshot of mobile drawer open state
        mobile_page.screenshot(path="/home/jules/verification/screenshots/mobile_menu_open.png")
        print("Mobile menu open screenshot saved.")

        # Interact with nav links scoped inside #nav-menu
        nav_services_link = mobile_page.locator("#nav-menu a[href='#services']")
        nav_services_link.wait_for(state="visible")
        nav_services_link.click(force=True)
        time.sleep(0.5)

        mobile_context.close()
        mobile_browser.close()

    print("Playwright verification completed successfully!")

if __name__ == "__main__":
    run_tests()
