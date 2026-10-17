import asyncio
from playwright.async_api import async_playwright

async def run():
    async with async_playwright() as p:
        browser = await p.chromium.launch()
        context = await browser.new_context(viewport={'width': 1280, 'height': 800})
        page = await context.new_page()

        await page.goto("http://127.0.0.1:8000")
        await page.wait_for_selector("#home")

        # Take light mode screenshot
        await page.screenshot(path="verification/screenshots/desktop_light.png")

        # Toggle dark mode
        await page.click("#theme-button")
        await page.wait_for_timeout(500)
        await page.screenshot(path="verification/screenshots/desktop_dark.png")

        # Scroll to contact section and submit contact form in simulation mode
        await page.evaluate("document.getElementById('simulation-mode-input').value = '1'")
        await page.fill("#contact-name", "Budi Santoso")
        await page.fill("#contact-email", "budi@example.com")
        await page.fill("#contact-message-input", "Halo Ar Creative, saya berminat dengan jasa layanan digital Anda.")

        await page.click("#contact-form button[type='submit']")
        await page.wait_for_selector("#contact-message:has-text('Mode Simulasi')")

        await page.screenshot(path="verification/screenshots/desktop_contact_simulated.png")

        await browser.close()

if __name__ == "__main__":
    asyncio.run(run())
