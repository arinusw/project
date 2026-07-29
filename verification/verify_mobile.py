import time
import os
from playwright.sync_api import sync_playwright

def run_mobile_verification():
    print("Starting Mobile UI Verification...")

    # Ensure screenshot directory exists
    os.makedirs('/app/verification/screenshots', exist_ok=True)
    os.makedirs('/home/jules/verification/screenshots', exist_ok=True)

    with sync_playwright() as p:
        # Launch browser (headless to run in sandbox)
        browser = p.chromium.launch(headless=True)

        # Configure viewport to 375x667 for mobile (iPhone SE style)
        context = browser.new_context(viewport={"width": 375, "height": 667})
        page = context.new_page()

        # Open local PHP server address
        url = "http://127.0.0.1:8000/index.html"
        print(f"Navigating to {url} on mobile...")
        page.goto(url)

        # Wait for page load
        page.wait_for_timeout(1000)

        # 1. Verify Mobile UI layout constraints
        print("Verifying Mobile UI constraints...")

        # Logo should be hidden on screen < 767px (to conserve space on fixed header)
        logo = page.locator(".nav__logo")
        logo_visible = logo.is_visible()
        if not logo_visible:
            print("✓ Mobile logo is hidden on mobile layout as expected.")
        else:
            print("✗ Warning: Logo is visible on mobile layout. Check CSS screen width constraints.")

        # Hamburger toggle button should be visible on mobile
        nav_toggle = page.locator("#nav-toggle")
        if nav_toggle.is_visible():
            print("✓ Mobile navigation hamburger toggle is visible.")
        else:
            print("✗ Error: Mobile hamburger toggle is hidden.")

        # 2. Open mobile menu drawer
        print("Opening Mobile Menu Drawer...")
        # Force click to bypass possible ScrollReveal transition overlays
        nav_toggle.click(force=True)
        page.wait_for_timeout(500)

        # Verify menu now has the show-menu class
        menu_opened = page.evaluate("document.getElementById('nav-menu').classList.contains('show-menu')")
        if menu_opened:
            print("✓ Mobile navigation drawer opened (show-menu class added).")
        else:
            print("✗ Error: Mobile navigation drawer did not open.")

        # Take screenshot of open drawer
        screenshot_path_menu = "/app/verification/screenshots/mobile_menu_open.png"
        page.screenshot(path=screenshot_path_menu)
        page.screenshot(path="/home/jules/verification/screenshots/mobile_menu_open.png")
        print(f"✓ Saved mobile open menu screenshot to {screenshot_path_menu}")

        # 3. Close mobile menu drawer
        print("Closing Mobile Menu Drawer...")
        nav_close = page.locator("#nav-close")
        nav_close.click(force=True)
        page.wait_for_timeout(500)

        menu_closed = not page.evaluate("document.getElementById('nav-menu').classList.contains('show-menu')")
        if menu_closed:
            print("✓ Mobile navigation drawer closed (show-menu class removed).")
        else:
            print("✗ Error: Mobile navigation drawer did not close.")

        # 4. Trigger ScrollReveal animations on mobile
        print("Scolling mobile view to activate entrance transitions...")
        for i in range(1, 10):
            page.mouse.wheel(0, 450)
            page.wait_for_timeout(300)

        # Capture bottom contact section on mobile
        page.locator("#contact").scroll_into_view_if_needed()
        page.wait_for_timeout(500)

        screenshot_path_contact = "/app/verification/screenshots/mobile_contact.png"
        page.screenshot(path=screenshot_path_contact)
        page.screenshot(path="/home/jules/verification/screenshots/mobile_contact.png")
        print(f"✓ Saved mobile contact screenshot to {screenshot_path_contact}")

        # Capture services on mobile
        page.locator("#services").scroll_into_view_if_needed()
        page.wait_for_timeout(500)
        page.screenshot(path="/app/verification/screenshots/mobile_services.png")
        page.screenshot(path="/home/jules/verification/screenshots/mobile_services.png")
        print("✓ Saved mobile services screenshot.")

        browser.close()
        print("Mobile verification complete.")

if __name__ == "__main__":
    run_mobile_verification()
