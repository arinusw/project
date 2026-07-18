import os
import subprocess
import time
from playwright.sync_api import sync_playwright

def run_cuj():
    print("Starting PHP built-in server...")
    # Kill any existing process on port 8000
    os.system("kill $(lsof -t -i :8000) 2>/dev/null || true")

    # Start the PHP server on 127.0.0.1:8000
    server_process = subprocess.Popen(
        "php -S 127.0.0.1:8000 -t ar-creative > php_server.log 2>&1",
        shell=True
    )

    # Wait for the server to spin up
    time.sleep(2)

    # Create directories
    os.makedirs("verification/screenshots", exist_ok=True)
    os.makedirs("verification/videos", exist_ok=True)

    base_url = "http://127.0.0.1:8000"

    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)

        # 1. Desktop Context with Video Recording
        print("Starting Desktop CUJ with video...")
        context = browser.new_context(
            viewport={"width": 1280, "height": 800},
            record_video_dir="verification/videos"
        )
        page = context.new_page()

        # Go to home
        page.goto(base_url)
        page.wait_for_timeout(1000)

        # Click on "Lihat Layanan" button
        print("Clicking Lihat Layanan button...")
        page.click("text=Lihat Layanan")
        page.wait_for_timeout(800)

        # Toggle Dark Theme
        print("Toggling dark theme...")
        page.click("#theme-button")
        page.wait_for_timeout(800)

        # Toggle Dark Theme off (back to light)
        print("Toggling theme back to light...")
        page.click("#theme-button")
        page.wait_for_timeout(800)

        # Fill Contact Form
        print("Scrolling and filling contact form...")
        page.locator("#contact").scroll_into_view_if_needed()
        page.wait_for_timeout(500)

        # Set simulation mode to 1
        page.evaluate("document.getElementById('simulation_mode').value = '1'")

        page.fill("#user_name", "Arinus Wantik")
        page.wait_for_timeout(300)
        page.fill("#user_email", "arinus.wantik@example.com")
        page.wait_for_timeout(300)
        page.fill("#user_project", "Halo Ar Creative! Desain portfolio studio ini sangat elegan dan responsif.")
        page.wait_for_timeout(300)

        # Submit Form
        print("Submitting contact form...")
        page.click("form#contact-form button[type='submit']")
        page.wait_for_timeout(1500)

        # Take final desktop screenshot
        screenshot_path = "verification/screenshots/desktop_cuj_final.png"
        page.screenshot(path=screenshot_path)
        print(f"Captured screenshot to {screenshot_path}")

        # Close context to save video
        context.close()

        # 2. Mobile Context with Video Recording
        print("\nStarting Mobile CUJ with video...")
        mobile_context = browser.new_context(
            viewport={"width": 375, "height": 667},
            is_mobile=True,
            has_touch=True,
            record_video_dir="verification/videos"
        )
        mobile_page = mobile_context.new_page()

        mobile_page.goto(base_url)
        mobile_page.wait_for_timeout(1000)

        # Toggle Mobile Menu
        print("Opening mobile menu...")
        mobile_page.click("#nav-toggle", force=True)
        mobile_page.wait_for_timeout(1000)

        # Click menu item "Layanan" to navigate
        print("Navigating to Layanan on Mobile...")
        mobile_page.click("#nav-menu a[href='#services']", force=True)
        mobile_page.wait_for_timeout(1000)

        # Take mobile screenshot
        mobile_screenshot_path = "verification/screenshots/mobile_cuj_final.png"
        mobile_page.screenshot(path=mobile_screenshot_path)
        print(f"Captured mobile screenshot to {mobile_screenshot_path}")

        mobile_context.close()
        browser.close()

    print("\nShutting down PHP server...")
    server_process.terminate()
    server_process.wait()
    print("PHP server stopped.")

if __name__ == "__main__":
    run_cuj()
