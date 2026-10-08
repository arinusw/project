import asyncio
import os
from playwright.async_api import async_playwright

async def run_cuj_video():
    os.makedirs("verification/screenshots", exist_ok=True)
    os.makedirs("verification/videos", exist_ok=True)

    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        context = await browser.new_context(
            viewport={'width': 1280, 'height': 800},
            record_video_dir="verification/videos/"
        )
        page = await context.new_page()

        # Journey 1: Open Home Page
        await page.goto("http://127.0.0.1:8000")
        await page.wait_for_load_state("networkidle")
        await page.wait_for_timeout(1000)

        # Journey 2: Scroll through sections
        for section in ["#about", "#services", "#education", "#social", "#contact"]:
            await page.evaluate(f"document.querySelector('{section}').scrollIntoView({{behavior: 'smooth'}})")
            await page.wait_for_timeout(1200)

        # Journey 3: Dark Mode Switch
        await page.locator("#theme-button").click()
        await page.wait_for_timeout(1000)

        # Journey 4: Submit Contact Form
        await page.evaluate("document.querySelector('#contact').scrollIntoView({behavior: 'instant'})")
        await page.locator("#name").fill("Customer Exploration")
        await page.locator("#email").fill("customer@example.com")
        await page.locator("#message").fill("Ingin berdiskusi tentang pengembangan produk digital.")

        await page.evaluate("""() => {
            const form = document.getElementById('contact-form');
            const sim = document.createElement('input');
            sim.type = 'hidden';
            sim.name = 'simulation_mode';
            sim.value = '1';
            form.appendChild(sim);
        }""")

        await page.locator("button.contact__submit").click()
        await page.locator("#contact-message").wait_for(state="visible")
        await page.wait_for_timeout(1500)

        # Take final CUJ screenshot
        await page.screenshot(path="verification/screenshots/final_cuj.png", full_page=False)

        await context.close()
        await browser.close()
        print("Customer User Journey video recorded successfully!")

if __name__ == '__main__':
    asyncio.run(run_cuj_video())
