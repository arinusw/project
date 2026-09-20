import asyncio
import os
from playwright.async_api import async_playwright

async def run_journey():
    print("Starting Complete Customer User Journey recording...")
    os.makedirs("verification/videos", exist_ok=True)
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        context = await browser.new_context(
            viewport={"width": 1280, "height": 800},
            record_video_dir="verification/videos/"
        )
        page = await context.new_page()

        # Navigate
        await page.goto("http://127.0.0.1:8000/index.html?simulation_mode=1")
        await page.wait_for_timeout(1000)

        # Toggle Theme to Dark and back to Light
        theme_btn = page.locator("#theme-button")
        await theme_btn.click()
        await page.wait_for_timeout(800)
        await theme_btn.click()
        await page.wait_for_timeout(800)

        # Smooth scrolling down
        for offset in range(0, 3200, 400):
            await page.mouse.wheel(0, 400)
            await page.wait_for_timeout(300)

        # Scroll to Contact Section and Fill Form
        await page.locator("#name").fill("Siti Rahma")
        await page.locator("#email").fill("siti@example.com")
        await page.locator("#subject").fill("Tanya Produk Digital")
        await page.locator("#message").fill("Halo, saya ingin menanyakan opsi lisensi untuk template produk digital Ar Creative.")
        await page.wait_for_timeout(500)

        await page.locator("#contact-form button[type='submit']").click()
        await page.locator("#contact-message").wait_for(state="visible")
        await page.wait_for_timeout(1500)

        # Click Scroll Up Button
        scrollup = page.locator("#scroll-up")
        if await scrollup.is_visible():
            await scrollup.click()
            await page.wait_for_timeout(1000)

        await context.close()
        await browser.close()
        print("Journey complete and video saved in verification/videos/")

if __name__ == "__main__":
    asyncio.run(run_journey())
