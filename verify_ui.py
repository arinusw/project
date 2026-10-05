import asyncio
from playwright.async_api import async_playwright

async def trigger_scroll_animations(page):
    for i in range(10):
        await page.mouse.wheel(0, 800)
        await page.wait_for_timeout(200)

async def run_desktop_verification():
    async with async_playwright() as p:
        browser = await p.chromium.launch()
        context = await browser.new_context(viewport={'width': 1280, 'height': 800})
        page = await context.new_page()

        await page.goto("http://127.0.0.1:8000/")
        await trigger_scroll_animations(page)

        # Scroll back to top for clean initial screenshot
        await page.evaluate("window.scrollTo({top: 0, behavior: 'instant'})")
        await page.wait_for_timeout(300)

        # 1. Desktop Light Theme
        await page.screenshot(path="verification/screenshots/desktop_light.png", full_page=False)

        # 2. Toggle Dark Theme
        theme_btn = page.locator("#theme-button")
        await theme_btn.click()
        await page.wait_for_timeout(300)
        await page.screenshot(path="verification/screenshots/desktop_dark.png", full_page=False)

        # 3. Test Contact Form Submission in Simulation Mode
        await page.evaluate("document.getElementById('contact').scrollIntoView()")
        await page.wait_for_timeout(300)

        await page.fill("#name", "Penguji otomatis")
        await page.fill("#email", "test@example.com")
        await page.fill("#message", "Halo Ar Creative, ini pesan pengujian otomatis!")

        # Inject simulation mode flag into form dynamically
        await page.evaluate("""
            const form = document.getElementById('contact-form');
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'simulation_mode';
            hidden.value = '1';
            form.appendChild(hidden);
        """)

        await page.click(".contact__submit-btn")
        await page.wait_for_selector("#contact-message.success", state="visible")
        await page.screenshot(path="verification/screenshots/desktop_contact_success.png", full_page=False)

        await browser.close()

if __name__ == "__main__":
    asyncio.run(run_desktop_verification())
