import asyncio
from playwright.async_api import async_playwright

async def verify_mobile():
    print("Starting Mobile Verification...")
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        # Create a browser context with mobile viewport
        context = await browser.new_context(viewport={"width": 375, "height": 667})
        page = await context.new_page()

        # Navigate to home page
        await page.goto("http://127.0.0.1:8000")
        await page.wait_for_timeout(2000)

        # Take initial screenshot of mobile home
        await page.screenshot(path="verification/screenshots/mobile_home.png")
        print("Mobile Home Screenshot saved.")

        # Test Mobile Menu toggle drawer (open and close)
        # ScrollReveal overlay might block click, use force=True per guideline
        nav_toggle = page.locator("#nav-toggle")
        await nav_toggle.click(force=True)
        await page.wait_for_timeout(1000)
        await page.screenshot(path="verification/screenshots/mobile_menu_open.png")
        print("Mobile Menu Open Screenshot saved.")

        nav_close = page.locator("#nav-close")
        await nav_close.click(force=True)
        await page.wait_for_timeout(1000)
        await page.screenshot(path="verification/screenshots/mobile_menu_closed.png")
        print("Mobile Menu Closed Screenshot saved.")

        # Test simulated contact form submission on mobile
        await page.locator("#user_name").fill("Jane Doe Mobile")
        await page.locator("#user_email").fill("jane.mobile@example.com")
        await page.locator("#user_project").fill("Saya butuh jasa editing video multimedia untuk promosi brand.")

        await page.locator("#contact").scroll_into_view_if_needed()
        await page.wait_for_timeout(1000)

        await page.locator('#contact-form button[type="submit"]').click(force=True)

        success_msg = page.locator("#contact-message")
        await success_msg.wait_for(state="visible")
        print("Mobile Form response message:", await success_msg.inner_text())

        await page.screenshot(path="verification/screenshots/mobile_form_submitted.png")
        print("Mobile Form Submitted Screenshot saved.")

        await browser.close()

if __name__ == "__main__":
    asyncio.run(verify_mobile())
