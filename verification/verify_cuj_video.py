import asyncio
from playwright.async_api import async_playwright
import os

async def main():
    os.makedirs("verification/videos", exist_ok=True)
    async with async_playwright() as p:
        browser = await p.chromium.launch()
        context = await browser.new_context(
            record_video_dir="verification/videos",
            viewport={"width": 1280, "height": 800}
        )
        page = await context.new_page()

        await page.goto("http://127.0.0.1:8000/")
        await page.wait_for_load_state("networkidle")
        await page.wait_for_timeout(1000)

        # Toggle Dark Mode
        await page.click("#theme-button")
        await page.wait_for_timeout(800)

        # Smooth Scroll through sections
        sections = ["about", "services", "education", "social", "contact"]
        for section in sections:
            await page.evaluate(f"document.getElementById('{section}').scrollIntoView({{behavior: 'smooth'}})")
            await page.wait_for_timeout(1200)

        # Fill and submit form
        await page.fill("#name", "Siti Rahma")
        await page.fill("#email", "siti@example.com")
        await page.fill("#message", "Saya ingin berkonsultasi mengenai pembuatan aplikasi edukasi digital.")
        await page.evaluate("""() => {
            const form = document.getElementById('contact-form');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'simulation_mode';
            input.value = '1';
            form.appendChild(input);
        }""")
        await page.click("#contact-form button[type='submit']")
        await page.wait_for_selector("#contact-message:has-text('Mode Simulasi')", timeout=5000)
        await page.wait_for_timeout(1000)

        # Scroll back up
        await page.click("#scroll-up")
        await page.wait_for_timeout(1000)

        await page.screenshot(path="verification/screenshots/final_cuj.png", full_page=False)
        await context.close()
        await browser.close()
        print("CUJ Video recording completed successfully!")

if __name__ == "__main__":
    asyncio.run(main())
