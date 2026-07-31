import os
import sys
import time
import subprocess
from playwright.sync_api import sync_playwright

def run_verification():
    # 1. Start the PHP Built-in Server
    print("Starting local PHP server...")
    # Kill any existing server on port 8000
    subprocess.run("kill $(lsof -t -i :8000) 2>/dev/null || true", shell=True)

    server_process = subprocess.Popen(
        "php -S 127.0.0.1:8000 -t ar-creative > php_server.log 2>&1 &",
        shell=True,
        preexec_fn=os.setsid
    )
    time.sleep(2) # Give server time to start up

    # Create directories if they do not exist
    os.makedirs("verification/screenshots", exist_ok=True)
    os.makedirs("verification/videos", exist_ok=True)

    print("Starting Playwright verification...")
    with sync_playwright() as p:
        # Launch browser with video recording enabled
        browser = p.chromium.launch(headless=True)

        # ---------------------------------------------
        # DESKTOP JOURNEY
        # ---------------------------------------------
        print("Running Desktop Verification...")
        desktop_context = browser.new_context(
            viewport={"width": 1280, "height": 800},
            record_video_dir="verification/videos/"
        )
        desktop_page = desktop_context.new_page()

        # Navigate
        desktop_page.goto("http://127.0.0.1:8000")
        desktop_page.wait_for_timeout(1000)

        # Incremental scroll to trigger ScrollReveal animations
        print("Slowing down and scrolling page incrementally to trigger entrance animations...")
        for i in range(1, 9):
            desktop_page.evaluate(f"window.scrollTo(0, {i * 500})")
            desktop_page.wait_for_timeout(200)

        # Scroll back up to verify theme switching
        desktop_page.evaluate("window.scrollTo(0, 0)")
        desktop_page.wait_for_timeout(500)

        # Click light/dark theme switch
        print("Toggling theme...")
        theme_button = desktop_page.locator("#theme-button")
        theme_button.wait_for(state="visible")
        theme_button.click()
        desktop_page.wait_for_timeout(500)

        # Verify body has class 'dark-theme'
        is_dark = desktop_page.evaluate("document.body.classList.contains('dark-theme')")
        print(f"Is dark theme active? {is_dark}")

        # Navigate to Services using header nav menu link
        # Scope menu search to #nav-menu as per memory guidelines
        print("Navigating to Layanan section...")
        desktop_page.locator("#nav-menu a[href='#services']").click()
        desktop_page.wait_for_timeout(1000)

        # Submit contact form in simulated mode
        print("Navigating to Kontak section and submitting contact form...")
        desktop_page.locator("#nav-menu a[href='#contact']").click()
        desktop_page.wait_for_timeout(500)

        desktop_page.locator("#user-name").fill("John Doe")
        desktop_page.locator("#user-email").fill("john@example.com")
        desktop_page.locator("#user-project").fill("Saya ingin membuat landing page studio digital.")

        # Submit form
        desktop_page.locator("#contact-form button[type='submit']").click()

        # Wait for dynamic success feedback message
        contact_msg = desktop_page.locator("#contact-message")
        contact_msg.wait_for(state="visible")
        feedback_text = contact_msg.inner_text()
        print(f"Contact feedback text: {feedback_text}")

        # Scroll back up and capture desktop screenshot
        desktop_page.evaluate("window.scrollTo(0, 0)")
        desktop_page.wait_for_timeout(1000)
        desktop_page.screenshot(path="verification/screenshots/desktop_view.png", full_page=True)
        print("Desktop view screenshot captured.")

        # Close desktop context to save video
        desktop_context.close()

        # ---------------------------------------------
        # MOBILE JOURNEY
        # ---------------------------------------------
        print("Running Mobile Verification...")
        mobile_context = browser.new_context(
            viewport={"width": 375, "height": 667},
            is_mobile=True,
            record_video_dir="verification/videos/"
        )
        mobile_page = mobile_context.new_page()

        # Navigate
        mobile_page.goto("http://127.0.0.1:8000")
        mobile_page.wait_for_timeout(1000)

        # Open mobile navigation menu using force=True to bypass potential ScrollReveal overlay
        print("Opening mobile navigation menu...")
        nav_toggle = mobile_page.locator("#nav-toggle")
        nav_toggle.wait_for(state="visible")
        nav_toggle.click(force=True)
        mobile_page.wait_for_timeout(500)

        # Verify menu visibility
        is_menu_visible = mobile_page.evaluate("document.getElementById('nav-menu').classList.contains('show-menu')")
        print(f"Is mobile menu show-menu active? {is_menu_visible}")

        # Click close button to close mobile drawer
        print("Closing mobile navigation menu...")
        nav_close = mobile_page.locator("#nav-close")
        nav_close.wait_for(state="visible")
        nav_close.click(force=True)
        mobile_page.wait_for_timeout(500)

        # Incremental scroll mobile viewport
        print("Scrolling mobile page incrementally...")
        for i in range(1, 10):
            mobile_page.evaluate(f"window.scrollTo(0, {i * 400})")
            mobile_page.wait_for_timeout(100)

        # Scroll back to top and capture mobile screenshot
        mobile_page.evaluate("window.scrollTo(0, 0)")
        mobile_page.wait_for_timeout(1000)
        mobile_page.screenshot(path="verification/screenshots/mobile_view.png", full_page=True)
        print("Mobile view screenshot captured.")

        # Close mobile context to save video
        mobile_context.close()
        browser.close()

    # 2. Terminate the PHP Server
    print("Terminating local PHP server...")
    subprocess.run("kill $(lsof -t -i :8000) 2>/dev/null || true", shell=True)
    print("Verification complete.")

if __name__ == "__main__":
    run_verification()
