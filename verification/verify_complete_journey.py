import asyncio
import os
from playwright.async_api import async_playwright

async def run_journey():
    os.makedirs("/home/jules/verification/videos", exist_ok=True)
    os.makedirs("/home/jules/verification/screenshots", exist_ok=True)

    async with async_playwright() as p:
        browser = await p.chromium.launch()
        context = await browser.new_context(
            viewport={'width': 1280, 'height': 800},
            record_video_dir="/home/jules/verification/videos"
        )
        page = await context.new_page()

        # Step 1: Open website in simulation mode
        await page.goto("http://127.0.0.1:8000/index.html?simulation_mode=1")
        await page.wait_for_timeout(1000)

        # Step 2: Scroll through sections
        for i in range(8):
            await page.mouse.wheel(0, 400)
            await page.wait_for_timeout(500)

        # Step 3: Switch theme to dark mode
        await page.click("#theme-button")
        await page.wait_for_timeout(1000)

        # Step 4: Scroll to contact section and fill form
        await page.click("a[href='#contact']")
        await page.wait_for_timeout(1000)

        await page.fill("#name", "Penguji Mandiri")
        await page.wait_for_timeout(300)
        await page.fill("#email", "penguji@arcreative.id")
        await page.wait_for_timeout(300)
        await page.fill("#subject", "Layanan Website & Edukasi Studio")
        await page.wait_for_timeout(300)
        await page.fill("#message", "Halo Ar Creative! Kami mengagumi karya studio dan edukasi Anda. Mohon info lebih lanjut.")
        await page.wait_for_timeout(500)

        # Submit contact form
        await page.click("#contact-form button[type='submit']", force=True)
        await page.wait_for_selector("#contact-message:has-text('Mode Simulasi')", timeout=5000)
        await page.wait_for_timeout(1000)

        # Take final verification screenshot
        await page.screenshot(path="/home/jules/verification/screenshots/journey_final.png")
        await page.wait_for_timeout(1000)

        await context.close()
        await browser.close()
        print("CUJ video recording completed!")

if __name__ == "__main__":
    asyncio.run(run_journey())
