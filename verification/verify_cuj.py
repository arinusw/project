import asyncio
from playwright.async_api import async_playwright

async def trigger_scroll_animations(page):
    for i in range(10):
        await page.mouse.wheel(0, 800)
        await page.wait_for_timeout(200)

async def record_user_journey():
    async with async_playwright() as p:
        browser = await p.chromium.launch()
        context = await browser.new_context(
            viewport={'width': 1280, 'height': 800},
            record_video_dir="verification/videos/"
        )
        page = await context.new_page()

        # Start journey
        await page.goto("http://127.0.0.1:8000/")
        await page.wait_for_timeout(1000)

        # Scroll through sections
        await trigger_scroll_animations(page)

        # Toggle theme
        theme_btn = page.locator("#theme-button")
        await theme_btn.click()
        await page.wait_for_timeout(1000)

        # Navigate to Contact Form
        await page.evaluate("document.getElementById('contact').scrollIntoView()")
        await page.wait_for_timeout(500)

        await page.fill("#name", "Budi Santoso")
        await page.fill("#email", "budi@example.id")
        await page.fill("#message", "Halo Ar Creative, saya tertarik dengan jasa pembuatan website.")

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
        await page.wait_for_timeout(1000)

        await page.screenshot(path="verification/screenshots/cuj_final.png", full_page=False)
        await context.close()
        await browser.close()

if __name__ == "__main__":
    asyncio.run(record_user_journey())
