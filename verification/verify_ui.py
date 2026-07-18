import asyncio
import os
import subprocess
import time
from playwright.async_api import async_playwright

async def run_verification():
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

    # Create screenshots directory
    os.makedirs("verification/screenshots", exist_ok=True)

    async with async_playwright() as p:
        # Browser instance
        browser = await p.chromium.launch(headless=True)

        # Define base URL
        base_url = "http://127.0.0.1:8000"

        # ==========================================================
        # 1. VERIFY DESKTOP VIEWPORT (1280x800)
        # ==========================================================
        print("\n=== Testing Desktop View (1280x800) ===")
        desktop_context = await browser.new_context(viewport={"width": 1280, "height": 800})
        page = await desktop_context.new_page()

        # Navigate to home
        print(f"Navigating to {base_url}...")
        await page.goto(base_url)
        await page.wait_for_timeout(1000) # Wait for page and fonts

        # Incremental scroll to trigger entrance animations (ScrollReveal)
        print("Performing incremental scroll to reveal all sections...")
        for y in range(0, 4500, 300):
            await page.mouse.wheel(0, 300)
            await page.wait_for_timeout(100)

        # Scroll back to top
        await page.evaluate("window.scrollTo({top: 0, behavior: 'instant'})")
        await page.wait_for_timeout(1000)

        # Capture Desktop Light Mode Home Screenshot
        print("Capturing Desktop Light Mode...")
        await page.screenshot(path="verification/screenshots/desktop_light_home.png", full_page=False)

        # Test Dark Theme Switch
        print("Toggling dark theme...")
        theme_btn = page.locator("#theme-button")
        await theme_btn.click()
        await page.wait_for_timeout(500)

        # Capture Desktop Dark Mode Home Screenshot
        print("Capturing Desktop Dark Mode...")
        await page.screenshot(path="verification/screenshots/desktop_dark_home.png", full_page=False)

        # Scroll to Contact Section and submit the form in Simulation Mode
        print("Testing Contact Form submission on Desktop...")
        # Scroll down to contact section
        await page.locator("#contact").scroll_into_view_if_needed()
        await page.wait_for_timeout(500)

        # Set simulation_mode to 1
        await page.evaluate("document.getElementById('simulation_mode').value = '1'")

        # Fill Form
        await page.fill("#user_name", "Arinus Wantik")
        await page.fill("#user_email", "arinus.wantik@example.com")
        await page.fill("#user_project", "Halo Ar Creative! Saya ingin memesan website portofolio studio kustom untuk produk digital saya.")

        # Submit
        await page.click("form#contact-form button[type='submit']")
        await page.wait_for_timeout(1500) # Wait for Fetch response

        # Capture Form Success message Screenshot
        print("Capturing contact form success state...")
        await page.screenshot(path="verification/screenshots/contact_submit_success.png")

        # Verify success text in Indonesian
        message_element = page.locator("#contact-message")
        message_text = await message_element.inner_text()
        print(f"Form submission response: {message_text}")

        await desktop_context.close()

        # ==========================================================
        # 2. VERIFY MOBILE VIEWPORT (375x667)
        # ==========================================================
        print("\n=== Testing Mobile View (375x667) ===")
        mobile_context = await browser.new_context(
            viewport={"width": 375, "height": 667},
            is_mobile=True,
            has_touch=True
        )
        mobile_page = await mobile_context.new_page()

        await mobile_page.goto(base_url)
        await mobile_page.wait_for_timeout(1000)

        # Mobile Menu Toggle test
        print("Opening mobile navigation menu...")
        await mobile_page.click("#nav-toggle", force=True)
        await mobile_page.wait_for_timeout(500)

        # Capture Mobile Navigation open state
        print("Capturing Mobile Menu Open state...")
        await mobile_page.screenshot(path="verification/screenshots/mobile_menu_open.png")

        # Close Mobile Menu
        print("Closing mobile navigation menu...")
        await mobile_page.click("#nav-close", force=True)
        await mobile_page.wait_for_timeout(500)

        # Scroll down and capture responsive section layout
        print("Scrolling down to Layanan Section on Mobile...")
        await mobile_page.locator("#services").scroll_into_view_if_needed()
        await mobile_page.wait_for_timeout(500)

        # Capture Services Layout on Mobile
        await mobile_page.screenshot(path="verification/screenshots/mobile_services.png")

        await mobile_context.close()
        await browser.close()

        # Stop PHP server
        print("\nShutting down PHP server...")
        server_process.terminate()
        server_process.wait()
        print("PHP server stopped.")

if __name__ == "__main__":
    asyncio.run(run_verification())
