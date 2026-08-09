import asyncio
import time
from playwright.async_api import async_playwright

async def run_desktop_verification():
    async with async_playwright() as p:
        # Launch browser
        browser = await p.chromium.launch(headless=True)
        # Create context with 1280x800 viewport
        context = await browser.new_context(
            viewport={"width": 1280, "height": 800},
            record_video_dir="verification/videos"
        )
        page = await context.new_page()

        # Open page
        print("Opening website on Desktop...")
        await page.goto("http://127.0.0.1:8000/index.html")
        await page.wait_for_timeout(1000)

        # Trigger ScrollReveal animations by incremental scrolling
        print("Scrolling page incrementally to reveal sections...")
        for y in range(0, 4500, 300):
            await page.mouse.wheel(0, 300)
            await page.wait_for_timeout(150)

        # Scroll back to top
        await page.evaluate("window.scrollTo(0, 0)")
        await page.wait_for_timeout(500)

        # Capture initial light theme desktop screenshot
        await page.screenshot(path="verification/screenshots/desktop_light.png", full_page=True)
        print("Saved desktop_light.png")

        # Toggle theme to dark
        print("Toggling theme...")
        theme_btn = page.locator("#theme-button")
        await theme_btn.click()
        await page.wait_for_timeout(500)

        # Capture dark theme desktop screenshot
        await page.screenshot(path="verification/screenshots/desktop_dark.png", full_page=True)
        print("Saved desktop_dark.png")

        # Fill & Submit Contact form under simulation mode
        print("Testing contact form submission...")
        # Scroll to contact form
        await page.locator("#contact").scroll_into_view_if_needed()
        await page.wait_for_timeout(500)

        # Fill fields
        await page.fill("#user_name", "Budi Santoso")
        await page.fill("#user_email", "budi@gmail.com")
        await page.fill("#user_project", "Saya ingin memesan landing page untuk UMKM saya.")

        # Inject simulation mode input to ensure simulation handles it
        await page.evaluate("""() => {
            const form = document.getElementById('contact-form');
            const simInput = document.createElement('input');
            simInput.type = 'hidden';
            simInput.name = 'simulation_mode';
            simInput.value = '1';
            form.appendChild(simInput);
        }""")

        # Submit form
        await page.locator("#contact-form button[type='submit']").click()

        # Wait for success message visibility
        msg_locator = page.locator("#contact-message")
        await msg_locator.wait_for(state="visible")
        success_text = await msg_locator.text_content()
        print(f"Contact form status message: {success_text}")

        # Save a screenshot of the form submission
        await page.screenshot(path="verification/screenshots/desktop_submitted.png")
        print("Saved desktop_submitted.png")

        # Close
        await context.close()
        await browser.close()

if __name__ == "__main__":
    asyncio.run(run_desktop_verification())
