import asyncio
from playwright.async_api import async_playwright
import os

async def record_cuj():
    os.makedirs("verification/screenshots", exist_ok=True)
    os.makedirs("verification/videos", exist_ok=True)

    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        context = await browser.new_context(
            viewport={'width': 1280, 'height': 800},
            record_video_dir="verification/videos/"
        )
        page = await context.new_page()

        # 1. Access Homepage
        await page.goto("http://127.0.0.1:8000/", wait_until="networkidle")
        await page.wait_for_timeout(1000)

        # 2. Toggle Theme
        theme_btn = page.locator("#theme-button")
        await theme_btn.click()
        await page.wait_for_timeout(1000)
        await theme_btn.click()
        await page.wait_for_timeout(1000)

        # 3. Smooth Scroll Through Sections
        sections = ["#about", "#services", "#education", "#social", "#contact"]
        for sec in sections:
            await page.locator(sec).scroll_into_view_if_needed()
            await page.wait_for_timeout(1200)

        # 4. Fill and Submit Contact Form
        await page.fill("#name", "Rian Wijaya")
        await page.fill("#email", "rian@example.com")
        await page.fill("#message", "Saya ingin berkonsultasi mengenai pembuatan produk digital untuk lembaga kami.")

        await page.evaluate("""
            const form = document.getElementById('contact-form');
            const sim = document.createElement('input');
            sim.type = 'hidden';
            sim.name = 'simulation_mode';
            sim.value = '1';
            form.appendChild(sim);
        """)

        await page.click("#contact-form button[type='submit']")
        await page.locator("#contact-message").wait_for(state="visible")
        await page.wait_for_timeout(1500)

        # 5. Capture Final Screenshot
        await page.screenshot(path="verification/screenshots/final_cuj.png", full_page=False)

        await context.close()
        await browser.close()

    print("Customer User Journey recording complete!")

if __name__ == "__main__":
    asyncio.run(record_cuj())
