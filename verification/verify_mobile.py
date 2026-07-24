import asyncio
from playwright.async_api import async_playwright

async def run():
    async with async_playwright() as p:
        browser = await p.chromium.launch()
        # Mobile screen size
        context = await browser.new_context(viewport={"width": 375, "height": 667})
        page = await context.new_page()

        # Navigate to local PHP server running on 127.0.0.1:8000
        await page.goto("http://127.0.0.1:8000")

        # Incremental scroll to trigger ScrollReveal animations before screenshot
        for i in range(1, 9):
            await page.mouse.wheel(0, 500)
            await asyncio.sleep(0.3)

        # Scroll back to top
        await page.evaluate("window.scrollTo(0, 0)")
        await asyncio.sleep(0.5)

        # Take full page screenshot of mobile default layout
        await page.screenshot(path="verification/screenshots/mobile_default.png", full_page=True)
        print("Mobile Default Screenshot Saved.")

        # Open mobile navigation menu
        nav_toggle = page.locator("#nav-toggle")
        await nav_toggle.click(force=True)
        await asyncio.sleep(0.5)

        # Capture screenshot with open menu
        await page.screenshot(path="verification/screenshots/mobile_menu_open.png")
        print("Mobile Menu Open Screenshot Saved.")

        # Navigate to contact hash directly to bypass potential overlays
        await page.goto("http://127.0.0.1:8000#contact")
        await asyncio.sleep(0.5)

        # Fill contact form in simulation mode
        await page.evaluate("document.getElementById('simulation_mode').value = '1'")
        await page.fill("#user_name", "Arin Mobile")
        await page.fill("#user_email", "arinmobile@example.com")
        await page.fill("#user_project", "Membangun responsive mobile interface.")

        # Click submit
        submit_btn = page.locator(".contact__submit-btn")
        await submit_btn.click(force=True)

        # Wait for feedback
        feedback = page.locator("#contact-message")
        await feedback.wait_for(state="visible")
        feedback_text = await feedback.inner_text()
        print(f"Mobile Contact form feedback: {feedback_text}")

        # Capture contact success on mobile
        await page.screenshot(path="verification/screenshots/mobile_contact_success.png")
        print("Mobile Contact Success Screenshot Saved.")

        await browser.close()

if __name__ == "__main__":
    asyncio.run(run())
