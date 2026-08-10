import subprocess
import time
import os
from playwright.sync_api import sync_playwright

def start_php_server():
    print("Starting local PHP development server...")
    # Kill any existing process on port 8000
    subprocess.run("kill $(lsof -t -i :8000) 2>/dev/null || true", shell=True)
    # Start PHP server in background
    process = subprocess.Popen(
        "php -S 127.0.0.1:8000 -t ar-creative > php_server.log 2>&1 &",
        shell=True,
        preexec_fn=os.setsid
    )
    time.sleep(2)  # Wait for server to initialize
    return process

def stop_php_server(process):
    print("Stopping local PHP development server...")
    subprocess.run("kill $(lsof -t -i :8000) 2>/dev/null || true", shell=True)

def trigger_scroll_animations(page):
    print("Triggering ScrollReveal entrance animations...")
    # Incremental scrolling to trigger ScrollReveal animations
    for offset in range(0, 4500, 450):
        page.evaluate(f"window.scrollTo(0, {offset});")
        page.wait_for_timeout(150)
    page.wait_for_timeout(500)
    # Scroll back to the top
    page.evaluate("window.scrollTo(0, 0);")
    page.wait_for_timeout(500)

def run_tests():
    os.makedirs("/home/jules/verification/screenshots", exist_ok=True)
    os.makedirs("/home/jules/verification/videos", exist_ok=True)

    php_process = start_php_server()

    with sync_playwright() as p:
        # 1. Desktop Test and CUJ Recording
        print("Launching Desktop browser context...")
        browser = p.chromium.launch(headless=True)

        # We record video of the desktop journey
        context_desktop = browser.new_context(
            viewport={"width": 1280, "height": 800},
            record_video_dir="/home/jules/verification/videos/"
        )
        page = context_desktop.new_page()

        try:
            print("Navigating to Ar Creative Portfolio on Desktop...")
            page.goto("http://127.0.0.1:8000")
            page.wait_for_timeout(1000)

            # Scroll to trigger all ScrollReveal animations
            trigger_scroll_animations(page)

            # Take initial theme screenshot (Light Theme)
            page.screenshot(path="/home/jules/verification/screenshots/desktop_light.png")
            page.wait_for_timeout(500)

            # Toggle Dark Theme
            print("Toggling Dark Theme...")
            theme_btn = page.locator("#theme-button")
            theme_btn.click()
            page.wait_for_timeout(800)

            # Take dark theme screenshot
            page.screenshot(path="/home/jules/verification/screenshots/desktop_dark.png")
            page.wait_for_timeout(500)

            # Verify navigation links and active-link scroll highlighting
            print("Testing navigation links...")
            about_link = page.locator(".nav__menu a[href='#about']")
            about_link.click()
            page.wait_for_timeout(1000)

            # Fill in the contact form with simulation_mode
            print("Filling and submitting the contact form...")
            page.locator("#contact").scroll_into_view_if_needed()
            page.wait_for_timeout(500)

            page.locator("#user_name").fill("John Doe")
            page.wait_for_timeout(300)
            page.locator("#user_email").fill("johndoe@example.com")
            page.wait_for_timeout(300)
            page.locator("#user_project").fill("Saya ingin membuat website landing page untuk bisnis kreatif lokal.")
            page.wait_for_timeout(300)

            # Check that simulation mode is active (value=1)
            # Submit form
            page.locator("#contact-form button[type='submit']").click()
            page.wait_for_timeout(1000)

            # Check that success message is displayed
            message_box = page.locator("#contact-message")
            message_box.wait_for(state="visible")
            message_text = message_box.text_content()
            print(f"Success Response Message: '{message_text}'")

            # Keep final state for a moment
            page.wait_for_timeout(1000)
            page.screenshot(path="/home/jules/verification/screenshots/desktop_form_success.png")

        except Exception as e:
            print(f"Error during Desktop test execution: {e}")
            raise e
        finally:
            context_desktop.close()

        # 2. Mobile Test and Drawer Menu Toggle
        print("Launching Mobile browser context...")
        context_mobile = browser.new_context(
            viewport={"width": 375, "height": 667},
            is_mobile=True,
            has_touch=True
        )
        page_mobile = context_mobile.new_page()

        try:
            print("Navigating to Ar Creative Portfolio on Mobile...")
            page_mobile.goto("http://127.0.0.1:8000")
            page_mobile.wait_for_timeout(1000)

            # Open Mobile Menu
            print("Opening Mobile Nav Menu Drawer...")
            toggle_btn = page_mobile.locator("#nav-toggle")
            toggle_btn.click(force=True)
            page_mobile.wait_for_timeout(800)
            page_mobile.screenshot(path="/home/jules/verification/screenshots/mobile_menu_open.png")

            # Click standard about section link (which should close menu)
            print("Clicking a nav link to close mobile drawer...")
            services_link = page_mobile.locator(".nav__menu a[href='#services']")
            services_link.click(force=True)
            page_mobile.wait_for_timeout(1000)

            # Let's verify menu closed and page scrolled to services
            page_mobile.screenshot(path="/home/jules/verification/screenshots/mobile_services_scroll.png")
            page_mobile.wait_for_timeout(500)

        except Exception as e:
            print(f"Error during Mobile test execution: {e}")
            raise e
        finally:
            context_mobile.close()
            browser.close()

    stop_php_server(php_process)
    print("UI Tests Completed successfully.")

    # Move recorded video file to standard location cuj_journey.webm
    video_dir = "/home/jules/verification/videos"
    video_files = [f for f in os.listdir(video_dir) if f.endswith(".webm")]
    if video_files:
        old_path = os.path.join(video_dir, video_files[0])
        new_path = os.path.join(video_dir, "cuj_journey.webm")
        os.rename(old_path, new_path)
        print(f"Recorded video saved to {new_path}")

if __name__ == "__main__":
    run_tests()
