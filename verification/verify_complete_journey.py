import asyncio
from playwright.async_api import async_playwright
import os

async def record_user_journey():
    os.makedirs("verification/videos", exist_ok=True)
    async with async_playwright() as p:
        browser = await p.chromium.launch()
        context = await browser.new_context(
            viewport={"width": 1280, "height": 800},
            record_video_dir="verification/videos"
        )
        page = await context.new_page()
        await page.goto("http://127.0.0.1:8000")
        await asyncio.sleep(1)

        # 1. Scroll through sections
        sections = ['#home', '#about', '#services', '#education', '#social', '#contact']
        for sec in sections:
            await page.locator(sec).scroll_into_view_if_needed()
            await asyncio.sleep(0.8)

        # 2. Toggle Theme
        await page.click("#theme-button")
        await asyncio.sleep(1)

        await page.click("#theme-button")
        await asyncio.sleep(1)

        # 3. Form interaction
        await page.locator('#contact').scroll_into_view_if_needed()
        await page.fill("#user_name", "Budi Santoso")
        await page.fill("#user_email", "budi@arcreative.id")
        await page.fill("#user_project", "Konsultasi produk digital & edukasi")

        await page.evaluate("""() => {
            const form = document.getElementById('contact-form');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'simulation_mode';
            input.value = '1';
            form.appendChild(input);
        }""")

        await page.click("#contact-form button[type='submit']")
        await asyncio.sleep(2)

        # Save video file name properly
        video = page.video
        await context.close()
        await browser.close()

        if video:
            video_path = await video.path()
            target_path = "verification/videos/cuj_journey.webm"
            os.rename(video_path, target_path)
            print(f"Video saved to {target_path}")

if __name__ == "__main__":
    asyncio.run(record_user_journey())
