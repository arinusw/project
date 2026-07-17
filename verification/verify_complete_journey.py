import os
import subprocess
import time
import sys
from playwright.sync_api import sync_playwright

def start_php_server():
    print("Starting local PHP built-in server on 127.0.0.1:8000...")
    # Kill existing server if any
    subprocess.run("kill $(lsof -t -i :8000) 2>/dev/null || true", shell=True)

    # Run the server
    proc = subprocess.Popen(
        "php -S 127.0.0.1:8000 -t ar-creative > php_server.log 2>&1 &",
        shell=True,
        preexec_fn=os.setsid
    )
    # Give the server a couple seconds to boot
    time.sleep(2)
    return proc

def run_ui_tests():
    proc = start_php_server()
    success = True

    try:
        with sync_playwright() as p:
            print("Launching headless Chromium browser...")
            browser = p.chromium.launch(headless=True)

            # --- DESKTOP VIEWPORT TEST (1280x800) ---
            print("\n--- Running Desktop Tests ---")
            desktop_context = browser.new_context(viewport={"width": 1280, "height": 800})
            desktop_page = desktop_context.new_page()

            print("Navigating to http://127.0.0.1:8000 ...")
            desktop_page.goto("http://127.0.0.1:8000")

            # Incremental scrolling to trigger ScrollReveal animations
            print("Performing incremental scrolling to activate ScrollReveal animations...")
            for i in range(1, 6):
                desktop_page.evaluate(f"window.scrollTo(0, {i * 500})")
                time.sleep(0.5)
            desktop_page.evaluate("window.scrollTo(0, 0)")
            time.sleep(1)

            # Capture Desktop Full Page Screenshot
            screenshot_path_desktop = "verification/screenshots/desktop_full_page.png"
            desktop_page.screenshot(path=screenshot_path_desktop, full_page=True)
            print(f"Captured desktop screenshot: {screenshot_path_desktop}")

            # Validate titles & texts
            title = desktop_page.title()
            print(f"Page Title: {title}")
            assert "Ar Creative" in title, f"Title does not match! Got: {title}"

            # Test Dark Mode Toggle on Desktop
            print("Testing Dark Mode toggle...")
            theme_btn = desktop_page.locator("#theme-button")
            theme_btn.click()
            time.sleep(0.5)
            is_dark = desktop_page.evaluate("document.body.classList.contains('dark-theme')")
            print(f"Dark mode active after click: {is_dark}")
            assert is_dark, "Dark theme class not applied to body!"

            desktop_page.screenshot(path="verification/screenshots/desktop_dark_mode.png")

            # Revert to Light Mode
            theme_btn.click()
            time.sleep(0.5)

            # Test contact form submission (using Simulation Mode)
            print("Testing contact form submission with simulation_mode=1...")
            # Set simulation_mode value to 1 via JS evaluation or selector
            desktop_page.evaluate("document.getElementById('simulation_mode').value = '1'")

            desktop_page.fill("#user_name", "Alat Penguji")
            desktop_page.fill("#user_email", "penguji@example.com")
            desktop_page.fill("#user_project", "Ini adalah pesan otomatis dari sistem pengujian visual.")

            # Click submit
            desktop_page.click("button[type='submit']")

            # Wait for response message
            desktop_page.wait_for_selector("#contact-message")
            time.sleep(1.5)

            response_msg = desktop_page.locator("#contact-message").inner_text()
            print(f"Contact Response Message: {response_msg}")
            assert "berhasil" in response_msg.lower() or "terima" in response_msg.lower(), f"Unexpected contact form response: {response_msg}"

            desktop_page.screenshot(path="verification/screenshots/desktop_form_success.png")

            desktop_context.close()

            # --- MOBILE VIEWPORT TEST (375x667) ---
            print("\n--- Running Mobile Tests ---")
            mobile_context = browser.new_context(
                viewport={"width": 375, "height": 667},
                user_agent="Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1"
            )
            mobile_page = mobile_context.new_page()

            print("Navigating to http://127.0.0.1:8000 (Mobile)...")
            mobile_page.goto("http://127.0.0.1:8000")
            time.sleep(1)

            # Check bottom-menu navigation visibility
            nav_menu_bottom = mobile_page.evaluate("window.getComputedStyle(document.getElementById('header')).bottom")
            print(f"Header bottom position: {nav_menu_bottom}")
            assert "0px" in nav_menu_bottom, "Mobile bottom menu bar is not fixed to the bottom!"

            # Test Mobile Menu toggle click
            print("Opening mobile menu...")
            mobile_page.click("#nav-toggle")
            time.sleep(0.5)

            is_menu_shown = mobile_page.evaluate("document.getElementById('nav-menu').classList.contains('show-menu')")
            print(f"Mobile menu show-menu active: {is_menu_shown}")
            assert is_menu_shown, "Mobile menu show-menu class was not added on toggle click!"

            mobile_page.screenshot(path="verification/screenshots/mobile_menu_open.png")

            # Close mobile menu
            print("Closing mobile menu...")
            mobile_page.click("#nav-close")
            time.sleep(0.5)
            is_menu_shown = mobile_page.evaluate("document.getElementById('nav-menu').classList.contains('show-menu')")
            print(f"Mobile menu show-menu active after close: {is_menu_shown}")
            assert not is_menu_shown, "Mobile menu show-menu class was not removed on close click!"

            # Capture Mobile Full Page Screenshot
            screenshot_path_mobile = "verification/screenshots/mobile_full_page.png"
            mobile_page.screenshot(path=screenshot_path_mobile, full_page=True)
            print(f"Captured mobile screenshot: {screenshot_path_mobile}")

            mobile_context.close()
            browser.close()

    except Exception as e:
        print(f"\n❌ Error during UI verification: {e}")
        success = False
    finally:
        print("Stopping local PHP server...")
        subprocess.run("kill $(lsof -t -i :8000) 2>/dev/null || true", shell=True)

    return success

if __name__ == "__main__":
    success = run_ui_tests()
    if not success:
        sys.exit(1)
    print("\n✅ Verification Completed Successfully!")
