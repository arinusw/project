import asyncio
from playwright.async_api import async_playwright

async def run():
    async with async_playwright() as p:
        browser = await p.chromium.launch()
        context = await browser.new_context(
            viewport={'width': 1280, 'height': 800},
            record_video_dir="verification/videos/"
        )
        page = await context.new_page()

        # Visit site
        await page.goto("http://127.0.0.1:8000")
        await page.wait_for_selector("#home")
        await page.wait_for_timeout(1000)

        # Smooth scroll through sections
        sections = ["#about", "#services", "#education", "#social", "#contact"]
        for sec in sections:
            await page.evaluate(f"document.querySelector('{sec}').scrollIntoView({{ behavior: 'smooth' }})")
            await page.wait_for_timeout(1200)

        # Toggle Dark Mode
        await page.click("#theme-button")
        await page.wait_for_timeout(1000)

        # Submit contact form in simulation mode
        await page.evaluate("document.getElementById('simulation-mode-input').value = '1'")
        await page.fill("#contact-name", "Jules Tester")
        await page.fill("#contact-email", "jules@example.com")
        await page.fill("#contact-message-input", "Pesan uji coba perjalanan pengguna lengkap.")

        await page.click("#contact-form button[type='submit']")
        await page.wait_for_selector("#contact-message:has-text('Mode Simulasi')")
        await page.wait_for_timeout(1500)

        # Final CUJ Screenshot
        await page.screenshot(path="verification/screenshots/final_cuj.png")

        await context.close()
        await browser.close()

if __name__ == "__main__":
    asyncio.run(run())
