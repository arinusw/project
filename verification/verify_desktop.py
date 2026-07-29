import time
import os
from playwright.sync_api import sync_playwright

def run_desktop_verification():
    print("Starting Desktop UI Verification...")

    # Ensure screenshot directory exists
    os.makedirs('/app/verification/screenshots', exist_ok=True)
    os.makedirs('/home/jules/verification/screenshots', exist_ok=True)

    with sync_playwright() as p:
        # Launch browser (headless to run in sandbox)
        browser = p.chromium.launch(headless=True)

        # Configure viewport to 1280x800 for desktop
        context = browser.new_context(viewport={"width": 1280, "height": 800})
        page = context.new_page()

        # Open local PHP server address
        url = "http://127.0.0.1:8000/index.html"
        print(f"Navigating to {url}...")
        page.goto(url)

        # Wait for page load
        page.wait_for_timeout(1000)

        # 1. Verify Desktop UI components
        print("Verifying Desktop UI layout...")
        # Desktop should show nav link names, but hide mobile hamburger toggle
        nav_name = page.locator(".nav__name").first
        nav_toggle = page.locator("#nav-toggle")

        # Ensure nav_name is visible, toggle is hidden on desktop (min-width: 767px is active)
        if nav_name.is_visible():
            print("✓ Desktop navigation links are visible.")
        else:
            print("✗ Error: Desktop navigation links are hidden.")

        if not nav_toggle.is_visible():
            print("✓ Mobile toggle button is correctly hidden on desktop layout.")
        else:
            print("✗ Error: Mobile toggle button is visible on desktop layout.")

        # 2. Incremental scrolling to trigger ScrollReveal animations
        print("Triggering ScrollReveal entrance animations via incremental scrolling...")
        for i in range(1, 9):
            page.mouse.wheel(0, 400)
            page.wait_for_timeout(300)

        # Scroll back to top
        page.evaluate("window.scrollTo(0, 0)")
        page.wait_for_timeout(500)

        # Capture home page screenshot
        screenshot_path_home = "/app/verification/screenshots/desktop_home.png"
        page.screenshot(path=screenshot_path_home)
        # Also copy to /home/jules/verification/screenshots
        page.screenshot(path="/home/jules/verification/screenshots/desktop_home.png")
        print(f"✓ Saved desktop home screenshot to {screenshot_path_home}")

        # 3. Test Theme Toggle (Light to Dark)
        theme_button = page.locator("#theme-button")
        print("Toggling dark mode...")
        theme_button.click()
        page.wait_for_timeout(500)

        # Verify body has class 'dark-theme'
        is_dark = page.evaluate("document.body.classList.contains('dark-theme')")
        if is_dark:
            print("✓ Success: 'dark-theme' class applied to body on toggle.")
        else:
            print("✗ Error: 'dark-theme' class was not applied on click.")

        # Capture dark mode screenshot
        screenshot_path_dark = "/app/verification/screenshots/desktop_dark.png"
        page.screenshot(path=screenshot_path_dark)
        page.screenshot(path="/home/jules/verification/screenshots/desktop_dark.png")
        print(f"✓ Saved desktop dark theme screenshot to {screenshot_path_dark}")

        # Toggle theme back to light
        theme_button.click()
        page.wait_for_timeout(500)

        # 4. Form Submission under Simulation Mode
        print("Testing Contact Form async submission under simulation mode...")
        # Set hidden field simulation_mode to 1
        page.evaluate("document.getElementById('simulation-mode').value = '1'")

        # Fill inputs
        page.fill("#user_name", "Jules Software Engineer")
        page.fill("#user_email", "jules@example.com")
        page.fill("#user_project", "Halo Ar Creative! Ini adalah pengujian kirim pesan simulasi dari robot Playwright.")

        # Scroll contact form into view
        page.locator("#contact-form").scroll_into_view_if_needed()
        page.wait_for_timeout(500)

        # Submit form
        print("Submitting the contact form...")
        page.locator("#contact-form button[type='submit']").click()

        # Wait for response message
        contact_message = page.locator("#contact-message")
        contact_message.wait_for(state="visible", timeout=5000)

        message_text = contact_message.text_content()
        print(f"Contact Response Message: '{message_text}'")

        if "Simulasi sukses" in message_text or "Pesan Anda berhasil dikirim" in message_text:
            print("✓ Contact Form Asynchronous Simulation Passed successfully!")
        else:
            print("✗ Error: Unexpected response or simulation failed.")

        # Capture form submission screenshot
        screenshot_path_submit = "/app/verification/screenshots/desktop_form_submitted.png"
        page.screenshot(path=screenshot_path_submit)
        page.screenshot(path="/home/jules/verification/screenshots/desktop_form_submitted.png")
        print(f"✓ Saved form submission screenshot to {screenshot_path_submit}")

        # Scroll to education section and take a screenshot
        page.locator("#education").scroll_into_view_if_needed()
        page.wait_for_timeout(500)
        page.screenshot(path="/app/verification/screenshots/desktop_education.png")
        page.screenshot(path="/home/jules/verification/screenshots/desktop_education.png")
        print("✓ Saved desktop education screenshot.")

        browser.close()
        print("Desktop verification complete.")

if __name__ == "__main__":
    run_desktop_verification()
