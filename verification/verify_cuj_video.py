import asyncio
import os
import glob
from playwright.async_api import async_playwright

async def run_cuj_journey():
    os.makedirs("/home/jules/verification/screenshots", exist_ok=True)
    os.makedirs("/home/jules/verification/videos", exist_ok=True)

    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        context = await browser.new_context(
            viewport={'width': 1280, 'height': 800},
            record_video_dir="/home/jules/verification/videos"
        )
        page = await context.new_page()

        # 1. Visit Home
        await page.goto("http://127.0.0.1:8000/")
        await page.wait_for_timeout(500)

        # 2. Scroll through sections to display animations and content
        sections = ['#about', '#services', '#education', '#social', '#contact']
        for sec in sections:
            await page.goto(f"http://127.0.0.1:8000/{sec}")
            await page.wait_for_timeout(600)

        # 3. Toggle Dark Mode
        theme_btn = page.locator("#theme-button")
        await theme_btn.click()
        await page.wait_for_timeout(800)

        # 4. Fill & submit contact form
        await page.fill("#name", "Rian Ardianto")
        await page.fill("#email", "rian@arcreative.id")
        await page.fill("#subject", "Pengembangan Website & Edukasi Digital")
        await page.fill("#message", "Halo Ar Creative, kami ingin bekerja sama dalam program edukasi digital dan pembuatan website.")

        await page.evaluate("""() => {
            const form = document.getElementById('contact-form');
            const simInput = document.createElement('input');
            simInput.type = 'hidden';
            simInput.name = 'simulation_mode';
            simInput.value = '1';
            form.appendChild(simInput);
        }""")

        await page.click("button[type='submit']")
        await page.wait_for_selector(".contact__message.success", timeout=5000)
        await page.wait_for_timeout(1000)

        # Take final CUJ screenshot
        await page.screenshot(path="/home/jules/verification/screenshots/final_cuj.png", full_page=False)

        await context.close()
        await browser.close()

if __name__ == "__main__":
    asyncio.run(run_cuj_journey())
