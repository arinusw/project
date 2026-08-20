import asyncio
import os
from playwright.async_api import async_playwright

async def run_cuj_video():
    os.makedirs("verification/videos", exist_ok=True)
    async with async_playwright() as p:
        browser = await p.chromium.launch()
        context = await browser.new_context(
            viewport={"width": 1280, "height": 800},
            record_video_dir="verification/videos/"
        )
        page = await context.new_page()

        await page.goto("http://127.0.0.1:8000/")
        await page.wait_for_timeout(1000)

        # Toggle theme
        await page.click("#theme-button")
        await page.wait_for_timeout(800)

        # Scroll to services
        await page.click("a[href='#services']")
        await page.wait_for_timeout(1000)

        # Scroll to contact
        await page.click("a[href='#contact']")
        await page.wait_for_timeout(1000)

        # Fill contact form
        await page.fill("#user_name", "Ahmad Test")
        await page.fill("#user_email", "ahmad@example.com")
        await page.fill("#user_project", "Halo Ar Creative, saya berminat membuat website portofolio studio.")

        # Submit form
        await page.click("#contact-form button[type='submit']")
        await page.wait_for_timeout(2000)

        await page.screenshot(path="verification/screenshots/contact_submitted.png")

        await context.close()
        await browser.close()

if __name__ == "__main__":
    asyncio.run(run_cuj_video())
