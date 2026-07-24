import asyncio
from playwright.async_api import async_playwright

async def run():
    async with async_playwright() as p:
        browser = await p.chromium.launch()
        # Desktop screen size
        context = await browser.new_context(viewport={"width": 1280, "height": 800})
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

        # Take full page screenshot of the default light mode
        await page.screenshot(path="verification/screenshots/desktop_light.png", full_page=True)
        print("Desktop Light Mode Screenshot Saved.")

        # Toggle Dark Theme
        theme_btn = page.locator("#theme-button")
        await theme_btn.click()
        await asyncio.sleep(0.5)

        # Take dark theme screenshot
        await page.screenshot(path="verification/screenshots/desktop_dark.png", full_page=True)
        print("Desktop Dark Mode Screenshot Saved.")

        # Fill & Submit Contact Form in Simulation Mode
        # Set simulation mode hidden field value to "1"
        await page.evaluate("document.getElementById('simulation_mode').value = '1'")

        await page.fill("#user_name", "Arin")
        await page.fill("#user_email", "arinusw@example.com")
        await page.fill("#user_project", "Membangun website portofolio studio kreatif digital.")

        # Click submit
        submit_btn = page.locator(".contact__submit-btn")
        await submit_btn.click()

        # Wait for the success feedback message
        feedback = page.locator("#contact-message")
        await feedback.wait_for(state="visible")
        feedback_text = await feedback.inner_text()
        print(f"Contact form feedback: {feedback_text}")

        # Take screenshot of contact submission success
        # Scroll to contact section to capture message clearly
        await page.goto("http://127.0.0.1:8000#contact")
        await asyncio.sleep(0.5)
        await page.screenshot(path="verification/screenshots/desktop_contact_success.png")
        print("Desktop Contact Success Screenshot Saved.")

        await browser.close()

if __name__ == "__main__":
    asyncio.run(run())
