import asyncio
from playwright.async_api import async_playwright
import os

async def verify_portfolio():
    async with async_playwright() as p:
        browser = await p.chromium.launch()
        context = await browser.new_context(viewport={'width': 1280, 'height': 800})
        page = await context.new_page()

        # Navigate to the local server
        url = "http://127.0.0.1:8000"
        print(f"Navigating to {url}...")
        try:
            await page.goto(url, wait_until="networkidle")
        except Exception as e:
            print(f"Error navigating to {url}: {e}")
            await browser.close()
            return

        # Wait for ScrollReveal animations to settle
        await page.wait_for_timeout(2000)

        # 1. Desktop Screenshot
        print("Capturing desktop screenshot...")
        await page.screenshot(path="verification/screenshots/desktop_home.png", full_page=True)

        # 2. Check Navigation
        print("Checking navigation...")
        await page.click("a[href='#about']")
        await page.wait_for_timeout(1000)
        await page.screenshot(path="verification/screenshots/desktop_about.png")

        # 3. Mobile Viewport Screenshot
        print("Capturing mobile screenshot...")
        await page.set_viewport_size({"width": 375, "height": 667})
        await page.wait_for_timeout(1000)

        # Open mobile menu
        await page.click("#nav-toggle")
        await page.wait_for_timeout(500)
        await page.screenshot(path="verification/screenshots/mobile_menu.png")

        # Close mobile menu
        await page.click("#nav-close")
        await page.wait_for_timeout(500)
        await page.screenshot(path="verification/screenshots/mobile_home.png", full_page=True)

        # 4. Form Submission Simulation
        print("Testing contact form...")
        await page.fill("input[name='user_name']", "Test User")
        await page.fill("input[name='user_email']", "test@example.com")
        await page.fill("textarea[name='user_project']", "Ini adalah pesan tes otomatis.")
        await page.click("button:has-text('Kirim Pesan')")

        # Wait for response
        await page.wait_for_selector("#contact-message:has-text('Pesan Anda telah berhasil terkirim')")
        await page.screenshot(path="verification/screenshots/contact_success.png")

        print("Verification complete! Screenshots saved in verification/screenshots/")
        await browser.close()

if __name__ == "__main__":
    if not os.path.exists("verification/screenshots"):
        os.makedirs("verification/screenshots")
    asyncio.run(verify_portfolio())
